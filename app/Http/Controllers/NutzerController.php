<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class NutzerController extends Controller
{
    public function benutzerAnlegen()
    {
        return view('admin.benutzer-anlegen');
    }

    public function speichern(Request $request)
    {
        $validated = $request->validate([
            'vorname' => ['required', 'string', 'max:255'],
            'nachname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', 'in:student,lehrender,admin'],
        ]);

        $temporaryPassword = Str::random(10);

        User::create([
            'name' => $validated['vorname'] . ' ' . $validated['nachname'],
            'username' => Str::slug($validated['vorname'] . '.' . $validated['nachname']) . rand(100, 999),
            'email' => $validated['email'],
            'password' => Hash::make($temporaryPassword),
            'role' => $validated['role'],
        ]);

        return redirect('/admin/nutzer/neu')
            ->with('success', 'Benutzer wurde erstellt. Temporäres Passwort: ' . $temporaryPassword);
    }

    public function loeschen($id)
    {
        $nutzer = User::findOrFail($id);

        // Admin kann sich nicht selbst löschen
        if ($nutzer->id === auth()->id()) {
            return redirect('/admin/nutzer')
                ->with('fehler', 'Du kannst dich nicht selbst löschen.');
        }

        $nutzer->delete();

        return redirect('/admin/nutzer')
            ->with('erfolg', 'Nutzer wurde gelöscht.');
    }
}
