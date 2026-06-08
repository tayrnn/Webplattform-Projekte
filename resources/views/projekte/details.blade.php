{{-- projekte/details.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 mt-6 mb-16">

    {{-- Zurück --}}
    <a href="{{ route('projekte.liste') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 mb-4 transition-colors">
        ← Zurück zur Übersicht
    </a>

    {{-- Erfolgs-/Fehlermeldungen --}}
    @if(session('erfolg'))
    <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2.5 rounded-lg mb-4">
        ✓ {{ session('erfolg') }}
    </div>
    @endif
    @if(session('fehler'))
    <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-2.5 rounded-lg mb-4">
        ✕ {{ session('fehler') }}
    </div>
    @endif

    {{-- Hauptkarte --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">

        {{-- Header: Titel + Status --}}
        <div class="flex justify-between items-start gap-3 mb-2">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Projektidee</p>
                <h1 class="text-xl font-semibold text-gray-900">{{ $projekt->projektname }}</h1>
            </div>
            @php
                $statusLabel = match($projekt->bearbeitungsstatus) {
                    'neu'         => 'Offen',
                    'in_pruefung' => 'In Bearbeitung',
                    'angenommen'  => 'Abgeschlossen',
                    default       => $projekt->bearbeitungsstatus,
                };
                $statusClass = match($projekt->bearbeitungsstatus) {
                    'neu'         => 'bg-green-100 text-green-800',
                    'in_pruefung' => 'bg-blue-100 text-blue-800',
                    'angenommen'  => 'bg-gray-200 text-gray-700',
                    default       => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusClass }} whitespace-nowrap">
                {{ $statusLabel }}
            </span>
        </div>

        {{-- Bild --}}
        @if($projekt->bildpfad)
        <img src="{{ asset('storage/' . $projekt->bildpfad) }}"
             alt="Bild zu {{ $projekt->projektname }}"
             class="w-full max-h-60 object-cover rounded-lg border border-gray-100 my-3">
        @endif

        {{-- Beschreibung --}}
        <p class="text-sm text-gray-500 leading-relaxed mb-4">
            {!! nl2br(e($projekt->beschreibung)) !!}
        </p>

        {{-- Ersteller + Datum --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 flex-wrap">
            <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-semibold uppercase flex-shrink-0">
                {{ substr($projekt->user->name ?? 'NA', 0, 2) }}
            </div>
            <span class="font-medium text-gray-800">{{ $projekt->user->name ?? '–' }}</span>
            @if($projekt->user?->role === 'student')
                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">Student</span>
            @elseif($projekt->user?->role === 'lehrende')
                <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full">Lehrende</span>
            @endif
            <span class="text-gray-300">·</span>
            <span>{{ \Carbon\Carbon::parse($projekt->created_at)->diffForHumans() }}</span>
        </div>

        {{-- Aktionen für eigene Projekte --}}
        @if($istStudent && $projekt->user_id === auth()->id())
        <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('projekte.bearbeiten', $projekt->id) }}"
               class="text-sm px-4 py-1.5 bg-blue-50 text-blue-700 rounded-lg font-medium hover:bg-blue-100 transition">
                ✏️ Bearbeiten
            </a>
            <form method="POST" action="{{ route('projekte.loeschen', $projekt->id) }}"
                  onsubmit="return confirm('Idee wirklich löschen?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-sm px-4 py-1.5 bg-red-50 text-red-600 rounded-lg font-medium hover:bg-red-100 transition">
                    🗑 Löschen
                </button>
            </form>
        </div>
        @endif

        {{-- Betreuung übernehmen --}}
        @if(Auth::check() && Auth::user()->role === 'lehrende' && $projekt->betreuer_id === null)
        <form method="POST" action="{{ route('betreuung.uebernehmen', $projekt->id) }}"
              class="mt-3 pt-3 border-t border-gray-100">
            @csrf
            <button type="submit"
                    onclick="return confirm('Möchten Sie dieses Projekt betreuen?')"
                    class="text-sm px-4 py-1.5 bg-blue-500 text-white rounded-lg font-medium hover:bg-blue-600 transition">
                Betreuung übernehmen
            </button>
        </form>
        @endif

        {{-- ── Sternebewertung ── --}}
        <div class="mt-5 pt-5 border-t border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Projektbewertung</p>

            <div class="flex items-center gap-3 flex-wrap">
                {{-- Durchschnitt --}}
                <span class="text-base font-semibold text-gray-800">
                    {{ number_format($durchschnitt ?? 0, 1, ',', '') }} Ø
                </span>

                {{-- Klickbare Sterne (Alpine.js) --}}
                <div x-data="{
                        hover: 0,
                        selected: {{ $eigeneBewertung ?? 0 }},
                        saved: {{ $eigeneBewertung ? 'true' : 'false' }},
                        setHover(v){ this.hover = v; },
                        clearHover(){ this.hover = 0; },
                        pick(v){ this.selected = v; this.saved = false; }
                     }"
                     class="flex items-center gap-2 flex-wrap">

                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                        <span
                            x-on:mouseenter="setHover({{ $i }})"
                            x-on:mouseleave="clearHover()"
                            x-on:click="pick({{ $i }})"
                            :class="(hover || selected) >= {{ $i }} ? 'text-amber-400' : 'text-gray-200'"
                            class="text-2xl cursor-pointer transition-colors select-none leading-none">★</span>
                        @endfor
                    </div>

                    {{-- Speichern-Button --}}
                    <form action="{{ route('projekte.bewerten', $projekt->id) }}" method="POST"
                          x-show="selected > 0 && !saved" style="display:none">
                        @csrf
                        <input type="hidden" name="bewertung" :value="selected">
                        <button type="submit" @click="saved = true"
                                class="text-xs px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                            Speichern
                        </button>
                    </form>

                    <span x-show="saved && selected > 0" class="text-xs text-green-600 font-medium" style="display:none">
                        ✓ Gespeichert
                    </span>
                </div>

                {{-- Stimmenverteilung --}}
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

    {{-- ── 2-Spalten: Diskussionen + Umfragen ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Linke Spalte: Diskussionen --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Diskussionen ({{ $projekt->diskussionen->count() }})
            </h2>

            @forelse($projekt->diskussionen ?? [] as $diskussion)
            {{-- Alpine für Bearbeiten und Schnellantwort --}}
            <div x-data="{ editDiscussion: false, zeigeAntwortBox: false }" class="bg-white border border-gray-200 rounded-xl px-4 py-3 mb-2 hover:border-blue-300 transition-colors">
                
                <div x-show="!editDiscussion" class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-semibold uppercase flex-shrink-0"
                         style="background:#E6F1FB;color:#0C447C">
                        {{ substr($diskussion->ersteller->name ?? 'NA', 0, 2) }}
                    </div>
                    
                    <a href="{{ route('diskussion.details', $diskussion->id) }}" class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate hover:text-blue-600 transition">{{ $diskussion->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $diskussion->ersteller->name ?? '–' }}
                            · {{ $diskussion->created_at->diffForHumans() }}
                            · {{ $diskussion->antworten_count ?? 0 }} Antworten
                        </p>
                    </a>
                    
                    {{-- Edit/Delete Optionen für eigene Diskussionen – immer sichtbar --}}
                    @if(auth()->id() === $diskussion->user_id)
                    <div class="flex flex-col items-end gap-1">
                        <button @click="editDiscussion = true" class="text-[10px] text-gray-400 hover:text-blue-600">✎ Bearbeiten</button>
                        <form action="{{ route('diskussion.loeschen', $diskussion->id) }}" method="POST" onsubmit="return confirm('Thema wirklich löschen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[10px] text-gray-400 hover:text-red-600">🗑 Löschen</button>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- Edit Formular --}}
                <div x-show="editDiscussion" x-collapse>
                    <form action="{{ route('diskussion.bearbeiten', $diskussion->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('PUT')
                        <textarea name="title" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-2 py-1 mb-2">{{ $diskussion->title }}</textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="editDiscussion = false" class="text-xs text-gray-500 hover:bg-gray-100 px-2 py-1 rounded">Abbrechen</button>
                            <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Speichern</button>
                        </div>
                    </form>
                </div>

                {{-- Schnellantwort-Bereich --}}
                <div class="mt-2 pt-2 border-t border-gray-50">
                    <button @click="zeigeAntwortBox = !zeigeAntwortBox" 
                            class="flex items-center gap-1 text-xs text-gray-500 hover:text-blue-600 transition-colors font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Schnellantwort
                    </button>

                    <div x-show="zeigeAntwortBox" x-collapse class="mt-2 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <form action="{{ route('diskussion.antworten', $diskussion->id) }}" method="POST">
                            @csrf
                            <textarea name="beitrag" 
                                      rows="2" 
                                      required
                                      placeholder="Schreibe deine Antwort auf dieses Thema..."
                                      class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 resize-none"></textarea>
                            
                            <div class="flex justify-end gap-2 mt-2">
                                <button type="button" 
                                        @click="zeigeAntwortBox = false"
                                        class="px-3 py-1.5 text-xs font-medium text-gray-500 hover:bg-gray-200 rounded transition">
                                    Abbrechen
                                </button>
                                <button type="submit" 
                                        class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition">
                                    Senden
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 mb-3">Noch keine Diskussionen.</p>
            @endforelse

            {{-- Neues Thema erstellen --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 mt-2"
                 x-data="{ istUmfrage: false, optionen: ['',''], text: '' }">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Neues Diskussionsthema erstellen</p>
                <form action="{{ route('diskussion.speichern', $projekt->id) }}" method="POST">
                    @csrf
                    <textarea name="title" x-model="text"
                              placeholder="Thema eingeben..."
                              rows="3"
                              class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700 placeholder-gray-300 mb-3"></textarea>

                    <label class="flex items-center gap-2 cursor-pointer mb-3" @click="istUmfrage = !istUmfrage">
                        <div class="relative w-8 h-4 rounded-full transition-colors flex-shrink-0"
                             :class="istUmfrage ? 'bg-blue-500' : 'bg-gray-200'">
                            <div class="absolute top-0.5 w-3 h-3 bg-white rounded-full shadow transition-transform"
                                 :style="istUmfrage ? 'transform:translateX(17px);left:0' : 'left:2px'"></div>
                        </div>
                        <input type="checkbox" name="ist_umfrage" value="1" :checked="istUmfrage" class="hidden">
                        <span class="text-sm text-gray-500">Als Umfrage erstellen</span>
                    </label>

                    <div x-show="istUmfrage" class="mb-3 space-y-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Antwortoptionen</p>
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
                        <button type="button" x-show="optionen.length < 6"
                                @click="optionen.push('')"
                                class="text-xs text-blue-500 hover:underline font-medium">
                            + Option hinzufügen
                        </button>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                :disabled="text.trim() === ''"
                                class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            Thema erstellen
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Rechte Spalte: Umfragen --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Umfragen ({{ $projekt->diskussionen->where('ist_umfrage', true)->count() }})
            </h2>

            @forelse($projekt->diskussionen->where('ist_umfrage', true) as $umfrage)
            @php $gesamtStimmen = $umfrage->stimmen->count(); @endphp
            <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3">
                <p class="text-sm font-medium text-gray-800 mb-3">{{ $umfrage->title }}</p>
                @foreach($umfrage->umfrageOptionen as $option)
                    @php
                        $cnt  = $umfrage->stimmen->where('poll_option_id', $option->id)->count();
                        $pct  = $gesamtStimmen > 0 ? round($cnt / $gesamtStimmen * 100) : 0;
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
</div>
@endsection