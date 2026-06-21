<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniProjekte</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 font-sans antialiased">

    <header class="bg-[#87afc7] shadow-sm border-b border-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">

            @php
            $homeUrl = '/login';

            if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
            $homeUrl = '/admin';
            } elseif (Auth::user()->role === 'lehrender') {
            $homeUrl = '/lehrende';
            } elseif (Auth::user()->role === 'student') {
            $homeUrl = '/student';
            }
            }
            @endphp

            <a href="{{ $homeUrl }}" class="flex items-center gap-3 no-underline group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                <div class="flex items-center text-2xl font-bold tracking-tight">
                    <span class="text-[#0066cc]">Uni</span>
                    <span class="text-[#1a1a4b]">Projekte</span>
                </div>
            </a>

            @php
                $istAdminNutzerverwaltung = Auth::check() && Auth::user()->role === 'admin' && (request()->is('admin/nutzer*'));

                $zielRoute = $istAdminNutzerverwaltung ? route('admin.nutzer.suchen') : url()->current();
            @endphp

            <form action="{{ $zielRoute }}" method="GET" class="relative w-[320px]">
                {{-- vorhandene Filteroptionen mitsenden, für die Kombination von Suchbegriff und Filtern --}}
                @foreach(request('filterStatus', []) as $status)
                    <input type="hidden" name="filterStatus[]" value="{{ $status }}">
                @endforeach
                @foreach(request('filterKategorie', []) as $kategorie)
                    <input type="hidden" name="filterKategorie[]" value="{{ $kategorie }}">
                @endforeach
                <input type="search" name="suche" placeholder="Suche..." value="{{ request('suche') }}"
                    class="w-full bg-white rounded-full pl-9 pr-4 py-1.5 text-sm border-none shadow-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <svg class="absolute left-3 top-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>

            <div class="flex items-center gap-4">
                {{-- Einstellungen Dropdown --}}
                <div class="relative flex items-center gap-3" x-data="{ offen: false }">

                    {{-- Avatar + Name + Rolle (klickbar) --}}
                    <button @click="offen = !offen" class="flex items-center gap-3 focus:outline-none">
                        <div
                            class="w-10 h-10 bg-[#d2f1ff] rounded-full flex items-center justify-center border-2 border-white text-xs font-bold text-cyan-800 shadow-sm uppercase">
                            {{ substr(auth()->user()?->name ?? 'NV', 0, 2) }}
                        </div>
                        <div class="flex flex-col gap-0.5 items-start">
                            <div class="bg-white border-[1.5px] border-gray-400 rounded-full px-3 py-0.5 shadow-sm">
                                <span class="text-xs font-bold text-gray-800">
                                    {{ auth()->user()?->name ?? 'Name' }}
                                </span>
                            </div>
                            <div class="bg-white border-[1.5px] border-gray-400 rounded-md px-2 py-0 ml-3 shadow-sm">
                                <span class="text-[9px] uppercase font-black text-gray-600 tracking-wider">
                                    {{ auth()->user()?->role ?? 'Rolle' }}
                                </span>
                            </div>
                        </div>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="offen" x-cloak @click.outside="offen = false"
                        class="absolute right-0 top-14 w-48 bg-white rounded-xl shadow-lg border border-gray-200 z-50 py-2">

                        {{-- Mein Profil --}}
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Mein Profil
                        </a>

                        <hr class="my-1 border-gray-100">

                        {{-- Abmelden --}}
                        <form method="POST" action="{{ route('abmelden') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Abmelden
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>

</html>