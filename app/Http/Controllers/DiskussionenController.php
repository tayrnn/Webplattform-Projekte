<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Projekt\Projekt;
use App\Models\Diskussion\Diskussion;
use App\Models\Diskussion\Diskussionsantwort;
use App\Models\Diskussion\Umfrage;
use App\Models\Diskussion\UmfrageStimme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiskussionenController extends Controller
{   
    /**
     * Zeigt die Projekt-Detailseite an
     */
    public function anzeigen($projektId) {
        
        $projekt = Projekt::with(['ersteller', 'kategorien'])->findOrFail($projektId);

        // Kopf : Sternebewertungen
        $sterneDurchschnitt = $projekt->bewertungen()->avg('sterne') ?? 0; 
        $anzahlBewertungen = $projekt->bewertungen()->count();

        // Rechte Seite : Umfragen
        $umfragen = Umfrage::with(['ersteller', 'optionen.stimmen'])
            ->where('projekt_id', $projektId)
            ->orderBy('created_at', 'desc') 
            ->get();

        $hatAbgestimmt = false;

        if ($umfragen->isNotEmpty()) {
            $hatAbgestimmt = UmfrageStimme::whereIn('umfrage_id', $umfragen->pluck('id'))
                ->where('user_id', auth()->id())
                ->exists();
        }

        // Linke Seite : Diskussionen
        $diskussion = Diskussion::where('project_id', $projektId)->first();
        $beitraege = collect();

        if ($diskussion) {
            $beitraege = Diskussionsantwort::with(['ersteller', 'unterantworten.ersteller'])
                ->where('discussion_id', $diskussion->id)
                ->whereNull('parent_id') // Nur Hauptkommentare
                ->orderBy('created_at', 'asc')
                ->get();
        }

        /**
        * Daten für die Detailansicht der Diskussion vorbereiten
        */
        return view('diskussion.details', compact(
            'projekt', 'diskussion', 'umfragen', 'sterneDurchschnitt',
            'anzahlBewertungen', 'hatAbgestimmt', 'beitraege'));
    }

    // -- Diskussionen-Detailseite --

    /**
     * Erstellt eine neue Diskussion
     */
    public function diskussionSpeichern(Request $request, Projekt $projekt) {
        // Da kein Beitrag erstellt wird, brauchen wir nur den Titel
        $request->validate([
            'titel' => 'required|string|max:255',
        ]);

        // Thema in der Datenbank anlegen
        $diskussion = Diskussion::create([
            'project_id' => $projekt->id,
            'user_id'    => auth()->id(),
            'title'      => $request->titel,
        ]);

        // Direkt zur Detailansicht der neuen (noch leeren) Diskussion weiterleiten
        return redirect()->route('diskussion.details', $diskussion->id)
            ->with('erfolg', 'Diskussionsthema erfolgreich angelegt!');
    }

    /**
     * Erstellt einen neuen Hauptkommentar
     */
    public function beitragSpeichern(Request $request, Diskussion $diskussion) {
        $request->validate([
            'content' => 'required|string',
        ]);

        Diskussionsantwort::create([
            'discussion_id' => $diskussion->id,
            'user_id'       => auth()->id(),
            'content'       => $request->content,
            'parent_id'     => null, // Hauptkommentar
        ]);

        return redirect()->back()->with('erfolg', 'Dein Beitrag wurde erfolgreich hinzugefügt!');

    }

    /**
     * Erstellt eine neue Antwort auf einen Beitrag
     */
    public function antwortSpeichern(Request $request, Diskussionsantwort $beitrag) {
        $request->validate([
            'content' => 'required|string',
        ]);

        $hauptbeitrag = $beitrag->parent_id ? Diskussionsantwort::find($beitrag->parent_id) : $beitrag;

        Diskussionsantwort::create([
            'discussion_id' => $hauptbeitrag->discussion_id,
            'user_id'       => auth()->id(),
            'content'       => $request->content,
            'parent_id'     => $hauptbeitrag->id, // Antwort auf diesen Beitrag
        ]);

        return redirect()->back()->with('erfolg', 'Antwort wurde erfolgreich hinzugefügt!');
    }

    /**
     * Löscht einen Beitrag (Ersteller oder Admin)
     */
    public function beitragLoeschen($id) {
        $beitrag = Diskussionsantwort::findOrFail($id);

        // 1. Berechtigungsprüfung (mit deiner neuen Logik aus dem Modell)
        if (!$beitrag->darfLoeschen(auth()->user())) {
            return redirect()->back()->with('fehler', 'Du hast keine Berechtigung.');
        }

        // 2. Einfaches Soft-Delete für ALLE Beiträge (egal ob Haupt oder Unter)
        // Wir setzen einfach nur den Zeitstempel. Die Hierarchie bleibt erhalten.
        $beitrag->update([
            'deleted_at' => now(),
            'content'    => '[Dieser Beitrag wurde gelöscht]' // Optional: Inhalt leeren
        ]);

        return redirect()->back()->with('erfolg', 'Beitrag gelöscht am: ' . now()->format('d.m.Y H:i'));
    }

    /**
     * Löscht die Diskussion (Admin)
     */
    public function diskussionLoeschen($projektId) {
        $diskussion = Diskussion::where('project_id', $projektId)->first();

        if (!$diskussion) {
            return redirect()->back()->with('fehler', 'Keine Diskussion zum Löschen gefunden.');
        }

        $istAdmin = auth()->user()->isAdmin();
        $istErsteller = auth()->id() === $diskussion->user_id;
        $hatBeitraege = method_exists($diskussion, 'antworten') ? $diskussion->antworten()->exists() : $diskussion->beitraege()->exists();

        if ($istErsteller && $hatBeitraege) {
           return redirect()->back()->with('fehler', 'Du kannst die Diskussion nicht löschen, da Beiträge existieren. Bitte lösche zuerst alle Beiträge oder kontaktiere einen Admin.');
        }

        if (!$istErsteller && !$istAdmin) {
            return redirect()->back()->with('fehler', 'Du hast keine Berechtigung, diese Diskussion zu löschen.');
        }

        $diskussion->delete();
        return redirect()->back()->with('erfolg', 'Die Diskussion wurde erfolgreich gelöscht!');
        }


    public function beitragBearbeiten(Request $request, $id) {
        $beitrag = Diskussionsantwort::findOrFail($id);
    
        if (!$beitrag->darfBearbeiten(auth()->user())) {
            return back()->with('fehler', 'Du darfst diesen Beitrag nicht bearbeiten.');
        }

        $request->validate(['content' => 'required|string']);

        $beitrag->update([
            'content' => $request->content,
            'edited_at' => now(), // Setzt den Zeitstempel
        ]);

        return back()->with('erfolg', 'Beitrag wurde aktualisiert.');
}
}