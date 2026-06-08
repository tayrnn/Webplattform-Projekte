{{-- diskussion/details.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 mt-6 mb-16">

    {{-- Zurück --}}
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
            @if($diskussion->ersteller?->role === 'student')
                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full">Student</span>
            @elseif($diskussion->ersteller?->role === 'lehrende')
                <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full">Lehrende</span>
            @endif
            <span class="text-gray-300">·</span>
            <span>{{ $diskussion->created_at->diffForHumans() }}</span>
        </div>
    </div>

    {{-- Antworten-Überschrift --}}
    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Antworten</h2>

    @forelse($beitraege as $antwort)
    {{-- Hauptantwort --}}
    <div x-data="{ zeigeAntwortFormular: false, editMode: false }" class="bg-white border border-gray-200 rounded-xl p-4 mb-3">
        
        {{-- Header der Antwort --}}
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
            
            {{-- Edit/Delete – immer sichtbar für eigene Antworten --}}
            @if(auth()->id() === $antwort->user_id)
            <div class="flex items-center gap-2">
                <button @click="editMode = true" class="text-[10px] text-gray-400 hover:text-blue-600">✎ Bearbeiten</button>
                <form action="{{ route('diskussion.beitrag.loeschen', $antwort->id) }}" method="POST" onsubmit="return confirm('Wirklich löschen?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-[10px] text-gray-400 hover:text-red-600">🗑 Löschen</button>
                </form>
            </div>
            @endif
        </div>

        {{-- Bearbeiten-Formular --}}
        <div x-show="editMode" x-collapse>
            <form action="{{ route('diskussion.beitrag.bearbeiten', $antwort->id) }}" method="POST" class="mb-2">
                @csrf @method('PUT')
                <textarea name="content" class="w-full text-sm border-gray-200 rounded-lg">{{ $antwort->content }}</textarea>
                <div class="flex justify-end gap-2 mt-1">
                    <button type="button" @click="editMode = false" class="text-xs text-gray-500">Abbrechen</button>
                    <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded">Speichern</button>
                </div>
            </form>
        </div>

        {{-- Inhalt --}}
        <p x-show="!editMode" class="text-sm text-gray-600 leading-relaxed">{{ $antwort->content }}</p>

        {{-- Antworten-Button --}}
        <button @click="zeigeAntwortFormular = !zeigeAntwortFormular" class="mt-2 text-xs font-medium text-gray-500 hover:text-blue-600 transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            Antworten
        </button>

        {{-- Formular für Unterantwort --}}
        <div x-show="zeigeAntwortFormular" x-collapse class="mt-3 bg-gray-50 p-3 rounded-lg border border-gray-100">
            <form action="{{ route('diskussion.unterantwort.speichern', $antwort->id) }}" method="POST">
                @csrf
                <textarea name="content" rows="2" required placeholder="Antworte auf diesen Beitrag..." class="w-full text-sm border-gray-200 rounded-lg"></textarea>
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" @click="zeigeAntwortFormular = false" class="text-xs text-gray-500">Abbrechen</button>
                    <button type="submit" class="text-xs bg-blue-600 text-white px-3 py-1 rounded">Senden</button>
                </div>
            </form>
        </div>

        {{-- Unterantworten (Kinder) anzeigen --}}
        @if($antwort->unterantworten && $antwort->unterantworten->count() > 0)
        <div class="mt-4 pl-4 border-l-2 border-blue-100 space-y-3">
            @foreach($antwort->unterantworten as $kind)
            <div x-data="{ editKindMode: false }" class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-700 uppercase">{{ $kind->ersteller->name ?? 'NA' }}</span>
                        @if($kind->ersteller?->role === 'lehrende')
                            <span class="text-[9px] bg-purple-50 text-purple-700 px-1.5 py-0 rounded-full">Lehrende</span>
                        @elseif($kind->ersteller?->role === 'student')
                            <span class="text-[9px] bg-blue-50 text-blue-700 px-1.5 py-0 rounded-full">Student</span>
                        @endif
                    </div>
                    
                    @if(auth()->id() === $kind->user_id)
                    <div class="flex items-center gap-2">
                        <button @click="editKindMode = true" class="text-[9px] text-gray-400 hover:text-blue-600">✎</button>
                        <form action="{{ route('diskussion.beitrag.loeschen', $kind->id) }}" method="POST" onsubmit="return confirm('Löschen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[9px] text-gray-400 hover:text-red-600">🗑</button>
                        </form>
                    </div>
                    @endif
                </div>

                {{-- Bearbeiten-Formular für Unterantwort --}}
                <div x-show="editKindMode" x-collapse>
                    <form action="{{ route('diskussion.beitrag.bearbeiten', $kind->id) }}" method="POST" class="mb-1">
                        @csrf @method('PUT')
                        <textarea name="content" class="w-full text-xs border-gray-200 rounded">{{ $kind->content }}</textarea>
                        <div class="flex justify-end gap-1 mt-1">
                            <button type="button" @click="editKindMode = false" class="text-[9px] text-gray-500">Abbrechen</button>
                            <button type="submit" class="text-[9px] bg-blue-600 text-white px-2 py-0.5 rounded">Speichern</button>
                        </div>
                    </form>
                </div>

                <p x-show="!editKindMode" class="text-xs text-gray-600">{{ $kind->content }}</p>
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

    {{-- Haupt-Antwort Formular (auf das Thema selbst) --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 mt-2 shadow-sm">
        <p class="text-sm font-medium text-gray-700 mb-3">Allgemeine Antwort schreiben</p>
        <form action="{{ route('diskussion.beitrag.speichern', $diskussion->id) }}" method="POST">
            @csrf
            <textarea name="content"
                      placeholder="Deine Antwort auf das Diskussionsthema..."
                      rows="3"
                      class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 text-gray-700 placeholder-gray-300 mb-3"></textarea>
            <div class="flex justify-end">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Senden
                </button>
            </div>
        </form>
    </div>

</div>
@endsection