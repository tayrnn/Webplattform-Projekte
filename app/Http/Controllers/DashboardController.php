<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Projekt\Projekt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function startseite()
    {
        return view('welcome');
    }


    // --- DASHBOARDS für Lehrende ---

    public function lehrendeDashboard()
    {
        $projekte = Projekt::all();
        return view('lehrende.dashboard', compact('projekte'));
    }

    
    // lädt zum Testen vorerst auch das Dashboard, soll aber später die betreuten Projekte anzeigen 
    public function lehrendeAlleProjekte()
    {
        $projekte = Projekt::all();
        return view('lehrende.dashboard', compact('projekte'));
    }

    public function lehrendeBetreuteProjekte()
    {
        $projekte = Projekt::all();
        return view('lehrende.dashboard', compact('projekte'));
    }


    // --- DASHBOARDS für Studenten ---

    public function studentDashboard()
    {
        $projekte = Projekt::all();
        return view('student.dashboard', compact('projekte'));
    }

    // dopplte Aufruf des gleichen Views (s. studentDashboard()) -> soll das wirklich doppelt sein, nur mit anderer Route?
    public function studentAlleProjekte()
    {
        $projekte = Projekt::all();
        return view('student.dashboard', compact('projekte'));
    }

    public function studentMeineProjekte() 
    {
        $projekte= Projekt::where('ersteller_id', Auth::id())->get();
        return view('student.dashboard', compact('projekte'));
    }

    public function studentNeuesProjekt()
    {
        // Hier kommt später das Formular zum Erstellen von Projekten hin.
        return "Hier entsteht das Formular zum Erstellen von Projekten!";
    }


    // --- DASHBOARDS für Admin ---

    public function adminDashboard()
    {
        $projekte = Projekt::all();
        return view('admin.dashboard', compact('projekte'));
    }

    public function adminNutzerverwaltung()
    {
        // hier kommt später eine Tabelle mit allen Nutzern hin -> anzeigen lassen
        $projekte = Projekt::all();
        return view('admin.dashboard', compact('projekte'));
    }

}
