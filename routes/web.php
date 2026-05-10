<?php

use Illuminate\Support\Facades\Route;

// Startseite wurde von Laravel vorgegeben, kann aber angepasst werden. Aktuell zeigt sie die welcome.blade.php an, die sich im resources/views Ordner befindet. Dort könnte man auch eine eigene Startseite erstellen, z.B. mit Informationen über die Plattform, Anleitungen oder Neuigkeiten. Die Route definiert, dass wenn jemand die URL "/dashboard" aufruft, 
// die welcome.blade.php angezeigt wird und somit die Startseite der Anwendung ist                  
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

use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Login anzeigen
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Login verarbeiten
Route::post('/login', [AuthenticatedSessionController::class, 'store']);