@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-6 mb-16">

    {{-- Zurück-Button --}}
    <a href="javascript:history.back()"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-[#0066cc] mb-4 transition-colors font-medium">  
        ← Zurück
    </a>

    {{-- Erfolgsmeldung --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Formular-Container --}}
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        
        <h2 class="text-xl font-semibold text-gray-800 mb-8 pb-3 border-b border-gray-100">
            Benutzerverwaltung / <span class="text-[#0066cc]">Neuen Benutzer anlegen</span>
        </h2>

        <form method="POST" action="/admin/nutzer-speichern" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vorname</label>
                <input type="text" name="vorname" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nachname</label>
                <input type="text" name="nachname" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail-Adresse</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rolle</label>
                <select name="role" required
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0066cc] focus:border-[#0066cc] outline-none transition bg-white">
                    <option value="student">Student</option>
                    <option value="lehrender">Lehrender</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="w-full bg-[#7ca3bf] hover:bg-[#6b92ae] text-white font-bold py-3 px-4 rounded-lg transition shadow-sm">
                    Benutzer erstellen
                </button>
            </div>

        </form>
    </div>
</div>
@endsection