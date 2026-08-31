<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Projekt\Projekt;
use App\Models\Projekt\Kategorie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SuchenFilternController extends Controller
{
    public function suchen(Request $request)
    {

        session(['letzte_projektliste' => $request->fullUrl()]);

        $kategorien = Kategorie::all();
        $istStudent = Auth::check() && Auth::user()->role === 'student';
        $istLehrender = Auth::check() && Auth::user()->role === 'lehrender';
        $istAdmin = Auth::check() && Auth::user()->role === 'admin';

        $pfad = $request->path(); //aktuellen Pfad anhand der URL ermitteln

        //Suchbegriff aus dem Request vom Suchfeld holen
        $suchbegriff = $request->input('suche'); //Name vom Suchefeld in der Blade-Datei ist "suche"
        //ausgewählte Optionen der Filter aus dem Request holen
        $filterKategorie = is_array($request->input('filterKategorie')) ? array_filter($request->input('filterKategorie')) : []; //Standardwert ist ein leeres Array, da mehrere Kategorien ausgewählt werden können
        $filterStatus = is_array($request->input('filterStatus')) ? array_filter($request->input('filterStatus')) : []; //Standardwert ist ein leeres Array, da mehrere Status ausgewählt werden können

        //Basis-Abfrage als Standardfall aufbauen
        $dataPool = Projekt::with('ersteller', 'kategorien'); //Datenbankbeziehung zwischen Projekt, Ersteller und Kategorie schon direkt mit laden (Datenbankabfrage muss dann nur zweimal durchgeführt werden)

        //Unterscheidung des Datenpools je nach Rolle und ausgewähltem Tab

        //Fall 1: Student
        if ($istStudent) {
            if (str_contains($pfad, 'meine-projekte')) {
                $dataPool->where('projects.ersteller_id', Auth::user()->id);
            } else {
                $dataPool->where('projects.is_public', 1);
            }
        }

        //Fall 2: Lehrender
        if ($istLehrender) {
            if (str_contains($pfad, 'betreute-projekte')) {
                $dataPool->where('projects.betreuer_id', Auth::user()->id);
            } else {
                $dataPool->where('projects.is_public', 1);
            }
        }

        //Fall 3: Admin
        if ($istAdmin) {
            if (str_contains($pfad, 'meine-projekte')) {
                $dataPool->where('projects.ersteller_id', Auth::user()->id);
            } else {
                $dataPool->where('projects.is_public', 1);
            }
        }

        $projekte = $dataPool
            //Bestandteil 1: Suchfunktion
            ->when($suchbegriff, function ($query) use ($suchbegriff) { //Suche nur durchführen, wenn ein Suchbegriff eingegeben wurde
                $query->where(function ($subQuery) use ($suchbegriff) { //Klammerung der Suchfunktion, damit die Filterfunktionen später nicht von der Suche beeinflusst werden
                    //Rückgabe von Projekten anhand der Übereinstimmung mit dem Suchbegriff selektieren
                    $subQuery->where('projektname', 'LIKE', '%' . $suchbegriff . '%')
                        //über die Beziehung von Projekt belongsTo Ersteller (Name: ersteller) auf die Tabelle der Nutzer zugreifen
                        //in der Tabelle der Nutzer mit dem Suchbegriff nach Erstellern suchen (Suchbegriff muss für die Funktion übergeben werden)
                        ->orWhereHas('ersteller', function ($userQuery) use ($suchbegriff) {
                            //Rückgabe von Projekten anhand der Übereinstimmung des Suchbegriffes mit deren Ersteller selektieren
                            $userQuery->where('users.name', 'LIKE', '%' . $suchbegriff . '%');
                        });
                });
            })
            //Bestandteil 2: Filterfunktion für die Kategorien
            ->when(!empty($filterKategorie), function ($query) use ($filterKategorie) {
                $query->whereHas('kategorien', function ($categoryQuery) use ($filterKategorie) {
                    $categoryQuery->whereIn('categories.id', $filterKategorie); //Rückgabe von Projekten anhand der Übereinstimmung mit den ausgewählten Kategorien selektieren
                });
            })
            //Bestandteil 3: Filterfunktion für den Bearbeitungsstatus
            ->when(!empty($filterStatus), function ($query) use ($filterStatus) {
                $query->whereIn('bearbeitungsstatus', array_values($filterStatus));
            })
            ->orderBy('projects.created_at', 'desc')
            ->get(); //Datenbankabfrage auch durchführen

        return view('projekte.liste', compact(
            'projekte',
            'kategorien',
            'filterKategorie',
            'filterStatus',
            'istStudent',
            'istLehrender',
            'istAdmin',
            'suchbegriff'
        ));
    }

    public function nachNutzernSuchen(Request $request)
    {
        $suchbegriff = $request->suche;

        $nutzer = User::where('name', 'LIKE', '%' . $suchbegriff . '%')->get();

        $projekte = collect(); //leere Collection, da hier nur die Nutzer zurückgegeben werden sollen
        $filterKategorie = null; //keine Filteroptionen für die Nutzerverwaltung
        $filterStatus = null;
        $istStudent = Auth::check() && Auth::user()->role === 'student';
        $istLehrender = Auth::check() && Auth::user()->role === 'lehrender';
        $istAdmin = Auth::check() && Auth::user()->role === 'admin';
        $kategorien = Kategorie::all();

        return view('projekte.liste', compact(
            'projekte',
            'kategorien',
            'nutzer',
            'filterKategorie',
            'filterStatus',
            'istStudent',
            'istLehrender',
            'istAdmin',
            'suchbegriff'
        ));
    }
}