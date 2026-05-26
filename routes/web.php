<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NutzerController;
use App\Http\Controllers\ProjektController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DiskussionController;

// Startseite
Route::get('/dashboard', [DashboardController::class, 'startseite']);

// --- 3 ROLLEN-DASHBOARDS ---

// Lehrende-Ansicht
Route::get('/lehrende', [DashboardController::class, 'lehrendeDashboard']);

// Admin-Ansicht
Route::get('/admin', [DashboardController::class, 'adminDashboard']);

// Studenten-Ansicht
Route::get('/student', [DashboardController::class, 'studentDashboard']);

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
// Route::get('/test', function () {
//     return view('test');
// });

// Benutzer anlegen
Route::get('/admin/nutzer/neu', [NutzerController::class, 'benutzerAnlegen']);

Route::post('/admin/nutzer-speichern', [NutzerController::class, 'speichern']);

// --- TAB-ROUTEN FÜR STUDENTEN ---

// Tab 1: Alle Ideen (lädt die normale Übersicht)
Route::get('/student/alle-ideen', [DashboardController::class, 'studentAlleProjekte']);

// Tab 2: Meine Projekte (lädt zum Testen vorerst auch das Dashboard)
Route::get('/student/meine-projekte', [DashboardController::class, 'studentMeineProjekte']);
Route::get('/student/nutzer/neu', [DashboardController::class, 'studentNeuesProjekt']);

// --- TAB-ROUTEN FÜR LEHRER ---
// Tab 1: Alle Ideen (lädt die normale Übersicht)
Route::get('/lehrende/alle-ideen', [DashboardController::class, 'lehrendeAlleProjekte']);
// Tab 2: Betreute Projekte (lädt zum Testen vorerst auch das Dashboard)
Route::get('/lehrende/betreute-projekte', [DashboardController::class, 'lehrendeBetreuteProjekte']); 


// 2. Admin Nutzerverwaltung (Übersicht aller Nutzer)
Route::get('/admin/nutzer', [DashboardController::class,'adminNutzerverwaltung']);


// Login anzeigen
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Login verarbeiten
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Diskussionen
Route::get('/projekte/{projekt}/diskussion/create', [DiskussionController::class, 'create'])
    ->name('diskussion.create');
Route::post('/projekte/{projekt}/diskussion/speichern', [DiskussionController::class, 'speichern'])
    ->name('diskussion.speichern');
Route::get('/diskussion/{diskussion}', [DiskussionController::class, 'details'])
    ->name('diskussion.details');
Route::post('/diskussion/{diskussion}/antworten', [DiskussionController::class, 'antworten'])
    ->name('diskussion.antworten');
Route::post('/diskussions-antwort/{antwort}/abstimmen', [DiskussionController::class, 'abstimmen'])
    ->name('diskussion.abstimmen');