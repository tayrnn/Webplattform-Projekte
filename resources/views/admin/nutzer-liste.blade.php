@extends('layouts.app')

@section('content')

<div style="padding:40px">

    <h2>Benutzerverwaltung</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>E-Mail</th>
            <th>Rolle</th>
            <th>Aktion</th>
        </tr>

        @foreach($nutzer as $user)

        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
    <form action="{{ route('admin.nutzer.loeschen', $user->id) }}"
          method="POST"
          onsubmit="return confirm('Benutzer wirklich löschen?');">

        @csrf
        @method('DELETE')

        <button type="submit">
            Löschen
        </button>

    </form>
</td>
        </tr>
        @endforeach

    </table>

</div>

@endsection