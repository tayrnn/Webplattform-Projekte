<?php

namespace App\Http\Controllers;

use App\Models\Nutzer\Nutzer;
use App\Models\Projekt\Projekt;
use App\Models\Projekt\Kategorie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SuchenFilternController extends Controller
{
    public function suchen(Request $request) 
    {
        //Suchbegriff aus dem Request vom Suchfeld holen
        $suchbegriff = $request->suche;
        //ausgewählte Optionen der Filter aus dem Request holen
        $filterKategorie = $request->input('filterKategorie');
        $filterStatus = $request->input('filterStatus');

        //Datenbankbeziehung zwischen Projekt, Ersteller und Kategorie schon direkt mit laden (Datenbankabfrage muss dann nur zweimal durchgeführt werden)
        $projekte  = Projekt::with('ersteller', 'kategorien')
            //Bestandteil 1: Suchfunktion
            ->when($suchbegriff, function ($query) use ($suchbegriff) { //Suche nur durchführen, wenn ein Suchbegriff eingegeben wurde
                $query->where(function ($subQuery) use ($suchbegriff) { //Klammerung der Suchfunktion, damit die Filterfunktionen später nicht von der Suche beeinflusst werden
                    //Rückgabe von Projekten anhand der Übereinstimmung mit dem Suchbegriff selektieren
                    $subQuery->where('projektname', 'LIKE', '%'.$suchbegriff.'%') 
                             //über die Beziehung von Projekt belongsTo Ersteller (Name: ersteller) auf die Tabelle der Nutzer zugreifen
                             //in der Tabelle der Nutzer mit dem Suchbegriff nach Erstellern suchen (Suchbegriff muss für die Funktion übergeben werden)
                             ->orWhereHas('ersteller', function ($userQuery) use ($suchbegriff) {
                                //Rückgabe von Projekten anhand der Übereinstimmung des Suchbegriffes mit deren Ersteller selektieren
                                $userQuery->where('name', 'LIKE', '%'.$suchbegriff.'%');
                            });
                });
            })
            //Bestandteil 2: Filterfunktion für die Kategorien
            ->when($filterKategorie, function ($query) use ($filterKategorie) {
                $query->whereHas('kategorien', function ($categoryQuery) use ($filterKategorie) {
                    $categoryQuery->where('id', $filterKategorie);
                });
            })
            //Bestandteil 3: Filterfunktion für den Bearbeitungsstatus
            ->when($filterStatus, function ($query) use ($filterStatus) {
                $query->where('bearbeitungsstatus', $filterStatus);
            })
            ->get(); //Datenbankabfrage auch durchführen

        $kategorien = Kategorie::all();
        $istStudent = Auth::check() && Auth::user()->role === 'student';
        $istLehrender = Auth::check() && Auth::user()->role === 'lehrender';
        $istAdmin = Auth::check() && Auth::user()->role === 'admin';

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

        $nutzer = Nutzer::where('name', 'LIKE', '%'.$suchbegriff.'%')->get();

        return view('admin.dashboard', compact(
            'nutzer', 
            'suchbegriff'
        ));
    }

}
