@extends('layouts.app')
@section('content')
{{-- Container für die Passwort-vergessen-Seite --}}
{{-- Weiße Karte für das Formular --}}
<div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">

    {{-- Überschrift --}}
    <h1 class="text-2xl font-bold text-[#1a202c] mb-1">
        Passwort vergessen?
    </h1>

    {{-- Kurze Erklärung für den Nutzer --}}
    <p class="text-gray-500 text-sm mb-6">
        Gib deine E-Mail-Adresse ein. Wir senden dir einen Link
        zum Zurücksetzen deines Passworts.
    </p>


    {{-- Erfolgsnachricht
         Wird angezeigt, wenn der Reset-Link erfolgreich versendet wurde --}}
    @if (session('status'))
    <div class="bg-green-50 border border-green-300 text-green-700
                    px-4 py-3 rounded-lg mb-6 text-sm">
        {{ session('status') }}
    </div>
    @endif


    {{-- Fehlermeldungen
         Zum Beispiel bei einer ungültigen oder nicht vorhandenen E-Mail-Adresse --}}
    @if ($errors->any())
    <div class="bg-red-50 border border-red-300 text-red-700
                    px-4 py-3 rounded-lg mb-6 text-sm">

        <ul class="list-disc list-inside space-y-1">

            {{-- Alle vorhandenen Fehlermeldungen ausgeben --}}
            @foreach ($errors->all() as $fehlermeldung)
            <li>{{ $fehlermeldung }}</li>
            @endforeach

        </ul>
    </div>
    @endif


    {{-- Formular zum Anfordern des Passwort-Reset-Links
         Die Daten werden an die Laravel-Route "password.email" gesendet --}}
    <form method="POST" action="{{ route('password.email') }}">

        {{-- CSRF-Schutz von Laravel --}}
        @csrf


        {{-- Eingabefeld für die E-Mail-Adresse --}}
        <div class="mb-6">

            <label for="email" class="block text-xs font-bold text-gray-500
                       uppercase tracking-wider mb-2">
                E-Mail
            </label>

            <input type="email" id="email" name="email" {{-- Behält die eingegebene E-Mail bei einem Fehler bei --}}
                value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg
                       px-4 py-3 text-sm text-gray-800
                       focus:outline-none focus:border-[#0066cc]
                       focus:ring-2 focus:ring-blue-100" required autofocus autocomplete="email">
        </div>


        {{-- Button zum Absenden der E-Mail-Adresse
             Danach versucht Laravel den Reset-Link per E-Mail zu senden --}}
        <button type="submit" class="w-full bg-[#6ba9dc] hover:bg-[#5a91c4]
                   text-white px-6 py-3 rounded-md font-bold
                   transition-colors shadow-sm">

            Reset-Link senden →
        </button>


        {{-- Link zurück zur Login-Seite --}}
        <div class="text-center mt-5">

            <a href="{{ route('login') }}" class="text-sm text-[#6ba9dc] hover:underline">

                ← Zurück zum Login
            </a>

        </div>

    </form>

</div>
@endsection