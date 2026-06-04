{{-- projekte/liste.blade.php --}}
{{-- Zeigt alle Projektideen mit Taqwas Design und echten Datenbankdaten --}}

@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">

        <section class="flex-1">

            {{-- Obere Leiste: Tabs links, Button rechts --}}
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">

                {{-- Tabs --}}
                <div class="flex gap-8">
                    <a href="{{ route('projekte.liste') }}"
                        class="font-bold text-lg transition-colors {{ request()->routeIs('projekte.liste') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
                        Alle Ideen
                    </a>
                    <a href="{{ route('projekte.meine') }}"
                        class="font-bold text-lg transition-colors {{ request()->routeIs('projekte.meine') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
                        Meine Projekte
                    </a>
                </div>

                {{-- Neue Idee Button (nur fuer Studierende) --}}
                @if($istStudent)
                <a href="{{ route('projekte.erstellen') }}"
                    class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-5 py-2 rounded-md font-bold transition-colors flex items-center gap-2 shadow-sm mb-1">
                    <span>+</span> Neue Idee
                </a>
                @endif
            </div>

            {{-- Erfolgs- oder Fehlermeldungen --}}
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

            {{-- Filter --}}
            <form method="GET" action="{{ route('projekte.liste') }}" class="flex flex-wrap gap-2 mb-6">
                <select name="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white" onchange="this.form.submit()">
                    <option value="" {{ !$filterStatus ? 'selected' : '' }}>Alle Status</option>
                    <option value="neu" {{ $filterStatus === 'neu'         ? 'selected' : '' }}>Offen</option>
                    <option value="in_pruefung" {{ $filterStatus === 'in_pruefung' ? 'selected' : '' }}>In Bearbeitung</option>
                    <option value="angenommen" {{ $filterStatus === 'angenommen'  ? 'selected' : '' }}>Abgeschlossen</option>
                </select>
                <select name="filterKategorie" class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600 bg-white" onchange="this.form.submit()">
                    <option value="" {{ !$filterKategorie ? 'selected' : '' }}>Alle Kategorien</option>
                    @foreach($kategorien as $kategorie)
                    <option value="{{ $kategorie->id }}" {{ $filterKategorie == $kategorie->id ? 'selected' : '' }}>
                        {{ $kategorie->name }}
                    </option>
                    @endforeach
                </select>
            </form>

            {{-- Projektkarten im Grid --}}
            @if($projekte->isEmpty())
            <div class="text-center py-20 text-gray-400">
                <div class="text-5xl mb-4">📭</div>
                <h3 class="text-lg font-semibold text-gray-500 mb-2">Keine Projekte gefunden</h3>
                <p class="text-sm">Versuche einen anderen Filter oder reiche eine neue Idee ein.</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projekte as $projekt)
                @php
                $statusLabel = match($projekt->bearbeitungsstatus) {
                'neu' => 'Offen',
                'in_pruefung' => 'In Bearbeitung',
                'angenommen' => 'Abgeschlossen',
                default => $projekt->bearbeitungsstatus,
                };
                $statusColor = match($projekt->bearbeitungsstatus) {
                'angenommen' => 'bg-gray-400 text-gray-900',
                'in_pruefung' => 'bg-blue-500 text-white',
                'neu' => 'bg-[#8dc63f] text-gray-900',
                default => 'bg-gray-200 text-gray-800',
                };
                @endphp

                <div class="bg-white p-6 rounded-xl border border-gray-200 flex flex-col h-full shadow-sm hover:shadow-md transition-shadow">

                    {{-- Kopfbereich --}}
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-col items-start gap-1">
                            <h3 class="text-xl font-bold text-[#1a202c]">{{ $projekt->projektname }}</h3>

                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Privat/Oeffentlich Label --}}
                                @if(!$projekt->is_public)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-medium rounded bg-gray-100 text-gray-600 border border-gray-300">
                                    🔒 Privat
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-medium rounded bg-blue-50 text-blue-600 border border-blue-200">
                                    🌐 Öffentlich
                                </span>
                                @endif

                                {{-- Eigene Idee Markierung --}}
                                @if($projekt->user_id === auth()->id())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-medium rounded bg-[#fff8e1] text-[#b7791f] border border-[#f6e05e]">
                                    ✏️ Meine Idee
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Status-Badge --}}
                        <span class="px-3 py-1 text-xs font-bold uppercase rounded-full ml-2 whitespace-nowrap {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Beschreibung --}}
                    <p class="text-gray-500 text-sm mt-2 mb-4 flex-grow">
                        {{ Str::limit($projekt->beschreibung, 100) }}
                    </p>

                    {{-- Kategorie & Ersteller --}}
                    <div class="text-xs text-gray-400 mb-3">
                        {{ $projekt->category->name ?? '—' }} &middot; {{ $projekt->user->name ?? 'Unbekannt' }}
                    </div>

                    {{-- Fusszeile: Details + Loeschen fuer Admin --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <a href="{{ route('projekte.details', $projekt->id) }}"
                            class="text-cyan-600 font-medium text-sm hover:underline">
                            Details ansehen &rarr;
                        </a>

                        {{-- Loeschen Button fuer Admin --}}
                        @if($istAdmin)
                        <form method="POST" action="{{ route('projekte.loeschen', $projekt->id) }}"
                            onsubmit="return confirm('Projekt wirklich löschen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-xs text-red-500 hover:text-red-700 font-medium">
                                🗑 Löschen
                            </button>
                        </form>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
            @endif

        </section>
    </div>
</div>
@endsection