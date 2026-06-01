<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projekt\Projekt;
use Illuminate\Support\Facades\Auth;

class BetreuungController extends Controller
{
    public function index()
    {
        $projekte = Projekt::where('betreuer_id', Auth::id())->get();

        return view('lehrende.dashboard', compact('projekte'));
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