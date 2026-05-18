@extends('layouts.app')

@section('title', 'Admin-Bereich')

@section('content')

<h1 class="text-3xl font-bold text-blue-900 mb-4">
    Admin-Bereich
</h1>

<p class="text-gray-600 italic">
    Platzhalter für Admin-Inhalte
</p>

<div class="mt-4">
    <button>Benutzer löschen</button>

    <button>Benutzer sperren</button>
</div>

<div class="mt-6 p-4 bg-gray-50 border border-dashed border-gray-300 rounded-lg">
    <table border="1" cellpadding="10">
    <tr>
        <th>Vorname</th>
        <th>Nachname</th>
        <th>Status</th>
    </tr>

    <tr>
        <td>Max</td>
        <td>Mustermann</td>
        <td>Aktiv</td>
    </tr>

    <tr>
        <td>Anna</td>
        <td>Schmidt</td>
        <td>Gesperrt</td>
    </tr>
</table>
</div>

@endsection