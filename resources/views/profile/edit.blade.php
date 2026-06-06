{{-- profile/edit.blade.php --}}
{{-- Mein Profil Seite - Benutzername & Passwort aendern --}}

@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-6 mt-8 mb-12">

    {{-- Seiten-Kopf --}}
    <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
        <div>
            <h1 class="text-2xl font-bold text-[#1a1a4b]">Mein Profil</h1>
            <p class="text-gray-500 text-sm mt-1">Benutzername und Passwort verwalten</p>
        </div>
        <a href="{{ route('projekte.liste') }}"
           class="font-bold text-lg transition-colors text-gray-400 hover:text-[#0066cc]">
            ← Zurück
        </a>
    </div>

    {{-- Erfolgs- oder Fehlermeldungen --}}
    @if(session('status') === 'profile-updated')
        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
            ✓ Profil erfolgreich aktualisiert!
        </div>
    @endif
    @if(session('status') === 'password-updated')
        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
            ✓ Passwort erfolgreich geändert!
        </div>
    @endif

    {{-- Profil-Avatar oben --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm text-center mb-6">
        <div class="w-20 h-20 bg-[#d2f1ff] rounded-full flex items-center justify-center border-4 border-white shadow-md mx-auto mb-3">
            <span class="text-2xl font-bold text-cyan-800 uppercase">
                {{ substr(auth()->user()->name ?? 'N', 0, 2) }}
            </span>
        </div>
        <h2 class="text-lg font-bold text-[#1a202c]">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-gray-400">{{ auth()->user()->email }}</p>
        <div class="mt-2 inline-block bg-[#eef2fd] text-[#0066cc] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
            {{ auth()->user()->role ?? 'Student' }}
        </div>
    </div>

    {{-- Benutzername aendern --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
        <h3 class="text-base font-bold text-[#1a1a4b] mb-1">Benutzername ändern</h3>
        <p class="text-sm text-gray-500 mb-5">Dein Benutzername ist sichtbar für andere Nutzer.</p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-5">
                <label for="username" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Benutzername <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username', auth()->user()->username) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                    required
                >
                @if($errors->get('username'))
                    <span class="text-red-500 text-xs mt-1 block">{{ implode(', ', $errors->get('username')) }}</span>
                @endif
            </div>

            <button type="submit"
                    class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-6 py-3 rounded-md font-bold transition-colors shadow-sm w-full">
                Benutzername speichern
            </button>
        </form>
    </div>

    {{-- Passwort aendern --}}
    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
        <h3 class="text-base font-bold text-[#1a1a4b] mb-1">Passwort ändern</h3>
        <p class="text-sm text-gray-500 mb-5">Gib zuerst dein aktuelles Passwort ein.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-4">
                <label for="current_password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Aktuelles Passwort <span class="text-red-500">*</span>
                </label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                    placeholder="••••••••"
                    required
                >
                @if($errors->updatePassword->get('current_password'))
                    <span class="text-red-500 text-xs mt-1 block">{{ implode(', ', $errors->updatePassword->get('current_password')) }}</span>
                @endif
            </div>

            <div class="mb-4">
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Neues Passwort <span class="text-red-500">*</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                    placeholder="••••••••"
                    required
                >
                @if($errors->updatePassword->get('password'))
                    <span class="text-red-500 text-xs mt-1 block">{{ implode(', ', $errors->updatePassword->get('password')) }}</span>
                @endif
            </div>

            <div class="mb-5">
                <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Neues Passwort bestätigen <span class="text-red-500">*</span>
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit"
                    class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-6 py-3 rounded-md font-bold transition-colors shadow-sm w-full">
                Passwort ändern
            </button>
        </form>
    </div>

</div>
@endsection