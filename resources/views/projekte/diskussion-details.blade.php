{{-- diskussion/details.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 mt-6 mb-16">

    <a href="javascript:history.back()"
       class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-blue-600 mb-4 transition-colors">
        ← Zurück
    </a>

    {{-- Thema-Header --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4">
        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Diskussionsthema</p>
        <h1 class="text-lg font-semibold text-gray-900 mb-4">{{ $diskussion->title }}</h1>
        <div class="flex items-center gap-2 text-sm text-gray-500 flex-wrap">
            <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-semibold uppercase flex-shrink-0">
                {{ substr($diskussion->ersteller->name ?? 'NA', 0, 2) }}
            </div>
            <span class="font-medium text-gray-800">{{ $diskussion->ersteller->name ?? '–' }}</span>
            <span class="text-gray-300">·</span>
            <span>{{ $diskussion->created_at->diffForHumans() }}</span>
        </div>
    </div>

    {{-- Antworten --}}
    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">
        Antworten ({{ $diskussion->antworten->count() }})
    </h2>

    @forelse($diskussion->antworten as $antwort)
    <div x-data="{ antwortFormular: false, editMode: false, menuOpen: false }" class="bg-white border border-gray-200 rounded-xl p-4 mb-3">
        <div class="flex items-center gap-2 mb-3 flex-wrap">
            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-700 flex items-center justify-center text-xs font-semibold uppercase flex-shrink-0">
                {{ substr($antwort->ersteller->name ?? 'NA', 0, 2) }}
            </div>
            <span class="text-sm font-medium text-gray-800">{{ $antwort->ersteller->name ?? '–' }}</span>
            @if($antwort->ersteller?->role === 'lehrende')
                <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full">Lehrende</span>
            @elseif($antwort->ersteller?->role === 'student')
                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">Student</span>
            @endif
            <span class="text-xs text-gray-400 ml-auto">{{ $antwort->created_at->diffForHumans() }}</span>

            {{-- Drei-Punkte-Menü für Bearbeiten / Löschen (Sichtbar für Autor ODER Admin) (Taqwa) --}}
            @if(auth()->check() && (auth()->id() === $antwort->user_id || auth()->user()->role === 'admin'))
            <div class="relative ml-2">
                <button @click="menuOpen = !menuOpen" class="text-gray-400 hover:text-gray-600 text-lg leading-none">⋮</button>
                <div x-show="menuOpen" @click.away="menuOpen = false" style="display: none;"
                     class="absolute right-0 mt-1 w-28 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden">
                    
                    {{-- Bearbeiten darf nur der eigentliche Autor (Taqwa) --}}
                    @if(auth()->id() === $antwort->user_id)
                    <button @click="editMode = true; menuOpen = false"
                            class="block w-full text-left px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100">✎ Bearbeiten</button>
                    @endif

                    {{-- Löschen darf Autor UND Admin (Taqwa) --}}
                    <form action="{{ route('diskussion.beitrag.loeschen', $antwort->id) }}" method="POST" onsubmit="return confirm('Wirklich löschen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="block w-full text-left px-3 py-1.5 text-xs text-red-600 hover:bg-gray-100 border-t border-gray-50">🗑 Löschen</button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- Bearbeiten-Formular (Taqwa) --}}
        <div x-show="editMode" x-collapse>
            <form action="{{ route('diskussion.beitrag.bearbeiten', $antwort->id) }}" method="POST" class="mb-2">
                @csrf @method('PUT')
                <textarea name="content" class="w-full text-sm border-gray-200 rounded-lg">{{ $antwort->content }}</textarea>
                <div class="flex justify-end gap-2 mt-1">
                    <button type="button" @click="editMode = false" class="text-xs text-gray-500 hover:bg-gray-100 px-2 py-1 rounded">Abbrechen</button>
                    <button type="submit" class="text-xs bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-2 py-1 rounded">Speichern</button>
                </div>
            </form>
        </div>

        {{-- Inhalt der Antwort --}}
        <p x-show="!editMode" class="text-sm text-gray-600 leading-relaxed">{{ $antwort->content }}</p>

        {{-- Antworten-Button (für eine neue Unterantwort) --}}
        <button @click="antwortFormular = !antwortFormular" class="mt-2 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
            </svg>
            Antworten
        </button>

        {{-- Formular zum Antworten auf diese Antwort --}}
        <div x-show="antwortFormular" x-collapse class="mt-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
            <form action="{{ route('diskussion.unterantwort.speichern', $antwort->id) }}" method="POST">
                @csrf
                <textarea name="content" rows="2" required placeholder="Schreibe eine Antwort..." class="w-full text-sm border-gray-200 rounded-lg"></textarea>
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" @click="antwortFormular = false" class="text-xs text-gray-500 px-2 py-1 hover:bg-gray-200 rounded">Abbrechen</button>
                    <button type="submit" class="text-xs bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-3 py-1 rounded">Senden</button>
                </div>
            </form>
        </div>

        {{-- Unterantworten (replies) anzeigen – rekursive Ansicht, aber nur eine Ebene tief --}}
        @if($antwort->unterantworten && $antwort->unterantworten->count() > 0)
        <div class="mt-4 pl-4 border-l-2 border-blue-100 space-y-3">
            @foreach($antwort->unterantworten as $unter)
            <div x-data="{ replyFormular: false, editKindMode: false, menuOpen: false }" class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                {{-- Kopf der Unterantwort --}}
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $unter->ersteller->name ?? 'NA' }}</span>
                        @if($unter->ersteller?->role === 'lehrende')
                            <span class="text-[9px] bg-purple-50 text-purple-700 px-1.5 py-0 rounded-full">Lehrende</span>
                        @elseif($unter->ersteller?->role === 'student')
                            <span class="text-[9px] bg-blue-50 text-blue-700 px-1.5 py-0 rounded-full">Student</span>
                        @endif
                        <span class="text-xs text-gray-400 ml-2">{{ $unter->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Drei-Punkte-Menü für Bearbeiten / Löschen bei Unterantwort (Autor ODER Admin) --}}
                    @if(auth()->check() && (auth()->id() === $unter->user_id || auth()->user()->role === 'admin'))
                    <div class="relative ml-2">
                        <button @click="menuOpen = !menuOpen" class="text-gray-400 hover:text-gray-600 text-lg leading-none">⋮</button>
                        <div x-show="menuOpen" @click.away="menuOpen = false" style="display: none;"
                             class="absolute right-0 mt-1 w-28 bg-white border border-gray-200 rounded-lg shadow-lg z-10 overflow-hidden">
                            
                            {{-- Bearbeiten darf nur der eigentliche Autor --}}
                            @if(auth()->id() === $unter->user_id)
                            <button @click="editKindMode = true; menuOpen = false"
                                    class="block w-full text-left px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-100">✎ Bearbeiten</button>
                            @endif

                            {{-- Löschen darf Autor UND Admin --}}
                            <form action="{{ route('diskussion.beitrag.loeschen', $unter->id) }}" method="POST" onsubmit="return confirm('Löschen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="block w-full text-left px-3 py-1.5 text-xs text-red-600 hover:bg-gray-100 border-t border-gray-50">🗑 Löschen</button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Bearbeiten Formular für Unterantwort --}}
                <div x-show="editKindMode" x-collapse>
                    <form action="{{ route('diskussion.beitrag.bearbeiten', $unter->id) }}" method="POST" class="mb-1">
                        @csrf @method('PUT')
                        <textarea name="content" class="w-full text-xs border-gray-200 rounded">{{ $unter->content }}</textarea>
                        <div class="flex justify-end gap-1 mt-1">
                            <button type="button" @click="editKindMode = false" class="text-[9px] text-gray-500 hover:bg-gray-200 px-2 py-1 rounded">Abbrechen</button>
                            <button type="submit" class="text-[9px] bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-2 py-0.5 rounded">Speichern</button>
                        </div>
                    </form>
                </div>

                <p x-show="!editKindMode" class="text-xs text-gray-600 mt-1">{{ $unter->content }}</p>

                {{-- Auch Unterantworten können beantwortet werden – Button + Formular --}}
                <button @click="replyFormular = !replyFormular" class="mt-2 text-[10px] font-medium text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    Antworten
                </button>

                <div x-show="replyFormular" x-collapse class="mt-2 bg-gray-100 p-2 rounded-lg border border-gray-200">
                    <form action="{{ route('diskussion.unterantwort.speichern', $unter->id) }}" method="POST">
                        @csrf
                        <textarea name="content" rows="2" required placeholder="Antworte auf diesen Kommentar..." class="w-full text-xs border-gray-200 rounded-lg"></textarea>
                        <div class="flex justify-end gap-2 mt-1">
                            <button type="button" @click="replyFormular = false" class="text-[9px] text-gray-500 px-2 py-1 hover:bg-gray-200 rounded">Abbrechen</button>
                            <button type="submit" class="text-[9px]bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-2 py-0.5 rounded">Senden</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white border border-dashed border-gray-200 rounded-xl p-8 text-center text-sm text-gray-400 mb-3">
        Noch keine Antworten. Schreib die erste!
    </div>
    @endforelse

    {{-- Antwort schreiben --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 mt-2">
        <p class="text-sm font-medium text-gray-700 mb-3">Antwort schreiben</p>
        <form action="{{ route('diskussion.antworten', $diskussion->id) }}" method="POST">
            @csrf
            <textarea name="beitrag"
                      placeholder="Deine Antwort..."
                      rows="3"
                      class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700 placeholder-gray-300 mb-3"></textarea>
            <div class="flex justify-end">
                <button type="submit"
                        class="px-4 py-2 bg-[#6ba9dc] hover:bg-[#5a91c4] text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Senden
                </button>
            </div>
        </form>
    </div>

</div>
@endsection