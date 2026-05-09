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
// Formular: Neue Idee erstellen (Nur für Studenten)
Route::get('/student/neue-idee', function () {
    return view('student.create-idea');
});
// --- TAB-ROUTEN FÜR STUDENTEN ---

// Tab 1: Alle Ideen (lädt die normale Übersicht)
Route::get('/student/alle-ideen', function () {
    return view('student.dashboard');
});

// Tab 2: Meine Projekte (lädt zum Testen vorerst auch das Dashboard)
Route::get('/student/meine-projekte', function () {
    return view('student.dashboard');
});
Route::get('/student/nutzer/neu', function () {
    // Hier kommt später das Formular hin,
    return "Hier entsteht das Formular zum Projekte erstellen !";
});

// --- TAB-ROUTEN FÜR LEHRER ---
// Tab 1: Alle Ideen (lädt die normale Übersicht)
Route::get('/lehrende/alle-ideen', function () {
    return view('lehrende.dashboard');
});
// Tab 2: Betreute Projekte (lädt zum Testen vorerst auch das Dashboard)
Route::get('/lehrende/betreute-projekte', function () {
    return view('lehrende.dashboard');
}); 
// 2. Admin Nutzerverwaltung (Übersicht aller Nutzer)
Route::get('/admin/nutzer', function () {
    // Hier kannst du später eine Tabelle mit allen Nutzern anzeigen lassen
    return view('admin.dashboard'); 
});

// 3. Admin: Nutzer anlegen (Das Formular)
Route::get('/admin/nutzer/neu', function () {
    // Hier kommt später das Formular hin, ähnlich wie bei "Neue Idee"
    return "Hier entsteht das Formular zum Nutzer anlegen!";
});
