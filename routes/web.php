<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NutzerController;
use App\Http\Controllers\ProjektController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Projekt\Projekt;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BetreuungController;
use App\Models\Projekt\Kategorie;
use App\Http\Controllers\DiskussionController;
use App\Http\Controllers\SuchenFilternController;

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
Route::get('/', [ProjektController::class, 'liste'])->name('projekte.liste');
Route::get('/dashboard', function () {
    return view('dashboard');
});

// --- STUDENT-ROUTEN ---
Route::get('/student', function () {
    $projekte = Projekt::all();
    $istStudent = true;
    $istLehrender = false;
    $istAdmin = false;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
Route::get('/student/alle-ideen', function () {
    $projekte = Projekt::all();
    $istStudent = true;
    $istLehrender = false;
    $istAdmin = false;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
Route::get('/student/meine-projekte', function () {
    $projekte = Projekt::where('ersteller_id', Auth::id())->get();
    $istStudent = true;
    $istLehrender = false;
    $istAdmin = false;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
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
Route::get('/lehrende/alle-ideen', function () {
    $projekte = Projekt::all();
    $istStudent = false;
    $istLehrender = true;
    $istAdmin = false;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});
Route::get('/lehrende/betreute-projekte', [BetreuungController::class, 'index']);

// --- ADMIN-ROUTEN ---
Route::get('/admin', function () {
    $projekte = Projekt::all();
    $istStudent = false;
    $istLehrender = false;
    $istAdmin = true;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});

Route::get('/admin/meine-projekte', function () {
    $projekte = Projekt::where('ersteller_id', Auth::id())->get();
    $istStudent = false;
    $istLehrender = false;
    $istAdmin = true;
    $filterKategorie = null;
    $filterStatus = null;
    $kategorien = Kategorie::all();
    return view('projekte.liste', compact('projekte', 'istStudent', 'istLehrender', 'istAdmin', 'filterKategorie', 'filterStatus', 'kategorien'));
});

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

// --- PROJEKTE ---
Route::get('/projekte/meine', [ProjektController::class, 'meine'])->name('projekte.meine');
Route::get('/projekte/erstellen', [ProjektController::class, 'erstellen'])->name('projekte.erstellen');
Route::post('/projekte/speichern', [ProjektController::class, 'speichern'])->name('projekte.speichern');
Route::get('/projekte/{id}', [ProjektController::class, 'details'])->name('projekte.details');
Route::get('/projekte/{id}/bearbeiten', [ProjektController::class, 'bearbeiten'])->name('projekte.bearbeiten');
Route::put('/projekte/{id}/aktualisieren', [ProjektController::class, 'aktualisieren'])->name('projekte.aktualisieren');
Route::delete('/projekte/{id}/loeschen', [ProjektController::class, 'loeschen'])->name('projekte.loeschen');

// --- BEWERTUNG (Sterne) ---
Route::post('/projekte/{id}/bewerten', [ProjektController::class, 'bewerten'])->name('projekte.bewerten');

// --- DISKUSSIONEN (VOLLSTÄNDIG) ---
// Neues Thema speichern
Route::post('/projekte/{projekt}/diskussion/speichern', [DiskussionController::class, 'speichern'])->name('diskussion.speichern');

// Diskussion-Detailseite anzeigen
Route::get('/diskussion/{diskussion}', [DiskussionController::class, 'details'])->name('diskussion.details');

// Diskussionsthema bearbeiten/löschen
Route::put('/diskussion/{diskussion}/bearbeiten', [DiskussionController::class, 'bearbeiten'])->name('diskussion.bearbeiten');
Route::delete('/diskussion/{diskussion}/loeschen', [DiskussionController::class, 'loeschen'])->name('diskussion.loeschen');

// Antworten auf ein Diskussionsthema (Hauptbeiträge)
Route::post('/diskussion/{diskussion}/antworten', [DiskussionController::class, 'antworten'])->name('diskussion.antworten');

// Beiträge (Antworten) bearbeiten/löschen
Route::put('/diskussion-beitrag/{beitrag}/bearbeiten', [DiskussionController::class, 'beitragBearbeiten'])->name('diskussion.beitrag.bearbeiten');
Route::delete('/diskussion-beitrag/{beitrag}/loeschen', [DiskussionController::class, 'beitragLoeschen'])->name('diskussion.beitrag.loeschen');

// Unterantworten (Antworten auf einen bestimmten Beitrag)
Route::post('/diskussion-beitrag/{beitrag}/unterantwort', [DiskussionController::class, 'unterantwortSpeichern'])->name('diskussion.unterantwort.speichern');

// --- SUCHE ---
Route::get('/student/projekte/suchen', [SuchenFilternController::class, 'suchen'])->name('student.projekte.suchen');
Route::get('/lehrende/projekte/suchen', [SuchenFilternController::class, 'suchen'])->name('lehrende.projekte.suchen');
Route::get('/admin/projektideen/suchen', [SuchenFilternController::class, 'suchen'])->name('admin.projekte.suchen');
Route::get('/admin/nutzer/suchen', [SuchenFilternController::class, 'nachNutzernSuchen'])->name('admin.nutzer.suchen');

// Test-Route
Route::get('/test', function () {
    return view('test');
});
