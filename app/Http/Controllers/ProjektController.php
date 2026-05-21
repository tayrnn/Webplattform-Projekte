<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ProjektController extends Controller
{
    // Alle Projekte anzeigen (mit Filter nach Status)
    public function liste(Request $request)
    {
        $filterKategorie = $request->input('filterKategorie');
        $filterStatus    = $request->input('filterStatus');

        $projekte = Project::with(['user'])
            ->when($filterStatus, fn($q) => $q->where('bearbeitungsstatus', $filterStatus))
            ->latest()
            ->get();

        $kategorien   = Category::all();
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
        $projekte = Project::with(['user'])
            ->where('ersteller_id', Auth::id())
            ->latest()
            ->get();

        $kategorien      = Category::all();
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
        $kategorien = Category::all();
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
        Project::create([
            'projektname'        => $validierteEingaben['projektname'],
            'beschreibung'       => $validierteEingaben['beschreibung'],
            'bearbeitungsstatus' => 'neu',
            'ersteller_id'       => Auth::id(),
        ]);

        return redirect()->route('projekte.liste')
            ->with('erfolg', 'Deine Projektidee wurde erfolgreich eingereicht!');
    }

    // Ein Projekt anzeigen
    public function details(int $id)
    {
        $projekt      = Project::with(['user'])->findOrFail($id);
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
    public function bearbeiten(int $id)
    {
        $projekt = Project::findOrFail($id);

        // Sicherheitspruefung: nur der Ersteller darf bearbeiten
        if ($projekt->ersteller_id !== Auth::id()) {
            return redirect()->route('projekte.liste')
                ->with('fehler', 'Du kannst nur deine eigenen Ideen bearbeiten.');
        }

        $kategorien = Category::all();
        $istStudent = true;

        // View zu projekt.bearbeiten fehlt noch
        return view('projekte.bearbeiten', compact('projekt', 'kategorien', 'istStudent'));
    }

    // Projekt aktualisieren
    public function aktualisieren(Request $request, int $id)
    {
        $projekt = Project::findOrFail($id);

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
        ]);

        return redirect()->route('projekte.details', $projekt->id)
            ->with('erfolg', 'Projektidee erfolgreich aktualisiert!');
    }

    // Projekt loeschen (nur eigene Idee)
    public function loeschen(int $id)
    {
        $projekt = Project::findOrFail($id);

        // Sicherheitspruefung
        if ($projekt->ersteller_id !== Auth::id()) {
            return redirect()->route('projekte.liste')
                ->with('fehler', 'Du kannst nur deine eigenen Ideen löschen.');
        }

        $projekt->delete();

        return redirect()->route('projekte.liste')
            ->with('erfolg', 'Projektidee wurde erfolgreich gelöscht.');
    }
}