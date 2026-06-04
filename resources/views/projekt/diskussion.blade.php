@extends('layouts.app')

@section('content')

@php
// ── Testdaten (später aus Datenbank) ─────────────────────
$projekt = [
'titel' => 'Erstes Projekt',
'status' => 'Offen',
'beschreibung'=> 'Hier steht eine ausführliche Beschreibung der Projektidee. Ziel ist es, eine moderne Webanwendung zu
entwickeln, die Studierenden und Lehrenden die Zusammenarbeit erleichtert.',
'autor' => 'Max Mustermann',
'rolle' => 'Student',
'datum' => '12.05.2025',
'sterne' => 4.5,
'bewertungen' => 12,
];

$kommentare = [
[
'id' => 1,
'autor' => 'Anna Schmidt',
'rolle' => 'Lehrende',
'text' => 'Sehr interessante Idee! Ich würde gerne mehr über den technischen Ansatz erfahren.',
'datum' => 'vor 2 Tagen',
'antworten' => [
['autor' => 'Max Mustermann', 'rolle' => 'Student', 'text' => 'Wir planen Laravel als Backend und Vue.js für das
Frontend.', 'datum' => 'vor 1 Tag'],
['autor' => 'Lisa Weber', 'rolle' => 'Student', 'text' => 'Ich wäre auch dabei, falls noch Teammitglieder gesucht
werden!', 'datum' => 'vor 1 Tag'],
],
],
[
'id' => 2,
'autor' => 'Prof. Dr. Müller',
'rolle' => 'Lehrende',
'text' => 'Ich könnte mir vorstellen, die Betreuung zu übernehmen. Bitte meldet euch per E-Mail.',
'datum' => 'vor 1 Tag',
'antworten' => [
['autor' => 'Max Mustermann', 'rolle' => 'Student', 'text' => 'Vielen Dank! Ich melde mich diese Woche.', 'datum' =>
'vor 20 Stunden'],
],
],
[
'id' => 3,
'autor' => 'Tom Becker',
'rolle' => 'Student',
'text' => 'Gibt es schon ein GitHub-Repository für das Projekt?',
'datum' => 'vor 5 Stunden',
'antworten' => [],
],
];

$umfrage = [
'frage' => 'Welche Technologie bevorzugt ihr für das Frontend?',
'optionen' => [
['text' => 'Vue.js', 'stimmen' => 8],
['text' => 'React', 'stimmen' => 5],
['text' => 'Blade', 'stimmen' => 3],
],
'gesamt' => 16,
];
@endphp

<div class="max-w-4xl mx-auto px-6 mt-8 mb-16">

    {{-- ── Zurück-Link ──────────────────────────────────────── --}}
    <a href="javascript:history.back()"
        class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#0066cc] mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Zurück zur Übersicht
    </a>
    {{-- ── Projekt-Header ───────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
        <div class="flex justify-between items-start gap-4 mb-3">
            <h1 class="text-2xl font-bold text-[#1a202c]">{{ $projekt['titel'] }}</h1>
            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 whitespace-nowrap">
                {{ $projekt['status'] }}
            </span>
        </div>

        <p class="text-gray-500 text-sm leading-relaxed mb-6">{{ $projekt['beschreibung'] }}</p>

        {{-- UNTERE ZEILE: Autor (Links) & Sterne (Rechts) --}}
        <div class="flex items-center justify-between border-t border-gray-100 pt-4">

            {{-- Linke Seite: Autor Infos --}}
            <div class="flex items-center gap-3 text-xs text-gray-400">
                <div
                    class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold uppercase">
                    {{ substr($projekt['autor'], 0, 2) }}
                </div>
                <span class="font-medium text-gray-600">{{ $projekt['autor'] }}</span>
                <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $projekt['rolle'] }}</span>
                <span>·</span>
                <span>{{ $projekt['datum'] }}</span>
            </div>

            {{-- Rechte Seite: Sterne-Bewertung (Klein) --}}
            <div class="flex items-center gap-1.5 cursor-pointer hover:opacity-80 transition-opacity"
                title="{{ $projekt['bewertungen'] }} Bewertungen">
                <div class="flex text-yellow-400">
                    {{-- 4 Volle Sterne --}}
                    @for($i = 0; $i < floor($projekt['sterne']); $i++) <svg class="w-4 h-4 fill-current"
                        viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endfor

                        {{-- 1 Halber Stern --}}
                        @if(fmod($projekt['sterne'], 1) !== 0.0)
                        <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                            <defs>
                                <linearGradient id="half" x1="0" x2="100%" y1="0" y2="0">
                                    <stop offset="50%" stop-color="currentColor" />
                                    <stop offset="50%" stop-color="#e5e7eb" />
                                </linearGradient>
                            </defs>
                            <path fill="url(#half)"
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endif
                </div>

                <span class="text-xs font-bold text-gray-700">{{ $projekt['sterne'] }}</span>
                <span class="text-[10px] text-gray-400">({{ $projekt['bewertungen'] }})</span>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── Kommentare (links, 2/3 Breite) ──────────────── --}}
        <div class="lg:col-span-2 space-y-4">

            <h2 class="text-base font-bold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#0066cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Diskussion
                <span class="text-xs font-normal text-gray-400">({{ count($kommentare) }} Kommentare)</span>
            </h2>

            {{-- Kommentar-Liste --}}
            @foreach($kommentare as $k)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">

                {{-- Kommentar-Kopf --}}
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-8 h-8 rounded-full bg-[#d2f1ff] text-cyan-800 flex items-center justify-center font-bold text-xs uppercase">
                        {{ substr($k['autor'], 0, 2) }}
                    </div>
                    <div>
                        <span class="font-semibold text-sm text-gray-800">{{ $k['autor'] }}</span>
                        <span
                            class="ml-2 text-[10px] px-2 py-0.5 rounded-full
                                {{ $k['rolle'] === 'Lehrende' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $k['rolle'] }}
                        </span>
                    </div>
                    <span class="ml-auto text-xs text-gray-400">{{ $k['datum'] }}</span>
                </div>

                {{-- Kommentar-Text --}}
                <p class="text-sm text-gray-600 leading-relaxed mb-3">{{ $k['text'] }}</p>

                {{-- Antworten --}}
                @if(count($k['antworten']) > 0)
                <div class="ml-6 border-l-2 border-gray-100 pl-4 space-y-3 mb-3">
                    @foreach($k['antworten'] as $a)
                    <div class="flex gap-3">
                        <div
                            class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-[10px] uppercase flex-shrink-0 mt-0.5">
                            {{ substr($a['autor'], 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-xs text-gray-700">{{ $a['autor'] }}</span>
                                <span
                                    class="text-[10px] px-1.5 py-0.5 rounded-full
                                                {{ $a['rolle'] === 'Lehrende' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $a['rolle'] }}
                                </span>
                                <span class="text-[10px] text-gray-400">{{ $a['datum'] }}</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $a['text'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Antworten-Button --}}
                <button class="text-xs text-[#0066cc] hover:underline font-medium flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="9 14 4 9 9 4" />
                        <path d="M20 20v-7a4 4 0 0 0-4-4H4" />
                    </svg>
                    Antworten
                </button>
            </div>
            @endforeach

            {{-- ── Neuer Kommentar ──────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5" x-data="{ text: '' }">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Kommentar schreiben</h3>
                <textarea x-model="text" placeholder="Dein Kommentar..." rows="3" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 resize-none
                                 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-[#0066cc]
                                 text-gray-700 placeholder-gray-400"></textarea>
                <div class="flex justify-end mt-2">
                    <button :disabled="text.trim() === ''"
                        class="px-4 py-2 bg-[#0066cc] text-white text-sm font-semibold rounded-lg
                                   hover:bg-blue-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        Kommentar senden
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Umfrage (rechts, 1/3 Breite) ────────────────── --}}
        <div class="space-y-4">

            <h2 class="text-base font-bold text-gray-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#0066cc]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Umfrage
            </h2>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5"
                x-data="{ gewählt: null, abgestimmt: false }">

                <p class="text-sm font-semibold text-gray-700 mb-4">{{ $umfrage['frage'] }}</p>

                <div class="space-y-2 mb-4">
                    @foreach($umfrage['optionen'] as $i => $option)
                    @php $prozent = round(($option['stimmen'] / $umfrage['gesamt']) * 100); @endphp
                    <label class="block cursor-pointer" x-show="!abgestimmt">
                        <input type="radio" name="umfrage" value="{{ $i }}" x-model="gewählt" class="hidden">
                        <div class="flex items-center gap-3 px-3 py-2 rounded-lg border transition-all" :class="gewählt == '{{ $i }}'
                                    ? 'border-[#0066cc] bg-blue-50'
                                    : 'border-gray-200 hover:border-gray-300'">
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0"
                                :class="gewählt == '{{ $i }}'
                                        ? 'border-[#0066cc] bg-[#0066cc]'
                                        : 'border-gray-300'">
                                <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="gewählt == '{{ $i }}'"></div>
                            </div>
                            <span class="text-sm text-gray-700">{{ $option['text'] }}</span>
                        </div>
                    </label>

                    {{-- Ergebnis nach Abstimmung --}}
                    <div x-show="abgestimmt" class="space-y-1">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>{{ $option['text'] }}</span>
                            <span class="font-semibold">{{ $prozent }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-[#0066cc] h-2 rounded-full transition-all duration-500"
                                style="width: {{ $prozent . '%' }};">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div x-show="!abgestimmt">
                    <button @click="if(gewählt !== null) abgestimmt = true" :disabled="gewählt === null"
                        class="w-full py-2 bg-[#0066cc] text-white text-sm font-semibold rounded-lg
                                   hover:bg-blue-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                        Abstimmen
                    </button>
                </div>

                <p class="text-xs text-gray-400 mt-3 text-center">
                    {{ $umfrage['gesamt'] }} Stimmen insgesamt
                </p>
            </div>

            {{-- Info-Box --}}
            <div class="bg-blue-50 rounded-xl border border-blue-100 p-4">
                <p class="text-xs font-semibold text-blue-700 mb-1">Projekt beitreten?</p>
                <p class="text-xs text-blue-600 leading-relaxed mb-3">
                    Du möchtest an diesem Projekt mitarbeiten? Schreib einen Kommentar oder kontaktiere den Autor
                    direkt.
                </p>

            </div>
        </div>
    </div>
</div>

@endsection