<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projekt\Projekt;
use App\Models\Projekt\Kategorie;
use Illuminate\Support\Facades\Auth;

class ProjektController extends Controller
{
    // Alle Projekte anzeigen (mit Filter nach Status)
    public function liste(Request $request)
    {
        $filterKategorie = $request->input('filterKategorie');
        $filterStatus    = $request->input('filterStatus');

        $projekte = Projekt::with(['ersteller', 'kategorien'])
            ->where(function ($query) {
                // Oeffentliche Projekte fuer alle sichtbar
                $query->where('is_public', true)
                    // Private Projekte nur fuer den Ersteller sichtbar
                    ->orWhere(function ($q) {
                        $q->where('is_public', false)
                            ->where('ersteller_id', Auth::id());
                    });
            })
            ->when($filterStatus,    fn($q) => $q->where('bearbeitungsstatus', $filterStatus))
            ->when($filterKategorie, fn($q) => $q->whereHas('kategorien', function ($sq) use ($filterKategorie) {
                $sq->where('id', $filterKategorie);
            }))
            ->latest()
            ->get();

        $kategorien   = Kategorie::all();
        $istStudent   = Auth::check() && Auth::user()->role === 'student';
        $istLehrender = Auth::check() && Auth::user()->role === 'lehrender';
        $istAdmin     = Auth::check() && Auth::user()->role === 'admin';

        return view('projekte.liste', compact(
            'projekte',
            'kategorien',
            'filterKategorie',
            'filterStatus',
            'istStudent',
            'istLehrender',
            'istAdmin'
        ));
    }

    // Nur eigene Projekte des eingeloggten Studenten anzeigen
    public function meine(Request $request)
    {
        // Zeigt ALLE eigenen Projekte (privat + oeffentlich)
        $projekte = Projekt::with(['ersteller', 'kategorien'])
            ->where('ersteller_id', Auth::id())
            ->latest()
            ->get();

        $kategorien      = Kategorie::all();
        $filterKategorie = null;
        $filterStatus    = null;
        $istStudent      = true;
        $istLehrender    = false;
        $istAdmin        = false;

        return view('projekte.liste', compact(
            'projekte',
            'kategorien',
            'filterKategorie',
            'filterStatus',
            'istStudent',
            'istLehrender',
            'istAdmin'
        ));
    }

    // Formular fuer neue Projektidee anzeigen
    public function erstellen()
    {
        $kategorien = Kategorie::all();
        $istStudent = Auth::check() && Auth::user()->role === 'student';

        return view('projekte.erstellen', compact('kategorien', 'istStudent'));
    }

    // Neue Projektidee speichern
    public function speichern(Request $request)
    {
        $validierteEingaben = $request->validate([
            'projektname'  => 'required|string|max:255',
            'beschreibung' => 'required|string',
        ]);

        // Projekt erstellen - Status wird automatisch auf "neu" gesetzt
        Projekt::create([
            'projektname'        => $validierteEingaben['projektname'],
            'beschreibung'       => $validierteEingaben['beschreibung'],
            'bearbeitungsstatus' => 'neu',
            'mitglied'           => '',
            'ersteller_id'            => Auth::id(),
            'is_public'          => $request->input('is_public', 1),
        ]);

        return redirect()->route('projekte.liste')
            ->with('erfolg', 'Deine Projektidee wurde erfolgreich eingereicht!');
    }

    // Ein Projekt anzeigen
    public function details($id)
    {
        $projekt = Projekt::with(['ersteller'])->findOrFail($id);

        // Sicherheitspruefung: Private Projekte nur fuer Ersteller sichtbar
        if (!$projekt->is_public && $projekt->ersteller_id !== Auth::id()) {
            return redirect()->route('projekte.liste')
                ->with('fehler', 'Dieses Projekt ist privat.');
        }

        $istStudent   = Auth::check() && Auth::user()->role === 'student';
        $istLehrender = Auth::check() && Auth::user()->role === 'lehrender';
        $istAdmin     = Auth::check() && Auth::user()->role === 'admin';

        return view('projekte.details', compact(
            'projekt',
            'istStudent',
            'istLehrender',
            'istAdmin'
        ));
    }

    // Bearbeitungs-Formular anzeigen (nur fuer eigene Idee)
    public function bearbeiten($id)
    {
        $projekt = Projekt::findOrFail($id);

        // Sicherheitspruefung: nur der Ersteller darf bearbeiten
        if ($projekt->ersteller_id !== Auth::id()) {
            return redirect()->route('projekte.liste')
                ->with('fehler', 'Du kannst nur deine eigenen Ideen bearbeiten.');
        }

        $kategorien = Kategorie::all();
        $istStudent = true;

        return view('projekte.bearbeiten', compact('projekt', 'kategorien', 'istStudent'));
    }

    // Projekt aktualisieren
    public function aktualisieren(Request $request, $id)
    {
        $projekt = Projekt::findOrFail($id);

        // Sicherheitspruefung
        if ($projekt->ersteller_id !== Auth::id()) {
            return redirect()->route('projekte.liste')
                ->with('fehler', 'Du kannst nur deine eigenen Ideen bearbeiten.');
        }

        $validierteEingaben = $request->validate([
            'projektname'  => 'required|string|max:255',
            'beschreibung' => 'required|string',
        ]);

        $projekt->update([
            'projektname'  => $validierteEingaben['projektname'],
            'beschreibung' => $validierteEingaben['beschreibung'],
            'is_public'    => $request->input('is_public', 1),
        ]);

        return redirect()->route('projekte.details', $projekt->id)
            ->with('erfolg', 'Projektidee erfolgreich aktualisiert!');
    }

    // Projekt loeschen (Student: nur eigene / Admin: alle)
    public function loeschen($id)
    {
        $projekt = Projekt::findOrFail($id);

        $istAdmin = Auth::check() && Auth::user()->role === 'admin';

        // Sicherheitspruefung: Student nur eigene, Admin alle
        if (!$istAdmin && $projekt->ersteller_id !== Auth::id()) {
            return redirect()->route('projekte.liste')
                ->with('fehler', 'Du kannst nur deine eigenen Ideen löschen.');
        }

        $projekt->delete();

        return redirect()->route('projekte.liste')
            ->with('erfolg', 'Projektidee wurde erfolgreich gelöscht.');
    }
}
