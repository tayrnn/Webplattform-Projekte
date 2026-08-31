<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diskussion\Umfrage;
use App\Models\Diskussion\UmfrageOption;
use App\Models\Diskussion\UmfrageStimme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UmfrageController extends Controller
{
    /**
     * Starte eine neue Umfrage
     */
    public function umfrageErstellen(Request $request)
    {
        $request->validate([
            'projekt_id' => 'required|exists:projekte,id',
            'frage' => 'required|string|max:255',
            'optionen' => 'required|array|min:2|max:10',
            'optionen.*' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // Umfrage erstellen
            $umfrage = Umfrage::create([
                'projekt_id' => $request->projekt_id,
                'user_id' => Auth::id(),
                'frage' => $request->frage,
            ]);

            // Optionen erstellen
            foreach ($request->optionen as $optionText) {
                if (!empty(trim($optionText))) {
                    UmfrageOption::create([
                        'umfrage_id' => $umfrage->id,
                        'option_text' => trim($optionText),
                    ]);
                }
            }
        });

        return redirect()->back()->with('erfolg', 'Die Umfrage wurde erfolgreich erstellt!');
    }

    /**
     * Abstimmen bei einer Umfrage
     */
    public function abstimmen(Request $request, $umfrageId)
    {
        $request->validate([
            'option_id' => [
                'required',
                'exists:poll_options,id',
                function ($attribute, $value, $fail) use ($umfrageId) {
                    $optionExistiertInUmfrage = DB::table('poll_options')
                        ->where('id', $value)
                        ->where('discussion_answer_id', $umfrageId)
                        ->exists();


                    if (!$optionExistiertInUmfrage) {
                        $fail('Die ausgewählte Option gehört nicht zu dieser Umfrage.');
                    }
                },
            ]
        ]);

        $umfrage = Umfrage::findOrFail($umfrageId);

        if ($umfrage->hatNutzerAbgestimmt(Auth::id())) {
            return redirect()->back()->with('fehler', 'Du hast bereits abgestimmt.');
        }

        UmfrageStimme::create([
            'discussion_answer_id' => $umfrage->id,
            'user_id' => Auth::id(),
            'poll_option_id' => $request->option_id,
        ]);

        return redirect()->back()->with('erfolg', 'Deine Stimme wurde gezählt!');
    }

    /**
     * Löscht eine Stimme bei einer Umfrage
     */
    public function stimmeLoeschen($umfrageId)
    {
        $umfrage = Umfrage::findOrFail($umfrageId);

        $stimme = UmfrageStimme::where('discussion_answer_id', $umfrage->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($stimme) {
            $stimme->delete();
            return redirect()->back()->with('erfolg', 'Deine Stimme wurde gelöscht!');
        }

        return redirect()->back()->with('fehler', 'Du hast noch nicht abgestimmt.');
    }

    /**
     * Zeigt die Ergebnisse einer Umfrage an
     */
    public function ergebnisse($umfrageId)
    {
        $umfrage = Umfrage::with('optionen.stimmen')->findOrFail($umfrageId);

        return view('umfrage.ergebnisse', compact('umfrage'));
    }

    /**
     * Löscht eine Umfrage (nur für den Ersteller oder Admins)
     */
    public function umfrageLoeschen($umfrageId)
    {
        $umfrage = Umfrage::findOrFail($umfrageId);
        $istAdmin = Auth::user()->role === 'admin';
        $istErsteller = $umfrage->user_id === Auth::id();

        if (!$istErsteller && !$istAdmin) {
            return redirect()->back()->with('fehler', 'Keine Berechtigung zum Löschen.');
        }

        $umfrage->optionen()->delete();
        UmfrageStimme::where('discussion_answer_id', $umfrage->id)->delete();
        $umfrage->delete();

        return redirect()->back()->with('erfolg', 'Umfrage wurde gelöscht.');
    }
}