{{-- projekte/details.blade.php --}}
{{-- Detailansicht einer Projektidee --}}
{{-- Layout & Grundstruktur: Akshata | Bewertung, Diskussionen & Umfragen: Taqwa --}}
{{-- Zusammengeführt von: Tay --}}

@extends('layouts.app')

@section('content')
{{-- ===== LAYOUT-GERÜST: Akshata ===== --}}
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">

        <section class="flex-1">

            {{-- Obere Leiste (Akshata) --}}
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
                <a href="{{ route('projekte.liste') }}"
                    class="font-bold text-lg transition-colors text-gray-400 hover:text-[#0066cc]">
                    ← Zurück
                </a>
            </div>

            {{-- Erfolgs- / Fehlermeldungen (Akshata) --}}
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

                {{-- ===== HAUPTBEREICH: Akshata ===== --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-4">

                    {{-- Status & Kategorie (Akshata) --}}
                    <div class="flex flex-wrap gap-2">
                        @forelse($projekt->kategorien as $kategorie)
                        <span class="text-xs font-semibold text-[#0066cc] bg-blue-50 px-2 py-1 rounded-full">
                            {{ $kategorie->name }}
                        </span>
                        @empty
                        <span class="text-xs font-semibold text-gray-400 bg-gray-50 px-2 py-1 rounded-full">
                            Keine Kategorie
                        </span>
                        @endforelse
                        @php
                        $statusColor = match($projekt->bearbeitungsstatus) {
                        'angenommen' => 'bg-gray-400 text-gray-900',
                        'in_pruefung' => 'bg-blue-500 text-white',
                        'neu' => 'bg-[#8dc63f] text-gray-900',
                        default => 'bg-gray-200 text-gray-800',
                        };
                        $statusLabel = match($projekt->bearbeitungsstatus) {
                        'neu' => 'Offen',
                        'in_pruefung' => 'In Bearbeitung',
                        'angenommen' => 'Abgeschlossen',
                        default => $projekt->bearbeitungsstatus,
                        };
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold uppercase rounded-full {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Projekttitel (Akshata) --}}
                    <h1 class="text-2xl font-bold text-[#1a202c]">{{ $projekt->projektname }}</h1>

                    {{-- Projektbild, falls vorhanden (Akshata) --}}
                    @if($projekt->bildpfad)
                    <img src="{{ asset('storage/' . $projekt->bildpfad) }}" alt="Bild zu {{ $projekt->projektname }}"
                        class="w-full max-h-72 object-cover rounded-lg border border-gray-200">
                    @endif

                    {{-- Beschreibung (Akshata) --}}
                    <div class="text-sm text-gray-600 leading-relaxed">
                        {!! nl2br(e($projekt->beschreibung)) !!}
                    </div>

                    <hr class="border-gray-100">

                    {{-- Ersteller & Datum (Akshata) --}}
                    <div class="flex flex-wrap gap-6 text-sm text-gray-400">
                        <span>👤 <strong
                                class="text-gray-600">{{ $projekt->ersteller->name ?? 'Unbekannt' }}</strong></span>
                        <span>📅 Eingereicht am <strong
                                class="text-gray-600">{{ \Carbon\Carbon::parse($projekt->created_at)->format('d.m.Y') }}</strong></span>
                    </div>

                    {{-- ===== STERNEBEWERTUNG: Taqwa ===== --}}
                    <hr class="border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Projektbewertung</p>

                        <div class="flex items-center gap-3 flex-wrap">
                            {{-- Durchschnitt (Taqwa) --}}
                            <span class="text-base font-semibold text-gray-800">
                                {{ number_format($durchschnitt ?? 0, 1, ',', '') }} Ø
                            </span>

                            {{-- Klickbare Sterne mit Alpine.js (Taqwa) --}}
                            <div x-data="{
                                    hover: 0,
                                    selected: {{ $eigeneBewertung ?? 0 }},
                                    saved: {{ ($eigeneBewertung ?? 0) ? 'true' : 'false' }},
                                    setHover(v){ this.hover = v; },
                                    clearHover(){ this.hover = 0; },
                                    pick(v){ this.selected = v; this.saved = false; }
                                 }" class="flex items-center gap-2 flex-wrap">

                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++) <span x-on:mouseenter="setHover({{ $i }})"
                                        x-on:mouseleave="clearHover()" x-on:click="pick({{ $i }})"
                                        :class="(hover || selected) >= {{ $i }} ? 'text-amber-400' : 'text-gray-200'"
                                        class="text-2xl cursor-pointer transition-colors select-none leading-none">
                                        ★</span>
                                        @endfor
                                </div>

                                {{-- Speichern-Button erscheint nur nach Auswahl (Taqwa)--}}
                                <form action="{{ route('projekte.bewerten', $projekt->id) }}" method="POST"
                                    x-show="selected > 0 && !saved" style="display:none">
                                    @csrf
                                    <input type="hidden" name="bewertung" :value="selected">
                                    <button type="submit" @click="saved = true"
                                        class="text-xs px-3 py-1  bg-[#6ba9dc] hover:bg-[#5a91c4]  text-white transition font-medium">
                                        Speichern
                                    </button>
                                </form>

                                <span x-show="saved && selected > 0" class="text-xs text-green-600 font-medium"
                                    style="display:none">
                                    ✓ Gespeichert
                                </span>
                            </div>

                            {{-- Stimmenverteilung (Taqwa) --}}
                            <span class="text-xs text-gray-400 flex gap-2 flex-wrap">
                                <span>5★ {{ $verteilung[5] ?? 0 }}</span>
                                <span>4★ {{ $verteilung[4] ?? 0 }}</span>
                                <span>3★ {{ $verteilung[3] ?? 0 }}</span>
                                <span>2★ {{ $verteilung[2] ?? 0 }}</span>
                                <span>1★ {{ $verteilung[1] ?? 0 }}</span>
                            </span>
                            <span class="text-xs text-gray-400">{{ $gesamt ?? 0 }} Stimmen</span>
                        </div>
                    </div>

                </div>

                {{-- ===== SEITENLEISTE: Akshata ===== --}}
                <div class="flex flex-col gap-4">

                    {{-- Aktionen für Projekte (Sichtbar für Besitzer ODER Admin) --}}
                    @if(auth()->check() && ($projekt->ersteller_id === auth()->id() || auth()->user()->role ===
                    'admin'))
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
                            {{ $projekt->ersteller_id === auth()->id() ? 'Meine Idee' : 'Administration' }}
                        </div>

                        {{-- Bearbeiten darf NUR der Ersteller selbst --}}
                        @if($projekt->ersteller_id === auth()->id())
                        <a href="{{ route('projekte.bearbeiten', $projekt->id) }}"
                            class="block w-full text-center bg-[#6ba9dc] hover:bg-[#5a91c4] text-white py-2 rounded-md text-sm font-bold transition mb-3">
                            ✏️ Idee bearbeiten
                        </a>

                        {{-- Status aendern (Student & Admin) --}}
                        @if($istStudent && $projekt->ersteller_id === auth()->id() || $istAdmin)
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Status ändern
                            </div>
                            <form method="POST" action="{{ route('projekte.status', $projekt->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="bearbeitungsstatus"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] mb-3">
                                    <option value="offen"
                                        {{ $projekt->bearbeitungsstatus === 'offen' ? 'selected' : '' }}>
                                        Offen
                                    </option>
                                    <option value="in_bearbeitung"
                                        {{ $projekt->bearbeitungsstatus === 'in_bearbeitung' ? 'selected' : '' }}>
                                        In Bearbeitung
                                    </option>
                                    <option value="abgeschlossen"
                                        {{ $projekt->bearbeitungsstatus === 'abgeschlossen' ? 'selected' : '' }}>
                                        Abgeschlossen
                                    </option>
                                    <option value="betreuer_gesucht"
                                        {{ $projekt->bearbeitungsstatus === 'betreuer_gesucht' ? 'selected' : '' }}>
                                        Betreuer gesucht
                                    </option>
                                </select>
                                <button type="submit"
                                    class="w-full bg-[#6ba9dc] hover:bg-[#5a91c4] text-white py-2 rounded-md text-sm font-bold transition">
                                    Status speichern
                                </button>
                            </form>
                        </div>
                        @endif

                        {{-- Löschen dürfen Ersteller UND Admin --}}
                        <form method="POST" action="{{ route('projekte.loeschen', $projekt->id) }}"
                            onsubmit="return confirm('Idee wirklich löschen? Diese Aktion kann nicht rückgängig gemacht werden.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full bg-red-50 text-red-600 border border-red-200 py-2 rounded-md text-sm font-bold hover:bg-red-100 transition">
                                🗑 Idee löschen
                            </button>
                        </form>
                    </div>
                    @endif
                    {{-- Betreuung übernehmen, für Lehrende (Tayrit) --}}
                    @if(Auth::check() && Auth::user()->role === 'lehrender' && $projekt->betreuer_id === null)
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm"
                        x-data="{ modalOffen: false }">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Betreuung</div>

                        <button type="button" @click="modalOffen = true"
                            class="w-full bg-[#6ba9dc] text-white py-2 rounded-md text-sm font-bold hover:bg-[#5a91c4] transition">
                            Betreuung übernehmen
                        </button>

                        {{-- Modal-Overlay --}}
                        <div x-show="modalOffen" x-cloak
                            class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4"
                            x-transition.opacity>
                            <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-6"
                                @click.outside="modalOffen = false">
                                <h3 class="text-lg font-bold text-[#1a202c] mb-2">Projekt betreuen?</h3>
                                <p class="text-sm text-gray-500 mb-6">Möchten Sie dieses Projekt betreuen?</p>

                                <div class="flex gap-3">
                                    <form method="POST" action="{{ route('betreuung.uebernehmen', $projekt->id) }}"
                                        class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-[#6ba9dc] text-white py-2 rounded-md text-sm font-bold hover:bg-[#5a91c4] transition">
                                            Ja, übernehmen
                                        </button>
                                    </form>
                                    <button type="button" @click="modalOffen = false"
                                        class="flex-1 border border-gray-300 text-gray-600 py-2 rounded-md text-sm font-bold hover:bg-gray-50 transition">
                                        Abbrechen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Projektdetails (Akshata) --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Projektdetails</div>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-400">Kategorie</span>
                                <span class="font-semibold text-gray-700 text-right">
                                    {{ $projekt->kategorien->pluck('name')->implode(', ') ?: '—' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-400">Status</span>
                                <span class="px-2 py-1 text-xs font-bold uppercase rounded-full {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-400">Eingereicht von</span>
                                <span class="font-semibold text-gray-700">{{ $projekt->ersteller->name ?? '—' }}</span>
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

            {{-- ===== DISKUSSIONEN & UMFRAGEN: Taqwa ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-6">

                {{-- Diskussionen (Taqwa) --}}
                <div class="flex flex-col">
                    <h2
                        class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                        💬 Diskussionen
                    </h2>

                    @forelse($projekt->diskussionen->where('ist_umfrage', false) as $diskussion)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3 relative"
                        x-data="{ menuOpen: false, editDiscussion: false, zeigeAntwortBox: false }">

                        <div class="flex justify-between items-start">
                            <h3 class="text-sm font-medium text-gray-800">{{ $diskussion->title }}</h3>

                            {{-- Drei-Punkte-Menü für Diskussionsthema (Autor ODER Admin) --}}
                            @if(auth()->check() && ($diskussion->user_id === auth()->id() || auth()->user()->role ===
                            'admin'))
                            <div class="relative">
                                <button @click="menuOpen = !menuOpen"
                                    class="text-gray-400 hover:text-gray-600 text-lg leading-none">⋮</button>
                                <div x-show="menuOpen" @click.away="menuOpen = false" style="display:none"
                                    class="absolute right-0 mt-1 w-28 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden">

                                    {{-- Bearbeiten nur für den Autor --}}
                                    @if(auth()->id() === $diskussion->user_id)
                                    <button @click="editDiscussion = true; menuOpen = false"
                                        class="block w-full text-left px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100">✎
                                        Bearbeiten</button>
                                    @endif

                                    {{-- Löschen für Autor UND Admin --}}
                                    <form action="{{ route('diskussion.loeschen', $diskussion->id) }}" method="POST"
                                        onsubmit="return confirm('Thema wirklich löschen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="block w-full text-left px-3 py-1.5 text-xs text-red-600 hover:bg-gray-100 border-t border-gray-50">🗑
                                            Löschen</button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Edit Formular --}}
                        <div x-show="editDiscussion" x-collapse>
                            <form action="{{ route('diskussion.bearbeiten', $diskussion->id) }}" method="POST"
                                class="mt-2">
                                @csrf
                                @method('PUT')
                                <textarea name="title" rows="2"
                                    class="w-full text-sm border border-gray-200 rounded-lg px-2 py-1 mb-2">{{ $diskussion->title }}</textarea>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="editDiscussion = false"
                                        class="text-xs text-gray-500 hover:bg-gray-100 px-2 py-1 rounded">Abbrechen</button>
                                    <button type="submit"
                                        class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Speichern</button>
                                </div>
                            </form>
                        </div>

                        {{-- Schnellantwort & Pfeil --}}
                        <div class="mt-2 pt-2 border-t border-gray-50 flex justify-between items-center">
                            {{-- Reply Toggle --}}
                            <button @click="zeigeAntwortBox = !zeigeAntwortBox"
                                class="flex items-center gap-1 text-xs text-gray-500 hover:text-blue-600 transition-colors font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                Schnellantwort
                            </button>

                            {{-- Pfeil zur Detailseite --}}
                            <a href="{{ route('diskussion.details', $diskussion->id) }}"
                                class="text-gray-400 hover:text-blue-500 transition-colors flex items-center gap-1 text-xs font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>

                        {{-- Antwort-Box --}}
                        <div x-show="zeigeAntwortBox" x-collapse
                            class="mt-2 bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <form action="{{ route('diskussion.antworten', $diskussion->id) }}" method="POST">
                                @csrf
                                <textarea name="beitrag" rows="2" required
                                    placeholder="Schreibe deine Antwort auf dieses Thema..."
                                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 resize-none"></textarea>
                                <div class="flex justify-end gap-2 mt-2">
                                    <button type="button" @click="zeigeAntwortBox = false"
                                        class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-200 rounded transition">Abbrechen</button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-[#6ba9dc] hover:bg-[#5a91c4] text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                        Senden
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400 mb-3">Noch keine Diskussionen.</p>
                    @endforelse

                    {{-- Neues Diskussionsthema erstellen (Taqwa) --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-4 mt-2"
                        x-data="{ istUmfrage: false, optionen: ['',''], text: '' }">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Neues
                            Diskussionsthema erstellen</p>
                        <form action="{{ route('diskussion.speichern', $projekt->id) }}" method="POST">
                            @csrf
                            <textarea name="titel" x-model="text" placeholder="Thema eingeben..." rows="3"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700 placeholder-gray-300 mb-3"></textarea>

                            <label class="flex items-center gap-2 cursor-pointer mb-3"
                                @click="istUmfrage = !istUmfrage">
                                <div class="relative w-8 h-4 rounded-full transition-colors flex-shrink-0"
                                    :class="istUmfrage ? 'bg-blue-500' : 'bg-gray-200'">
                                    <div class="absolute top-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform"
                                        :style="istUmfrage ? 'transform:translateX(17px);left:0' : 'left:2px'"></div>
                                </div>
                                <input type="checkbox" name="ist_umfrage" value="1" :checked="istUmfrage"
                                    class="hidden">
                                <span class="text-sm text-gray-500">Als Umfrage erstellen</span>
                            </label>

                            <div x-show="istUmfrage" class="mb-3 space-y-2">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Antwortoptionen
                                </p>
                                <template x-for="(opt, index) in optionen" :key="index">
                                    <div class="flex gap-2">
                                        <input type="text" name="optionen[]" x-model="optionen[index]"
                                            :placeholder="'Option ' + (index + 1)"
                                            class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                        <button type="button" x-show="optionen.length > 2"
                                            @click="optionen.splice(index, 1)"
                                            class="text-gray-300 hover:text-red-400 text-lg leading-none">×</button>
                                    </div>
                                </template>
                                <button type="button" x-show="optionen.length < 6" @click="optionen.push('')"
                                    class="text-xs text-blue-500 hover:underline font-medium">
                                    + Option hinzufügen
                                </button>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" :disabled="text.trim() === ''"
                                    class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition disabled:opacity-40 disabled:cursor-not-allowed">
                                    Thema erstellen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Umfragen (Taqwa) --}}
                <div>
                    <h2
                        class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Umfragen ({{ $projekt->diskussionen->where('ist_umfrage', true)->count() }})
                    </h2>
                    @forelse($projekt->diskussionen->where('ist_umfrage', true) as $umfrage)
                    @php $gesamtStimmen = $umfrage->stimmen->count(); @endphp
                    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3">
                        <p class="text-sm font-medium text-gray-800 mb-3">{{ $umfrage->title }}</p>
                        @foreach($umfrage->umfrageOptionen as $option)
                        @php
                        $cnt = $umfrage->stimmen->where('poll_option_id', $option->id)->count();
                        $pct = $gesamtStimmen > 0 ? round($cnt / $gesamtStimmen * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2 text-sm mb-2">
                            <div class="w-3.5 h-3.5 rounded-full border border-gray-300 flex-shrink-0"></div>
                            <span class="text-gray-700 flex-1 text-xs">{{ $option->option_text }}</span>
                            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-400 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400 w-5 text-right">{{ $cnt }}</span>
                        </div>
                        @endforeach
                        <p class="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-100">
                            {{ $gesamtStimmen }} Stimmen
                        </p>
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">Noch keine Umfragen.</p>
                    @endforelse
                </div>

            </div>

        </section>
    </div>
</div>
@endsection