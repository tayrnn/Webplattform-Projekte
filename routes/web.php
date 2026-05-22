<?php

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard'); 
});

// --- 3 ROLLEN-DASHBOARDS ---
Route::get('/lehrende', function () { return view('lehrende.dashboard'); });
Route::get('/admin',    function () { return view('admin.dashboard'); });
Route::get('/student',  function () { return view('student.dashboard'); });

// --- PROJEKT-DISKUSSION ---
// Aufruf: /projekt/1  /projekt/2  usw.
Route::get('/projekt/{id}', function ($id) {
    return view('projekt.diskussion', ['projektId' => $id]);
});

// --- STUDENTEN-ROUTEN ---
Route::get('/student/neue-idee',      function () { return view('student.create-idea'); });
Route::get('/student/alle-ideen',     function () { return view('student.dashboard'); });
Route::get('/student/meine-projekte', function () { return view('student.dashboard'); });
Route::get('/student/nutzer/neu',     function () { return "Hier entsteht das Formular zum Projekte erstellen!"; });

// --- LEHRENDEN-ROUTEN ---
Route::get('/lehrende/alle-ideen',        function () { return view('lehrende.dashboard'); });
Route::get('/lehrende/betreute-projekte', function () { return view('lehrende.dashboard'); });

// --- ADMIN-ROUTEN ---
Route::get('/admin/nutzer',     function () { return view('admin.dashboard'); });
Route::get('/admin/nutzer/neu', function () { return "Hier entsteht das Formular zum Nutzer anlegen!"; });