{{-- projekte/details.blade.php --}}
{{-- Detailansicht einer Projektidee - Prototyp I --}}

@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">

        <section class="flex-1">

            {{-- Obere Leiste --}}
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
                <a href="{{ route('projekte.liste') }}"
                   class="font-bold text-lg transition-colors text-gray-400 hover:text-[#0066cc]">
                    ← Zurück zur Übersicht
                </a>
            </div>

            {{-- Erfolgs- / Fehlermeldungen --}}
            @if(session('erfolg'))
                <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
                    ✓ {{ session('erfolg') }}
                </div>
            @endif
            @if(session('fehler'))
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                    ✕ {{ session('fehler') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- HAUPTBEREICH --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-4">

                    {{-- Status & Kategorie --}}
                    <div class="flex gap-2">
                        <span class="text-xs font-semibold text-[#0066cc] bg-blue-50 px-2 py-1 rounded-full">
                            {{ $projekt->category->name ?? '—' }}
                        </span>
                        @php
                            $statusColor = match($projekt->bearbeitungsstatus) {
                                'angenommen'  => 'bg-gray-400 text-gray-900',
                                'in_pruefung' => 'bg-blue-500 text-white',
                                'neu'         => 'bg-[#8dc63f] text-gray-900',
                                default       => 'bg-gray-200 text-gray-800',
                            };
                            $statusLabel = match($projekt->bearbeitungsstatus) {
                                'neu'         => 'Offen',
                                'in_pruefung' => 'In Bearbeitung',
                                'angenommen'  => 'Abgeschlossen',
                                default       => $projekt->bearbeitungsstatus,
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Projekttitel --}}
                    <h1 class="text-2xl font-bold text-[#1a202c]">{{ $projekt->projektname }}</h1>

                    {{-- Projektbild (falls vorhanden) --}}
                    @if($projekt->bildpfad)
                        <img
                            src="{{ asset('storage/' . $projekt->bildpfad) }}"
                            alt="Bild zu {{ $projekt->projektname }}"
                            class="w-full max-h-72 object-cover rounded-lg border border-gray-200"
                        >
                    @endif

                    {{-- Beschreibung --}}
                    <div class="text-sm text-gray-600 leading-relaxed">
                        {!! nl2br(e($projekt->beschreibung)) !!}
                    </div>

                    <hr class="border-gray-100">

                    {{-- Ersteller & Datum --}}
                    <div class="flex flex-wrap gap-6 text-sm text-gray-400">
                        <span>👤 <strong class="text-gray-600">{{ $projekt->user->name ?? 'Unbekannt' }}</strong></span>
                        <span>📅 Eingereicht am <strong class="text-gray-600">{{ \Carbon\Carbon::parse($projekt->created_at)->format('d.m.Y') }}</strong></span>
                    </div>

                </div>

                {{-- SEITENLEISTE --}}
                <div class="flex flex-col gap-4">

                    {{-- Aktionen fuer eigene Projekte --}}
                    @if($istStudent && $projekt->ersteller_id === auth()->id())
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Meine Idee</div>

                            <a href="{{ route('projekte.bearbeiten', $projekt->id) }}"
                               class="block w-full text-center bg-[#6ba9dc] hover:bg-[#5a91c4] text-white py-2 rounded-md text-sm font-bold transition mb-3">
                                ✏️ Idee bearbeiten
                            </a>

                            <form
                                method="POST"
                                action="{{ route('projekte.loeschen', $projekt->id) }}"
                                onsubmit="return confirm('Idee wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-red-50 text-red-600 border border-red-200 py-2 rounded-md text-sm font-bold hover:bg-red-100 transition">
                                    🗑 Idee löschen
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- Projektdetails --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Projektdetails</div>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-400">Kategorie</span>
                                <span class="font-semibold text-gray-700">{{ $projekt->category->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-400">Status</span>
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded-full {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-400">Eingereicht von</span>
                                <span class="font-semibold text-gray-700">{{ $projekt->user->name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-400">Datum</span>
                                <span class="font-semibold text-gray-700">
                                    {{ \Carbon\Carbon::parse($projekt->created_at)->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section>
    </div>
</div>
@endsection