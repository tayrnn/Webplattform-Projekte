<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Projekt\Projekt;
use App\Models\Diskussion\Diskussion;
use App\Models\Diskussion\Diskussionsantwort;
use App\Models\Diskussion\UmfrageOption;
use App\Models\Diskussion\UmfrageStimme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiskussionController extends Controller
{
    public function create(Projekt $projekt)
    {
        return view('diskussion.create', compact('projekt'));
    }

    /**
     * Erstellt nur das übergeordnete Diskussionsthema (ohne Beitrag).
     */
    public function speichern(Request $request, Projekt $projekt)
    {
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
     * Zeigt den gesamten Thread an.
     */
    public function details(Diskussion $diskussion)
    {
        $diskussion->load(['antworten.ersteller', 'projekt']);

        return view('projekte.diskussion-details', compact('diskussion'));
    }

    /**
     * Route zum Abstimmen auf eine spezifische Umfrage
     */
    public function abstimmen(Request $request, $antwortId)
    {
        $request->validate([
            'option_id' => 'required|exists:poll_options,id'
        ]);

        $antwort = Diskussionsantwort::findOrFail($antwortId);

        if ($antwort->hatNutzerAbgestimmt(auth()->id())) {
            return redirect()->back()->with('fehler', 'Du hast bereits abgestimmt.');
        }

        UmfrageStimme::create([
            'discussion_answer_id' => $antwort->id,
            'user_id'              => auth()->id(),
            'poll_option_id'       => $request->option_id,
        ]);

        return redirect()->back()->with('erfolg', 'Deine Stimme wurde gezählt!');
    }

    /**
     * Erstellt eine Antwort oder eine Umfrage innerhalb des Threads.
     */
    public function antworten(Request $request, Diskussion $diskussion)
    {
        $request->validate([
            'beitrag'     => 'required|string',
            'ist_umfrage' => 'nullable|boolean',
            'optionen'    => 'required_if:ist_umfrage,1|array|min:2|max:10',
            'optionen.*'  => 'required_if:ist_umfrage,1|string|max:255',
        ]);

        DB::transaction(function () use ($request, $diskussion) {
            $antwort = Diskussionsantwort::create([
                'discussion_id' => $diskussion->id,
                'user_id'       => auth()->id(),
                'content'       => $request->beitrag,
                'ist_umfrage'   => $request->has('ist_umfrage'),
            ]);

            if ($antwort->ist_umfrage) {
                foreach ($request->optionen as $optionText) {
                    if (!empty(trim($optionText))) {
                        UmfrageOption::create([
                            'discussion_answer_id' => $antwort->id,
                            'option_text'          => trim($optionText)
                        ]);
                    }
                }
            }
        });

        return redirect()->route('diskussion.details', $diskussion->id)
            ->with('erfolg', 'Beitrag erfolgreich hinzugefügt.');
    }
}
