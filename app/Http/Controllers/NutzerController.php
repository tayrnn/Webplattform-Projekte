<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class NutzerController extends Controller
{
    public function benutzerAnlegen()
    {
        return view('admin.benutzer-anlegen');
    }

public function speichern(Request $request)
{
    $user = User::create([
        'name' => $request->vorname . ' ' . $request->nachname,
        'email' => $request->email,
        'password' => Hash::make(Str::random(32)),
    ]);

    Password::sendResetLink([
        'email' => $user->email,
    ]);

    return redirect('/admin/benutzer-anlegen')
        ->with('success', 'Benutzer wurde erstellt. E-Mail wurde versendet.');
}
}