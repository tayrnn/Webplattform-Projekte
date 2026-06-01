@extends('layouts.app')
@section('content')

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Benutzer anlegen</title>

<style>
    body {
        font-family: Arial;
        background: #f5f5f5;
        padding: 40px;
    }

    .container {
        width: 100%;
        margin: 40x auto;
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    }

    input,
    select {
        width: 100%;
        padding: 12px;
        margin-top: 8px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
    }

    button {
        padding: 12px 20px;
        background: #6ba9dc;
        color: white;
        border: none;
        border-radius: 6px;
        margin-top: 20px;
    }

    h2 {
        margin-bottom: 30px;
    }
</style>

@if(session('success'))
<div style="
        background: #d4edda;
        color: #155724;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    ">
    {{ session('success') }}
</div>
@endif
<div class="container">

    <h2>Benutzerverwaltung / Neuen Benutzer anlegen</h2>

    <form method="POST" action="/admin/nutzer-speichern">

        @csrf

        <div>
            <label>Vorname</label>
            <input type="text" name="vorname">
        </div>

        <br>

        <div>
            <label>Nachname</label>
            <input type="text" name="nachname">
        </div>

        <br>

        <div>
            <label>E-Mail-Adresse</label>
            <input type="email" name="email">
        </div>

        <br>

        <div>
            <label>Rolle</label>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="lehrender">Lehrender</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit">
            Benutzer erstellen
        </button>

    </form>

</div>

@endsection