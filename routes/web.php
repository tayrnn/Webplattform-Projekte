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

// --- PROJEKTE ---
Route::get('/projekte/meine', [ProjektController::class, 'meine'])->name('projekte.meine');
Route::get('/projekte/erstellen', [ProjektController::class, 'erstellen'])->name('projekte.erstellen');
Route::post('/projekte/speichern', [ProjektController::class, 'speichern'])->name('projekte.speichern');
Route::get('/projekte/{id}', [ProjektController::class, 'details'])->name('projekte.details');
Route::get('/projekte/{id}/bearbeiten', [ProjektController::class, 'bearbeiten'])->name('projekte.bearbeiten');
Route::put('/projekte/{id}/aktualisieren', [ProjektController::class, 'aktualisieren'])->name('projekte.aktualisieren');
Route::delete('/projekte/{id}/loeschen', [ProjektController::class, 'loeschen'])->name('projekte.loeschen');

// --- BETREUUNG ---
Route::post('/projekte/{id}/betreuung-uebernehmen', [BetreuungController::class, 'uebernehmen'])->name('betreuung.uebernehmen');
Route::post('/projekte/{id}/betreuung-beenden', [BetreuungController::class, 'beenden'])->name('betreuung.beenden');

// --- DISKUSSIONEN ---
Route::get('/projekte/{projekt}/diskussion/create', [DiskussionController::class, 'create'])->name('diskussion.create');
Route::post('/projekte/{projekt}/diskussion/speichern', [DiskussionController::class, 'speichern'])->name('diskussion.speichern');
Route::get('/diskussion/{diskussion}', [DiskussionController::class, 'details'])->name('diskussion.details');
Route::post('/diskussion/{diskussion}/antworten', [DiskussionController::class, 'antworten'])->name('diskussion.antworten');
Route::post('/diskussions-antwort/{antwort}/abstimmen', [DiskussionController::class, 'abstimmen'])->name('diskussion.abstimmen');

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
