<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projekt\Projekt;
use Illuminate\Support\Facades\Auth;

class BetreuungController extends Controller
{
    public function index()
    {
        $projekte = Projekt::with(['ersteller', 'kategorien'])
            ->where('betreuer_id', Auth::id())
            ->latest()
            ->get();

        $kategorien      = \App\Models\Projekt\Kategorie::all();
        $istStudent      = false;
        $istLehrender    = true;
        $istAdmin        = false;
        $filterKategorie = null;
        $filterStatus    = null;

        return view('projekte.liste', compact(
            'projekte',
            'kategorien',
            'istStudent',
            'istLehrender',
            'istAdmin',
            'filterKategorie',
            'filterStatus'
        ));
    }

    public function uebernehmen($id)
    {
        $projekt = Projekt::findOrFail($id);

        $projekt->update([
            'betreuer_id' => Auth::id(),
            'bearbeitungsstatus' => 'in_bearbeitung',
        ]);

        return redirect('/lehrende/betreute-projekte')
            ->with('success', 'Projekt wurde zur Betreuung übernommen.');
    }

    public function beenden($id)
    {
        $projekt = Projekt::findOrFail($id);

        $projekt->update([
            'betreuer_id' => null,
            'bearbeitungsstatus' => 'offen',
        ]);

        return redirect('/lehrende/betreute-projekte')
            ->with('success', 'Betreuung wurde beendet.');
    }
}