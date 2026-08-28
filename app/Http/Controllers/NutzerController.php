<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

        $user = User::create([
            'name' => $validated['vorname'] . ' ' . $validated['nachname'],
            'username' => Str::slug($validated['vorname'] . '.' . $validated['nachname']) . rand(100, 999),
            'email' => $validated['email'],
            'password' => Hash::make(Str::random(32)),
            'role' => $validated['role'],
        ]);

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            return redirect('/admin/nutzer/neu')
                ->with('fehler', 'Benutzer wurde erstellt, aber die E-Mail konnte nicht versendet werden.');
        }
        return redirect('/admin/nutzer/neu')
            ->with('success', 'Benutzer wurde erstellt. Eine E-Mail zum Festlegen des Passworts wurde versendet.');
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
