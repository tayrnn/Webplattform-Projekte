<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NutzerController extends Controller
{
    public function benutzerAnlegen()
    {
        return view('admin.benutzer-anlegen');
    }

    public function speichern()
    {
        return redirect('/admin/benutzer-anlegen')
            ->with('success', 'Benutzer wurde erstellt!');
    }
}