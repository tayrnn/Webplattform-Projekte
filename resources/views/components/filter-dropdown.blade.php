{{--
  ============================================================
    COMPONENT: Filter-Dropdown für Projekt-Listing
  resources/views/components/filter-dropdown.blade.php
  
  VERWENDUNG (z.B. resources/views/projekte/index.blade.php):
  <x-filter-dropdown :activeStatus="$activeStatus" :activeKategorie="$activeKategorie" />
  ============================================================
--}}

@props([
    'activeStatus'    => request('status', ''),
    'activeKategorie' => request('kategorie', ''),
])

@php
    $statusOptionen = [
        'offen'           => 'Offen',
        'in_bearbeitung'  => 'In Bearbeitung',
        'abgeschlossen'   => 'Abgeschlossen',
        'betreuer_gesucht'=> 'Betreuer gesucht',
    ];

    $kategorieOptionen = [
        'programmierung' => 'Programmierung',
        'ki'             => 'KI (Künstliche Intelligenz)',
        'betriebssysteme'=> 'Betriebssysteme',
    ];

    $anzahlAktiv =
        ($activeStatus    !== '' ? 1 : 0) +
        ($activeKategorie !== '' ? 1 : 0);
@endphp

{{-- ── Filter-Leiste ──────────────────────────────────────── --}}
<div class="up-filter-bar">

    {{-- Tabs: Alle Ideen / Meine Projekte --}}
    <div class="up-tabs">
        <a href="{{ route('projekte.index') }}"
           class="up-tab {{ !request()->routeIs('meine-projekte*') ? 'active' : '' }}">
            Alle Ideen
        </a>
        <a href="{{ route('projekte.meine') }}"
           class="up-tab {{ request()->routeIs('meine-projekte*') ? 'active' : '' }}">
            Meine Projekte
        </a>
    </div>

    <div class="up-filter-actions">

        {{-- Filter-Button mit aktivem Badge --}}
        <div class="up-filter-wrapper" x-data="{ offen: false }" @click.outside="offen = false">

            <button @click="offen = !offen"
                    class="up-filter-btn"
                    :class="{ 'is-active': offen }"
                    type="button"
                    aria-haspopup="true"
                    :aria-expanded="offen">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                </svg>
                Filter
                @if($anzahlAktiv > 0)
                    <span class="up-filter-badge">{{ $anzahlAktiv }}</span>
                @endif
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" aria-hidden="true"
                     :style="offen ? 'transform:rotate(180deg)' : ''">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>

            {{-- Dropdown-Panel --}}
            <div x-show="offen"
                 x-transition:enter="up-fade-in"
                 x-cloak
                 class="up-dropdown"
                 role="dialog"
                 aria-label="Filteroptionen">

                <form method="GET" action="{{ url()->current() }}" id="filter-form">

                    {{-- Bestehende Query-Parameter beibehalten (z.B. Suchbegriff) --}}
                    @foreach(request()->except(['status','kategorie']) as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach

                    {{-- STATUS --}}
                    <fieldset class="up-filter-group">
                        <legend class="up-filter-legend">Status</legend>
                        @foreach($statusOptionen as $wert => $label)
                            <label class="up-filter-option">
                                <input type="radio"
                                       name="status"
                                       value="{{ $wert }}"
                                       {{ $activeStatus === $wert ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                <span class="up-checkmark" aria-hidden="true"></span>
                                {{ $label }}
                            </label>
                        @endforeach
                    </fieldset>

                    <hr class="up-divider">

                    {{-- KATEGORIEN --}}
                    <fieldset class="up-filter-group">
                        <legend class="up-filter-legend">Kategorien</legend>
                        @foreach($kategorieOptionen as $wert => $label)
                            <label class="up-filter-option">
                                <input type="radio"
                                       name="kategorie"
                                       value="{{ $wert }}"
                                       {{ $activeKategorie === $wert ? 'checked' : '' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                <span class="up-checkmark" aria-hidden="true"></span>
                                {{ $label }}
                            </label>
                        @endforeach
                    </fieldset>

                    {{-- Reset-Link --}}
                    @if($anzahlAktiv > 0)
                        <div class="up-reset-row">
                            <a href="{{ url()->current() }}" class="up-reset-link">
                                Filter zurücksetzen
                            </a>
                        </div>
                    @endif

                </form>
            </div>
        </div>

        {{-- Neue Idee Button --}}
        <a href="{{ route('projekte.create') }}" class="up-new-btn">
            + Neue Idee
        </a>
    </div>
</div>


{{-- ── CSS (einmalig – am besten in deine app.css verschieben) ── --}}
<style>
.up-filter-bar {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

/* Tabs */
.up-tabs { display: flex; gap: 4px; }
.up-tab {
    padding: 8px 14px;
    font-size: 14px;
    color: #6b7280;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: color .15s, border-color .15s;
}
.up-tab.active {
    color: #2563eb;
    border-bottom-color: #2563eb;
    font-weight: 500;
}

/* Rechte Seite */
.up-filter-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
}

/* Filter-Button */
.up-filter-wrapper { position: relative; }
.up-filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    font-size: 14px;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    cursor: pointer;
    color: #374151;
    transition: border-color .15s, background .15s;
}
.up-filter-btn:hover,
.up-filter-btn.is-active {
    border-color: #2563eb;
    background: #eff6ff;
    color: #1d4ed8;
}
.up-filter-btn svg { transition: transform .2s; }

/* Aktiv-Badge */
.up-filter-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    font-size: 11px;
    font-weight: 600;
    background: #2563eb;
    color: #fff;
    border-radius: 50%;
}

/* Dropdown-Panel */
.up-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    z-index: 50;
    width: 260px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
}

/* Filter-Gruppen */
.up-filter-group {
    border: none;
    padding: 0;
    margin: 0 0 4px;
}
.up-filter-legend {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #9ca3af;
    margin-bottom: 8px;
    display: block;
}

/* Radio-Option */
.up-filter-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 8px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    color: #374151;
    transition: background .1s;
}
.up-filter-option:hover { background: #f3f4f6; }
.up-filter-option input[type="radio"] { display: none; }

.up-checkmark {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 2px solid #d1d5db;
    flex-shrink: 0;
    transition: border-color .15s, background .15s;
    position: relative;
}
.up-filter-option input:checked + .up-checkmark {
    border-color: #2563eb;
    background: #2563eb;
}
.up-filter-option input:checked + .up-checkmark::after {
    content: '';
    position: absolute;
    top: 3px; left: 3px;
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #fff;
}

.up-divider {
    border: none;
    border-top: 1px solid #f3f4f6;
    margin: 10px 0;
}

/* Reset */
.up-reset-row {
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid #f3f4f6;
    text-align: center;
}
.up-reset-link {
    font-size: 13px;
    color: #2563eb;
    text-decoration: none;
}
.up-reset-link:hover { text-decoration: underline; }

/* Neue Idee Button */
.up-new-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: background .15s;
}
.up-new-btn:hover { background: #1d4ed8; }

/* Alpine Transition */
.up-fade-in { animation: upFade .15s ease; }
@keyframes upFade {
    from { opacity: 0; transform: translateY(-4px); }
    to   { opacity: 1; transform: translateY(0); }
}
[x-cloak] { display: none !important; }

/* Mobil */
@media (max-width: 640px) {
    .up-filter-bar { gap: 8px; }
    .up-dropdown { right: 0; left: auto; width: 240px; }
    .up-tabs { width: 100%; }
}
</style>