{{--
@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">
        <section class="flex-1">

            <!-- Obere Leiste: Tabs links, Filter + Button rechts -->
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">

                <!-- Tabs -->
                <div class="flex gap-8">
                    <a href="/student/alle-ideen"
                        class="font-bold text-lg transition-colors {{ request()->is('student/alle-ideen') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
Alle Ideen
</a>
<a href="/student/meine-projekte"
    class="font-bold text-lg transition-colors {{ request()->is('student/meine-projekte') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
    Meine Projekte
</a>
</div>

<!-- Filter-Dropdown + Neue Idee -->
<div class="flex items-center gap-3 mb-1">

    @php
    $activeStatus = request('status', '');
    $activeKategorie = request('kategorie', '');
    $anzahl = ($activeStatus ? 1 : 0) + ($activeKategorie ? 1 : 0);
    @endphp

    <!-- Filter-Button -->
    <div class="relative" x-data="{ offen: false }" @click.outside="offen = false">

        <button @click="offen = !offen" type="button"
            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold border rounded-md bg-white transition-colors hover:border-[#0066cc] hover:text-[#0066cc]"
            :class="offen ? 'border-[#0066cc] text-[#0066cc] bg-blue-50' : 'border-gray-300 text-gray-600'">
            <!-- Filter-Icon -->
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
            Filter
            @if($anzahl > 0)
            <span
                class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-[#0066cc] text-white rounded-full">
                {{ $anzahl }}
            </span>
            @endif
            <!-- Pfeil-Icon -->
            <svg class="w-3 h-3 transition-transform duration-200" :style="offen ? 'transform:rotate(180deg)' : ''"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 12 15 18 9" />
            </svg>
        </button>

        <!-- Dropdown-Panel -->
        <div x-show="offen" x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-cloak
            class="absolute right-0 top-[calc(100%+8px)] z-50 w-64 bg-white border border-gray-200 rounded-xl shadow-lg p-4">

            <form method="GET" action="{{ url()->current() }}" id="filter-form">

                <!-- STATUS -->
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Status</p>

                @foreach([
                'offen' => 'Offen',
                'in_bearbeitung' => 'In Bearbeitung',
                'abgeschlossen' => 'Abgeschlossen',
                'betreuer_gesucht' => 'Betreuer gesucht',
                ] as $wert => $label)
                <label
                    class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <input type="radio" name="status" value="{{ $wert }}" class="hidden"
                        {{ $activeStatus === $wert ? 'checked' : '' }}
                        onchange="document.getElementById('filter-form').submit()">
                    <span
                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                                     {{ $activeStatus === $wert ? 'border-[#0066cc] bg-[#0066cc]' : 'border-gray-300 bg-white' }}">
                        @if($activeStatus === $wert)
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        @endif
                    </span>
                    {{ $label }}
                </label>
                @endforeach

                <hr class="my-3 border-gray-100">

                <!-- KATEGORIEN -->
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Kategorien
                </p>

                @foreach([
                'programmierung' => 'Programmierung',
                'ki' => 'KI (Künstliche Intelligenz)',
                'betriebssysteme' => 'Betriebssysteme',
                ] as $wert => $label)
                <label
                    class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <input type="radio" name="kategorie" value="{{ $wert }}" class="hidden"
                        {{ $activeKategorie === $wert ? 'checked' : '' }}
                        onchange="document.getElementById('filter-form').submit()">
                    <span
                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                                     {{ $activeKategorie === $wert ? 'border-[#0066cc] bg-[#0066cc]' : 'border-gray-300 bg-white' }}">
                        @if($activeKategorie === $wert)
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        @endif
                    </span>
                    {{ $label }}
                </label>
                @endforeach

                <!-- Reset -->
                @if($anzahl > 0)
                <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                    <a href="{{ url()->current() }}" class="text-sm text-[#0066cc] hover:underline">
                        Filter zurücksetzen
                    </a>
                </div>
                @endif

            </form>
        </div>
    </div>

    <!-- Neue Idee Button -->
    <a href="/student/neue-idee"
        class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-5 py-2 rounded-md font-bold transition-colors flex items-center gap-2 shadow-sm">
        <span>+</span> Neue Idee
    </a>
</div>
</div>

<!-- Projektkarten -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($projekte as $projekt)

    <x-project-card :title="$projekt->projektname" :status="$projekt->bearbeitungsstatus"
        :beschreibung="$projekt->beschreibung" :needsSupervision="true" :id="$projekt->id" />

    @empty
    <p>Keine Projekte vorhanden.</p>
    @endforelse

</div>

</section>
</div>
</div>
@endsection
--}}