<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniProjekte</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

    <header class="bg-[#87afc7] shadow-sm border-b border-gray-300">
        <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-between">
            
            <a href="{{ url('/') }}" class="flex items-center gap-3 no-underline group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                <div class="flex items-center text-2xl font-bold tracking-tight">
                    <span class="text-[#0066cc]">Uni</span>
                    <span class="text-[#1a1a4b]">Projekte</span>
                </div>
            </a>

            <div class="relative w-[320px]">
                <input type="search" placeholder="Suche..." class="w-full bg-white rounded-full pl-9 pr-4 py-1.5 text-sm border-none shadow-sm focus:ring-2 focus:ring-blue-400 outline-none">
                <svg class="absolute left-3 top-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="flex items-center gap-4">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#d2f1ff] rounded-full flex items-center justify-center border-2 border-white text-xs font-bold text-cyan-800 shadow-sm uppercase">
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
                </div>
            </div>
        </div>
    </header>

    <div class="flex max-w-7xl mx-auto w-full items-start">
        
   {{-- <x-sidebar /> --}}

        <main class="flex-1 px-4 py-6">
            @yield('content')
        </main>
        
    </div>

</body>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</html>