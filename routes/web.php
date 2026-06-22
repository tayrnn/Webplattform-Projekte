<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NutzerController;
use App\Http\Controllers\projektController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Projekt\Projekt;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BetreuungController;
use App\Models\Projekt\Kategorie;
use App\Http\Controllers\SuchenFilternController;
use App\Http\Controllers\DiskussionenController;
use App\Http\Controllers\UmfrageController;
use App\Http\Controllers\BewertungsController;

// --- AUTH ---
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/abmelden', [AuthenticatedSessionController::class, 'destroy'])->name('abmelden');

// --- PROFIL ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
});

// --- STARTSEITE ---
//Route::get('/', [ProjektController::class, 'liste'])->name('projekte.liste');
Route::get('/', [SuchenFilternController::class, 'suchen'])->name('projekte.liste');
Route::get('/dashboard', function () {
    return view('dashboard');
});

// --- STUDENT-ROUTEN ---
Route::get('/student', function () {
    if (Auth::user()->role === 'lehrender') return redirect('/lehrende');
    if (Auth::user()->role === 'admin') return redirect('/admin');

    $projekte = Projekt::all();
    $istStudent = true;
    $istLehrender = false;
    $istAdmin = false;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
Route::get('/student/alle-ideen', [SuchenFilternController::class, 'suchen'])->name('student.alle-projekte.suchen');
Route::get('/student/meine-projekte', [SuchenFilternController::class, 'suchen'])->name('student.meine-projekte.suchen');
Route::get('/student/neue-idee', function () {
    $kategorien = Kategorie::all();
    return view('projekte.erstellen', compact('kategorien'));
});

// --- LEHRENDE-ROUTEN ---
Route::get('/lehrende', function () {
    $projekte = Projekt::all();
    $istStudent = false;
    $istLehrender = true;
    $istAdmin = false;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
Route::get('/lehrende/alle-ideen', [SuchenFilternController::class, 'suchen'])->name('lehrende.alle-projekte.suchen');
Route::get('/lehrende/betreute-projekte', [SuchenFilternController::class, 'suchen'])->name('lehrende.betreute-projekte.suchen');

// --- ADMIN-ROUTEN ---
Route::get('/admin', [SuchenFilternController::class, 'suchen'])->name('admin.projekte.suchen');
Route::get('/admin/nutzer', function () {
    $projekte = collect();
    $nutzer = \App\Models\User::all();
    $istStudent = false;
    $istLehrender = false;
    $istAdmin = true;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'nutzer', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
Route::get('/admin/nutzer/neu', [NutzerController::class, 'benutzerAnlegen']);
Route::post('/admin/nutzer-speichern', [NutzerController::class, 'speichern']);
Route::delete('/admin/nutzer/{id}/loeschen', [NutzerController::class, 'loeschen'])->name('admin.nutzer.loeschen');
Route::get('/admin/meine-projekte', [SuchenFilternController::class, 'suchen'])->name('admin.meine-projekte.suchen');

// --- PROJEKTE ---
Route::get('/projekte/meine', [ProjektController::class, 'meine'])->name('projekte.meine');
Route::get('/projekte/erstellen', [ProjektController::class, 'erstellen'])->name('projekte.erstellen');
Route::post('/projekte/speichern', [ProjektController::class, 'speichern'])->name('projekte.speichern');
Route::get('/projekte/{id}', [ProjektController::class, 'details'])->name('projekte.details');
Route::get('/projekte/{id}/bearbeiten', [ProjektController::class, 'bearbeiten'])->name('projekte.bearbeiten');
Route::put('/projekte/{id}/aktualisieren', [ProjektController::class, 'aktualisieren'])->name('projekte.aktualisieren');
Route::delete('/projekte/{id}/loeschen', [ProjektController::class, 'loeschen'])->name('projekte.loeschen');
Route::patch('/projekte/{id}/status', [projektController::class, 'statusAendern'])->name('projekte.status');

// --- BEWERTUNG (Sterne) ---
Route::post('/projekte/{id}/bewerten', [ProjektController::class, 'bewerten'])->name('projekte.bewerten');

// --- NEUE PROJEKT-DETAILANSICHT MIT DISKUSSION, UMFRAGE & STERNEN ---

// 1. Das kombinierte Frontend-Layout (Hauptseite)
Route::get('/projekt/{id}/diskussionen', [DiskussionenController::class, 'anzeigen'])->name('projekt.diskussionen');

// 2. Diskussionen & Kommentare
Route::post('/projekte/{projekt}/diskussion/speichern', [DiskussionenController::class, 'diskussionSpeichern'])->name('diskussion.speichern');
Route::post('/projekt/{diskussion}/beitrag', [DiskussionenController::class, 'beitragSpeichern'])->name('beitrag.speichern');
Route::post('/beitrag/{beitrag}/antwort', [DiskussionenController::class, 'antwortSpeichern'])->name('antwort.speichern');
Route::delete('/beitrag/{id}/loeschen', [DiskussionenController::class, 'beitragLoeschen'])->name('beitrag.loeschen');
Route::delete('/projekt/{projektId}/diskussion/loeschen', [DiskussionenController::class, 'diskussionLoeschen'])->name('diskussion.loeschen');

// 3. Umfragen
Route::post('/projekt/{projektId}/abstimmen', [UmfrageController::class, 'abstimmen'])->name('umfrage.abstimmen');
Route::delete('/projekt/{projektId}/stimme-zurueckziehen', [UmfrageController::class, 'stimmeLoeschen'])->name('umfrage.loeschen');

// 4. Sternebewertungen
Route::post('/projekt/{projektId}/bewerten', [BewertungsController::class, 'bewerten'])->name('bewertung.speichern');
Route::delete('/projekt/{projektId}/bewertung-entfernen', [BewertungsController::class, 'bewertungLoeschen'])->name('bewertung.loeschen');

// --- SUCHE --- 
//-> alle bis auf /admin/nutzer/suchen eigentlich bei den anderen jetzt mit untergebracht, da sonst keine direkte Hervorhebung des aktuellen Tabs möglich war 
//   bzw. die Hervorhebung beim Suchen/Filtern immer verloren gegangen ist (ansonsten hat es jetzt so ohne Probleme an den anderen Stellen mit funktioniert)
Route::get('/student/alle-ideen/suchen', [SuchenFilternController::class, 'suchen'])->name('student.alle-projekte.suchen');
Route::get('/student/meine-projekte/suchen', [SuchenFilternController::class, 'suchen'])->name('student.meine-projekte.suchen');
Route::get('/lehrende/alle-ideen/suchen', [SuchenFilternController::class, 'suchen'])->name('lehrende.alle-projekte.suchen');
Route::get('/lehrende/betreute-projekte/suchen', [SuchenFilternController::class, 'suchen'])->name('lehrende.betreute-projekte.suchen');
Route::get('/admin/projekte/suchen', [SuchenFilternController::class, 'suchen'])->name('admin.projekte.suchen');
Route::get('/admin/nutzer/suchen', [SuchenFilternController::class, 'nachNutzernSuchen'])->name('admin.nutzer.suchen');

// Test-Route
Route::get('/test', function () {
    return view('test');
});