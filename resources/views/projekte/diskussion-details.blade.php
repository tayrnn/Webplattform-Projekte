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
    <div class="bg-white border border-gray-200 rounded-xl p-4 mb-3">
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
        </div>
        <p class="text-sm text-gray-600 leading-relaxed">{{ $antwort->content }}</p>
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
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Senden
                </button>
            </div>
        </form>
    </div>

</div>
@endsection