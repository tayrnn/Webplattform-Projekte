<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projekt\Bewertung;
use Illuminate\Support\Facades\Auth;

class BewertungsController extends Controller
{
    /**
     * Speichert oder aktualisiert eine Bewertung für ein Projekt
     */
    public function bewerten(Request $request, $projektId)
    {
        $request->validate([
            'sterne' => 'required|integer|min:1|max:5',
        ]);

        Bewertung::updateOrCreate(
            ['projekt_id' => $projektId, 'nutzer_id' => Auth::id()],
            ['sterne' => $request->sterne]
        );

        return redirect()->back()->with('erfolg', 'Deine Bewertung wurde erfolgreich gespeichert!');
    }

    /**
     * Löscht die Bewertung eines Nutzers
     */
    public function bewertungLoeschen(int $projektId)
    {
        $deleted = Bewertung::where('projekt_id', $projektId)
            ->where('nutzer_id', Auth::id())
            ->delete();

        if ($deleted) {
            return redirect()->back()->with('erfolg', 'Deine Bewertung wurde erfolgreich gelöscht!');
        }
        
        return redirect()->back()->with('fehler', 'Du hast keine Bewertung für dieses Projekt abgegeben.');
    }
}