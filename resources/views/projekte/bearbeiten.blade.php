{{-- projekte/bearbeiten.blade.php --}}
{{-- Formular fuer Studierende um eigene Projektidee zu bearbeiten --}}

@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">
        <section class="flex-1">

            {{-- Obere Leiste --}}
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
                <a href="{{ route('projekte.details', $projekt->id) }}"
                   class="font-bold text-lg transition-colors text-gray-400 hover:text-[#0066cc]">
                    ← Zurück zum Projekt
                </a>
            </div>

            {{-- Seiten-Titel --}}
            <h1 class="text-2xl font-bold text-[#1a1a4b] mb-1">Projektidee bearbeiten</h1>
            <p class="text-gray-500 text-sm mb-6">Aktualisiere deine Projektidee.</p>

            {{-- Validierungsfehler --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $fehlermeldung)
                            <li>{{ $fehlermeldung }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formular-Karte --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm max-w-2xl">
                <form method="POST" action="{{ route('projekte.aktualisieren', $projekt->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Projekttitel --}}
                    <div class="mb-5">
                        <label for="projektname" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            Projekttitel <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="projektname"
                            name="projektname"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 {{ $errors->has('projektname') ? 'border-red-400' : '' }}"
                            placeholder="Aussagekräftiger Titel der Idee"
                            value="{{ old('projektname', $projekt->projektname) }}"
                            maxlength="255"
                            required
                        >
                        @error('projektname')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Beschreibung --}}
                    <div class="mb-5">
                        <label for="beschreibung" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            Beschreibung <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="beschreibung"
                            name="beschreibung"
                            rows="6"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100 resize-y {{ $errors->has('beschreibung') ? 'border-red-400' : '' }}"
                            placeholder="Was ist die Idee? Welches Problem löst sie? Wer soll es nutzen?"
                            required
                        >{{ old('beschreibung', $projekt->beschreibung) }}</textarea>
                        @error('beschreibung')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kategorie --}}
                    <div class="mb-5">
                        <label for="category_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            Kategorien <span class="text-red-500">*</span>
                        </label>

                        @foreach($kategorien as $kategorie)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="category_id[]" value="{{ $kategorie->id }}"
                                    {{ in_array($kategorie->id, old('category_id', $projekt->kategorien->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-[#0066cc] focus:ring-[#0066cc]"
                                >
                                <span class="text-sm text-gray-700">{{ $kategorie->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- Privat oder Öffentlich --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                            Sichtbarkeit
                        </label>
                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_public" value="1"
                                {{ old('is_public', $projekt->is_public) == 1 ? 'checked' : '' }}
                                class="text-blue-600">
                                <span class="text-sm text-gray-700">🌐 Öffentlich</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_public" value="0"
                                {{ old('is_public', $projekt->is_public) == 0 ? 'checked' : '' }}
                                class="text-blue-600">
                                <span class="text-sm text-gray-700">🔒 Privat</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Privat: nur du kannst es sehen</p>
                    </div>
                    
                    {{-- Buttons --}}
                    <div class="flex gap-3 mt-6">
                        <button type="submit"
                                class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-6 py-3 rounded-md font-bold transition-colors shadow-sm">
                            Änderungen speichern →
                        </button>
                        <a href="{{ route('projekte.details', $projekt->id) }}"
                           class="px-6 py-3 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition font-medium">
                            Abbrechen
                        </a>
                    </div>

                </form>
            </div>

        </section>
    </div>
</div>
@endsection
