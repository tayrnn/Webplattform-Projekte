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
                        class="font-bold text-lg transition-colors {{ request()->is('student/alle-ideen*') || request()->is('student') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Alle
                        Ideen</a>
                    <a href="/student/meine-projekte"
                        class="font-bold text-lg transition-colors {{ request()->is('student/meine-projekte*') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Meine
                        Projekte</a>
                    @elseif($istLehrender)
                    <a href="/lehrende/alle-ideen"
                        class="font-bold text-lg transition-colors {{ request()->is('lehrende/alle-ideen*') || request()->is('lehrende') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Alle
                        Ideen</a>
                    <a href="/lehrende/betreute-projekte"
                        class="font-bold text-lg transition-colors {{ request()->is('lehrende/betreute-projekte*') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Betreute
                        Projekte</a>
                    @elseif($istAdmin)
                    <a href="/admin"
                        class="font-bold text-lg transition-colors {{ (request()->is('admin') || request()->is('admin/projekte*')) ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Alle
                        Projekte</a>
                    <a href="/admin/nutzer"
                        class="font-bold text-lg transition-colors {{ (request()->is('admin/nutzer') || request()->is('admin/nutzer/suchen*')) ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">Nutzerverwaltung</a>
                    @endif
                </div>

                <!-- Rechte Seite: Filter + Buttons -->
                <div class="flex items-center gap-3 mb-1">
                    @php
                    //Auswahl mehrerer Filteroptionen gleichzeitig ermöglichen (Armin)
                    $activeStatus = request('filterStatus', []);
                    if (!is_array($activeStatus)) { $activeStatus = [$activeStatus]; } //automatische Umwandlung in ein Array, falls nur ein einzelner Wert übergeben wird (Sicherheitsmaßname)
                    $activeKategorie = request('filterKategorie', []);
                    if (!is_array($activeKategorie)) { $activeKategorie = [$activeKategorie]; }
                    $anzahl = count(array_filter($activeStatus)) + count(array_filter($activeKategorie)); //Anzahl der Filteroptionen wird via count gezählt

                    //array_filter entfernt leere Werte, damit die Anzahl der aktiven Filter korrekt gezählt wird, auch wenn nur ein einzelner Filter ausgewählt ist
                    $dropdownAktiv = $anzahl > 0 ? 'true' : 'false'; //Variable, damit das Dropdown-Menü auch nach Filter-Auswahl geöffnet bleibt

                    $istAdminNutzerverwaltung = Auth::check() && Auth::user()->role === 'admin' && (request()->is('admin/nutzer*'));

                    $zielRoute = $istAdminNutzerverwaltung ? route('admin.nutzer.suchen') : url()->current();
                    @endphp

                    {{-- Filter-Dropdown (Taqwa) --}}
                    <div class="relative" x-data="{ offen: {{ $dropdownAktiv }} }" @click.outside="offen = false">
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
                            <form method="GET" action="{{ $zielRoute }}" id="filter-form">

                                {{-- Suchbegriff mitsenden, damit die Kombination von Suchbegriff und Filtern möglich ist --}}
                                @if(request('suche'))
                                    <input type="hidden" name="suche" value="{{ request('suche') }}">
                                @endif

                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Status</p>
                                @foreach(['offen' => 'Offen', 'in_bearbeitung' => 'In Bearbeitung', 'abgeschlossen' =>
                                'Abgeschlossen', 'betreuer_gesucht' => 'Betreuer gesucht'] as $wert => $label)
                                @php $statusChecked = in_array($wert, $activeStatus); 
                                //sorgt dafür, dass die Variable $activeStatus den aktuellen Status des Filters korrekt wiedergibt, auch wenn mehrere Filteroptionen gleichzeitig ausgewählt sind
                                @endphp 
                                
                                <label
                                    class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="filterStatus[]" value="{{ $wert }}" class="hidden" {{-- Mehrfachauswahl ermöglichen + PHP anweisen, mehrere Werte zu übergeben --}}
                                        {{ $statusChecked ? 'checked' : '' }}
                                        onchange="this.form.submit()"> {{-- automatisches Absenden der Filteroption beim Anklicken --}}
                                    <span
                                        class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ $statusChecked ? 'border-[#0066cc] bg-[#0066cc]' : 'border-gray-300 bg-white' }}">
                                        @if($statusChecked)
                                        <svg 
                                            class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <polyline points="20 6 9 17 4 12" /> {{-- die Linie des Häkchens in der Checkbox --}}
                                        </svg> {{-- Formatierung des Häkchens in der Checkbox --}}
                                        @endif 
                                    </span>
                                    {{ $label }} 
                                </label>
                                @endforeach

                                <hr class="my-3 border-gray-100">

                                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-2">Kategorien</p>
                                @foreach($kategorien as $kategorie) {{-- dynamische Erzeugung der Filteroptionen auf Basis der aus der Datenbank an den View übergebenen Kategorien --}}
                                @php $kategorieChecked = in_array($kategorie->id, $activeKategorie); @endphp {{-- Überprüfung, bei welchen Kategorien der Filter aktiv ist --}}
                                <label
                                    class="flex items-center gap-3 px-2 py-1.5 rounded-lg cursor-pointer text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="filterKategorie[]" value="{{ $kategorie->id }}" class="hidden"
                                        {{ $kategorieChecked ? 'checked' : '' }}
                                        onchange="this.form.submit()"> {{-- automatisches Absenden der Filteroption beim Anklicken --}}
                                    <span
                                        class="w-4 h-4 rounded border-2 flex items-center justify-center flex-shrink-0 {{ $kategorieChecked ? 'border-[#0066cc] bg-[#0066cc]' : 'border-gray-300 bg-white' }}">
                                        @if($kategorieChecked)
                                        <svg
                                            class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <polyline points="20 6 9 17 4 12" /> {{-- die Linie des Häkchens in der Checkbox --}}
                                        </svg> {{-- Formatierung des Häkchens in der Checkbox --}}
                                        @endif
                                    </span>
                                    {{ $kategorie->name }} {{-- Anzeige der Filteroptionen mit lesbaren Labels dynamisch anhand der übergebenen Werte --}}
                                </label>
                                @endforeach

                                {{-- Button zum Zurücksetzen der Filteroptionen --}}
                                @if($anzahl > 0)
                                <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                                    {{-- damit der Suchbegriff beim Zurücksetzen der Filteroptionen erhalten bleibt, wird die aktuelle URL mit den bestehenden Suchparametern beibehalten, nur die Filter-Parameter werden entfernt --}}
                                    <a href="{{ request()->fullUrlWithQuery(['filterStatus' => null, 'filterKategorie' => null]) }}"
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
            @if(!($istAdmin && (request()->is('admin/nutzer') || request()->is('admin/nutzer/suchen'))))
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
            @if($istAdmin && (request()->is('admin/nutzer') || request()->is('admin/nutzer/suchen')))
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