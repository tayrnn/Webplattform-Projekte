@extends('layouts.app') 

@section('content')
    <h1 class="text-3xl font-bold text-blue-900 mb-4">Willkommen!</h1>
    <p class="text-gray-600 leading-relaxed">
        Das hier ist deine erste Test-Seite. Wenn du das im Browser siehst, bedeutet das:
    </p>
    <ul class="list-disc ml-6 mt-4 space-y-2 text-green-600">
        <li>Laravel ist korrekt installiert.</li>
        <li>Das Master-Layout funktioniert.</li>
        <li>Das Design (Tailwind) wird geladen.</li>
    </ul>
    
    <div class="mt-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700">
        <strong>Tipp:</strong> jetzt kann man heir was schreiben in @section('content')!
    </div>
@endsection