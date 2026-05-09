<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NutzerController;

// Startseite
Route::get('/dashboard', function () {
    return view('dashboard');
});

// --- 3 ROLLEN-DASHBOARDS ---

// Lehrende-Ansicht
Route::get('/lehrende', function () {
    return view('lehrende.dashboard');
});

// Admin-Ansicht
Route::get('/admin', function () {
    return view('admin.dashboard');
});

// Studenten-Ansicht
Route::get('/student', function () {
    return view('student.dashboard');
});

// Benutzer anlegen
Route::get('/admin/benutzer-anlegen', [NutzerController::class, 'benutzerAnlegen']);

Route::post('/admin/benutzer-speichern', [NutzerController::class, 'speichern']);