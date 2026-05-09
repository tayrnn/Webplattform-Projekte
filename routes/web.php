<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjektController;

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