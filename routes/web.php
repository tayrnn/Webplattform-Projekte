<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NutzerController;
use App\Http\Controllers\ProjektController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

// Startseite
Route::get('/dashboard', function () {
    return view('dashboard');
});

// --- 3 ROLLEN-DASHBOARDS ---

// Lehrende-Ansicht
Route::get('/lehrende', function () {
    $projekte = Project::all();
    return view('lehrende.dashboard', compact('projekte'));
});

// Admin-Ansicht
Route::get('/admin', function () {
    $projekte = Project::all();
    return view('admin.dashboard', compact('projekte'));
});

// Studenten-Ansicht
Route::get('/student', function () {
    $projekte = Project::where('ersteller_id', Auth::id())->get();
    return view('student.dashboard', compact('projekte'));
});

// Startseite zeigt die Projektliste
Route::get('/', [ProjektController::class, 'liste'])->name('projekte.liste');

// Meine Projekte (nur eigene Ideen des eingeloggten Studenten)
Route::get('/projekte/meine', [ProjektController::class, 'meine'])->name('projekte.meine');

// Projekt erstellen
Route::get('/projekte/erstellen', [ProjektController::class, 'erstellen'])->name('projekte.erstellen');
Route::post('/projekte/speichern', [ProjektController::class, 'speichern'])->name('projekte.speichern');

// Projekt Details
Route::get('/projekte/{id}', [ProjektController::class, 'details'])->name('projekte.details');

// Projekt bearbeiten (nur eigene Idee)
Route::get('/projekte/{id}/bearbeiten', [ProjektController::class, 'bearbeiten'])->name('projekte.bearbeiten');
Route::put('/projekte/{id}/aktualisieren', [ProjektController::class, 'aktualisieren'])->name('projekte.aktualisieren');

// Projekt loeschen (nur eigene Idee)
Route::delete('/projekte/{id}/loeschen', [ProjektController::class, 'loeschen'])->name('projekte.loeschen');

// Test-Route (von Taqwa)
Route::get('/test', function () {
    return view('test');
});

// Benutzer anlegen
Route::get('/admin/nutzer/neu', [NutzerController::class, 'benutzerAnlegen']);

Route::post('/admin/nutzer-speichern', [NutzerController::class, 'speichern']);
// --- TAB-ROUTEN FÜR STUDENTEN ---

// Tab 1: Alle Ideen (lädt die normale Übersicht)
Route::get('/student/alle-ideen', function () {
   $projekte = Project::all();
    return view('student.dashboard', compact('projekte'));
});

// Tab 2: Meine Projekte (lädt zum Testen vorerst auch das Dashboard)
Route::get('/student/meine-projekte', function () {
    $projekte = Project::where('ersteller_id', Auth::id())->get();
    return view('student.dashboard', compact('projekte'));
});
Route::get('/student/nutzer/neu', function () {
    // Hier kommt später das Formular hin,
    return "Hier entsteht das Formular zum Projekte erstellen !";
});

// --- TAB-ROUTEN FÜR LEHRER ---
// Tab 1: Alle Ideen (lädt die normale Übersicht)
Route::get('/lehrende/alle-ideen', function () {
    $projekte = Project::all();
    return view('lehrende.dashboard', compact('projekte'));
});
// Tab 2: Betreute Projekte (lädt zum Testen vorerst auch das Dashboard)
Route::get('/lehrende/betreute-projekte', function () {
    $projekte = Project::all();
    return view('lehrende.dashboard', compact('projekte'));
}); 
// 2. Admin Nutzerverwaltung (Übersicht aller Nutzer)
Route::get('/admin/nutzer', function () {
    // Hier kann später eine Tabelle mit allen Nutzern anzeigen lassen
    $projekte = Project::all();
    return view('admin.dashboard', compact('projekte'));
});


// Login anzeigen
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Login verarbeiten
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// abmelden button (einstellung)
Route::post('/abmelden', [AuthenticatedSessionController::class, 'destroy'])->name('abmelden');