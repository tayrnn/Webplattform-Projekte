{{-- DATEI-PFAD: resources/views/projekte/index.blade.php --}}

@extends('layouts.app')

@section('content')

{{-- ── Filter-Leiste ──────────────────────────────────────── --}}
<div class="flex items-center gap-4 mb-6 flex-wrap">

    {{-- Tabs --}}
    <div class="flex gap-1 border-b border-gray-200">
        <a href="{{ route('projekte.index') }}"
           class="px-4 py-2 text-sm font-medium border-b-2 transition-colors
                  {{ !request()->routeIs('projekte.meine')
                       ? 'border-blue-600 text-blue-600'
                       : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Alle Ideen
        </a>
        <a href="{{ route('projekte.meine') }}"
           class="px-4 py-2 text-sm font-medium border-b-2 transition-colors
                  {{ request()->routeIs('projekte.meine')
                       ? 'border-blue-600 text-blue-600'
                       : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Meine Projekte
        </a>
    </div>

    {{-- Filter-Dropdown + Neue Idee (rechts) --}}
    <div class="ml-auto flex items-center gap-3">

        {{-- Filter-Button mit Alpine.js --}}
        <div class="relative" x-data="{ offen: false }" @click.outside="offen = false">

            <button @click="offen = !offen" type="button"
                    class="flex items-center gap-2 px-4 py-2 text-sm border rounded-lg bg-white
                           transition-colors hover:border-blue-500 hover:text-blue-600
                           focus:outline-none focus:ring-2 focus:ring-blue-400"
                    :class="offen ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-gray-300 text-gray-700'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" stroke-width="2"/>
                </svg>
                Filter
                @php $anzahl = ($activeStatus ? 1 : 0) + ($activeKategorie ? 1 : 0); @endphp
                @if($anzahl > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold
                                 bg-blue-600 text-white rounded-full">
                        {{ $anzahl }}
                    </span>
                @endif
                <svg class="w-3 h-3 transition-transform" :style="offen ? 'transform:rotate(180deg)' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            {{-- Dropdown-Panel --}}
            <div x-show="offen"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-cloak
                 class="absolute right-0 top-[calc(100%+8px)] z-50 w-64 bg-white
                        border border-gray-200 rounded-xl shadow-lg p-4">

                <form method="GET" action="{{ url()->current() }}" id="filter-form">
                    {{-- andere Query-Parameter behalten (z.B. Suche) --}}
                    @foreach(request()->except(['status','kategorie']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    {{-- STATUS --}}
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Status
                    </p>
                    @foreach([
                        'offen'            => 'Offen',
                        'in_bearbeitung'   => 'In Bearbeitung',
                        'abgeschlossen'    => 'Abgeschlossen',
                        'betreuer_gesucht' => 'Betreuer gesucht',
                    ] as $wert => $label)
                        <label class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer
                                      text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <input type="radio" name="status" value="{{ $wert }}"
                                   class="hidden"
                                   {{ $activeStatus === $wert ? 'checked' : '' }}
                                   onchange="document.getElementById('filter-form').submit()">
                            {{-- Custom Radio --}}
                            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                         {{ $activeStatus === $wert
                                              ? 'border-blue-600 bg-blue-600'
                                              : 'border-gray-300 bg-white' }}">
                                @if($activeStatus === $wert)
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                @endif
                            </span>
                            {{ $label }}
                        </label>
                    @endforeach

                    <hr class="my-3 border-gray-100">

                    {{-- KATEGORIEN --}}
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mb-2">
                        Kategorien
                    </p>
                    @foreach([
                        'programmierung'  => 'Programmierung',
                        'ki'              => 'KI (Künstliche Intelligenz)',
                        'betriebssysteme' => 'Betriebssysteme',
                    ] as $wert => $label)
                        <label class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer
                                      text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <input type="radio" name="kategorie" value="{{ $wert }}"
                                   class="hidden"
                                   {{ $activeKategorie === $wert ? 'checked' : '' }}
                                   onchange="document.getElementById('filter-form').submit()">
                            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                         {{ $activeKategorie === $wert
                                              ? 'border-blue-600 bg-blue-600'
                                              : 'border-gray-300 bg-white' }}">
                                @if($activeKategorie === $wert)
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                @endif
                            </span>
                            {{ $label }}
                        </label>
                    @endforeach

                    {{-- Reset --}}
                    @if($anzahl > 0)
                        <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                            <a href="{{ url()->current() }}"
                               class="text-sm text-blue-600 hover:underline">
                                Filter zurücksetzen
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Neue Idee --}}
        <a href="{{ route('projekte.create') }}"
           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                  rounded-lg transition-colors">
            + Neue Idee
        </a>
    </div>
</div>

{{-- ── Projekt-Karten ──────────────────────────────────────── --}}
@if($projekte->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm">Keine Projekte gefunden.</p>
        @if($anzahl > 0)
            <a href="{{ url()->current() }}" class="text-blue-500 text-sm hover:underline mt-1 inline-block">
                Filter zurücksetzen
            </a>
        @endif
    </div>
@else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($projekte as $projekt)
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col gap-3
                        hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

                {{-- Kopfzeile: Titel + Status-Badge --}}
                <div class="flex items-start justify-between gap-2">
                    <h2 class="text-base font-semibold text-gray-800 leading-snug">
                        {{ $projekt->titel }}
                    </h2>
                    @php
                        $badge = match($projekt->status) {
                            'offen'            => ['text' => 'Offen',          'class' => 'bg-green-100 text-green-700'],
                            'in_bearbeitung'   => ['text' => 'In Bearbeitung', 'class' => 'bg-blue-100 text-blue-700'],
                            'abgeschlossen'    => ['text' => 'Abgeschlossen',  'class' => 'bg-gray-200 text-gray-600'],
                            'betreuer_gesucht' => ['text' => 'Sucht Betreuer', 'class' => 'bg-yellow-100 text-yellow-700'],
                            default            => ['text' => $projekt->status, 'class' => 'bg-gray-100 text-gray-500'],
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap {{ $badge['class'] }}">
                        {{ $badge['text'] }}
                    </span>
                </div>

                {{-- Beschreibung --}}
                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                    {{ $projekt->beschreibung }}
                </p>

                {{-- Details-Link --}}
                <a href="{{ route('projekte.show', $projekt) }}"
                   class="mt-auto text-sm text-blue-600 hover:underline font-medium">
                    Details ansehen →
                </a>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $projekte->withQueryString()->links() }}
    </div>
@endif

@endsection