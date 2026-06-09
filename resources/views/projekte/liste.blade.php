@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">
        <section class="flex-1">

            {{-- Obere Leiste: Tabs + Filter + Buttons (Akshata - Layout-Struktur) --}}
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">

                <!-- Tabs -->
                <div class="flex gap-8">
                    @if($istStudent)
                    <a href="/student/alle-ideen"
                        class="font-bold text-lg transition-colors {{ request()->is('student/alle-ideen') || request()->is('student') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Alle
                        Ideen</a>
                    <a href="/student/meine-projekte"
                        class="font-bold text-lg transition-colors {{ request()->is('student/meine-projekte') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Meine
                        Projekte</a>
                    @elseif($istLehrender)
                    <a href="/lehrende/alle-ideen"
                        class="font-bold text-lg transition-colors {{ request()->is('lehrende/alle-ideen') || request()->is('lehrende') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Alle
                        Ideen</a>
                    <a href="/lehrende/betreute-projekte"
                        class="font-bold text-lg transition-colors {{ request()->is('lehrende/betreute-projekte') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Betreute
                        Projekte</a>
                    @elseif($istAdmin)
                    <a href="/admin"
                        class="font-bold text-lg transition-colors {{ request()->is('admin') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Alle
                        Projekte</a>
                    <a href="/admin/nutzer"
                        class="font-bold text-lg transition-colors {{ request()->is('admin/nutzer') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Nutzerverwaltung</a>
                    @endif
                </div>

                <!-- Rechte Seite: Filter + Buttons -->
                <div class="flex items-center gap-3 mb-1">
                    @php
                    $activeStatus = request('status', '');
                    $activeKategorie = request('kategorie', '');
                    $anzahl = ($activeStatus ? 1 : 0) + ($activeKategorie ? 1 : 0);
                    @endphp

                    {{-- Filter-Dropdown (Taqwa) --}}
                    <div class="relative" x-data="{ offen: false }" @click.outside="offen = false">
                        <button @click="offen = !offen" type="button"
                            class="flex items-center gap-2 px-4 py-2 text-sm font-semibold border rounded-md bg-white transition-colors hover:border-[#0066cc] hover:text-[#0066cc]"
                            :class="offen ? 'border-[#0066cc] text-[#0066cc] bg-blue-50' : 'border-gray-300 text-gray-600'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                            </svg>
                            Filter
                            @if($anzahl > 0)
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold bg-[#0066cc] text-white rounded-full">{{ $anzahl }}</span>
                            @endif
                            <svg class="w-3 h-3 transition-transform duration-200"
                                :style="offen ? 'transform:rotate(180deg)' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>

                        {{-- Dropdown-Panel (Taqwa) --}}
                        <div x-show="offen" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0" x-cloak
                            class="absolute right-0 top-[calc(100%+8px)] z-50 w-64 bg-white border border-gray-200 rounded-xl shadow-lg p-4">
                            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Status</p>
                                @foreach(['offen' => 'Offen', 'in_bearbeitung' => 'In Bearbeitung', 'abgeschlossen' =>
                                'Abgeschlossen', 'betreuer_gesucht' => 'Betreuer gesucht'] as $wert => $label)
                                <label
                                    class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="status" value="{{ $wert }}" class="hidden"
                                        {{ $activeStatus === $wert ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()">
                                    <span
                                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ $activeStatus === $wert ? 'border-[#0066cc] bg-[#0066cc]' : 'border-gray-300 bg-white' }}">
                                        @if($activeStatus === $wert)<span
                                            class="w-1.5 h-1.5 rounded-full bg-white"></span>@endif
                                    </span>
                                    {{ $label }}
                                </label>
                                @endforeach

                                <hr class="my-3 border-gray-100">

                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Kategorien
                                </p>
                                @foreach(['programmierung' => 'Programmierung', 'ki' => 'KI (Künstliche Intelligenz)',
                                'betriebssysteme' => 'Betriebssysteme'] as $wert => $label)
                                <label
                                    class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <input type="radio" name="kategorie" value="{{ $wert }}" class="hidden"
                                        {{ $activeKategorie === $wert ? 'checked' : '' }}
                                        onchange="document.getElementById('filter-form').submit()">
                                    <span
                                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ $activeKategorie === $wert ? 'border-[#0066cc] bg-[#0066cc]' : 'border-gray-300 bg-white' }}">
                                        @if($activeKategorie === $wert)<span
                                            class="w-1.5 h-1.5 rounded-full bg-white"></span>@endif
                                    </span>
                                    {{ $label }}
                                </label>
                                @endforeach

                                @if($anzahl > 0)
                                <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                                    <a href="{{ url()->current() }}"
                                        class="text-sm text-[#0066cc] hover:underline">Filter zurücksetzen</a>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    {{-- Buttons je nach Rolle (Akshata) --}}
                    @if($istAdmin)
                    <a href="/admin/nutzer/neu"
                        class="bg-white hover:bg-gray-50 text-[#0066cc] border border-[#0066cc] px-5 py-2 rounded-md font-bold transition-colors flex items-center gap-2 shadow-sm">
                        <span>+</span> Nutzer anlegen
                    </a>
                    <a href="/student/neue-idee"
                        class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-5 py-2 rounded-md font-bold transition-colors flex items-center gap-2 shadow-sm">
                        <span>+</span> Neue Idee
                    </a>
                    @elseif($istStudent)
                    <a href="{{ route('projekte.erstellen') }}"
                        class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-5 py-2 rounded-md font-bold transition-colors flex items-center gap-2 shadow-sm mb-1">
                        <span>+</span> Neue Idee
                    </a>
                    @endif
                </div>
            </div>

            {{-- Session Messages (Akshata) --}}
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

            {{-- Projektkarten Grid (Akshata - Layout, Taqwa - project-card Komponente) --}}
            @if(!($istAdmin && request()->is('admin/nutzer')))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projekte as $projekt)
                <div>
                    <x-project-card :title="$projekt->projektname" :status="$projekt->bearbeitungsstatus"
                        :beschreibung="$projekt->beschreibung" :needsSupervision="$projekt->betreuer_id === null"
                        :id="$projekt->id" :projektId="$projekt->id" :isPublic="$projekt->is_public" />
                </div>
                @empty
                <div class="text-center py-20 text-gray-400 col-span-3">
                    <div class="text-5xl mb-4">📭</div>
                    <p class="text-sm">Keine Projekte gefunden.</p>
                </div>
                @endforelse
            </div>
            @endif

            {{-- Nutzerverwaltung Tabelle - Admin only (Tayrit) --}}
            @if($istAdmin && request()->is('admin/nutzer'))
            <div class="mt-8">
                <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Rolle</th>
                            <th class="px-4 py-3 text-left">Aktion</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($nutzer ?? [] as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.nutzer.loeschen', $user->id) }}"
                                    onsubmit="return confirm('Nutzer wirklich löschen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">
                                        🗑 Löschen
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </section>
    </div>
</div>
@endsection