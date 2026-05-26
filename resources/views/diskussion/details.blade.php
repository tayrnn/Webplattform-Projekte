@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">
        <section class="flex-1">
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
                <a href="{{ route('projekte.details', $diskussion->project_id) }}" class="font-bold text-lg text-gray-400 hover:text-[#0066cc]">← Zurück zum Projekt</a>
            </div>

            @if(session('erfolg'))
                <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">✓ {{ session('erfolg') }}</div>
            @endif
            @if(session('fehler'))
                <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">✕ {{ session('fehler') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 flex flex-col gap-6">
                    
                    {{-- KOPFZEILE DER DISKUSSION --}}
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-2">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Thema / Diskussions-Thread</span>
                        <h1 class="text-2xl font-bold text-[#1a202c]">{{ $diskussion->title }}</h1>
                    </div>

                    {{-- CHRONOLOGISCHE ANTWORTEN (Jede Antwort kann eine Umfrage sein) --}}
                    <h2 class="text-lg font-bold text-[#1a202c] flex items-center gap-2 mt-4">
                        💬 Beiträge <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full text-xs font-semibold">{{ $diskussion->antworten->count() }}</span>
                    </h2>

                    <div class="space-y-4">
                        @forelse($diskussion->antworten as $index => $antwort)
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-4">
                                
                                {{-- Meta-Informationen --}}
                                <div class="flex justify-between items-center text-xs text-gray-400 border-b border-gray-100 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span>👤 <strong class="text-gray-600">{{ $antwort->ersteller->name ?? 'Unbekannt' }}</strong></span>
                                        @if($index === 0)
                                            <span class="bg-blue-50 text-[#0066cc] text-[10px] font-bold px-2 py-0.5 rounded-md uppercase border border-blue-100">Themenstarter</span>
                                        @endif
                                    </div>
                                    <span>{{ \Carbon\Carbon::parse($antwort->created_at)->format('d.m.Y · H:i') }} Uhr</span>
                                </div>
                                
                                {{-- Beitragstext (Dient bei Umfragen als Frage) --}}
                                <div class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($antwort->content)) !!}</div>

                                {{-- INTEGRIERTE UMFRAGE COMPONENT (Falls bei dieser Antwort aktiviert) --}}
                                @if($antwort->ist_umfrage)
                                    <div class="mt-2 bg-gray-50 p-5 rounded-xl border border-gray-200 flex flex-col gap-4">
                                        @php 
                                            $hatAbgestimmt = $antwort->hatNutzerAbgestimmt(auth()->id());
                                        @endphp

                                        @if(!$hatAbgestimmt)
                                            {{-- Abstimmen-Formular --}}
                                            <form method="POST" action="{{ route('diskussion.abstimmen', $antwort->id) }}" class="space-y-3">
                                                @csrf
                                                @foreach($antwort->umfrageOptionen as $option)
                                                    <label class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg hover:border-[#6ba9dc] cursor-pointer transition">
                                                        <input type="radio" name="option_id" value="{{ $option->id }}" class="text-[#0066cc] focus:ring-[#0066cc]" required>
                                                        <span class="text-sm text-gray-700 font-medium">{{ $option->option_text }}</span>
                                                    </label>
                                                @endforeach
                                                <button type="submit" class="mt-2 bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-4 py-2 rounded text-xs font-bold transition shadow-sm">
                                                    Stimme abgeben
                                                </button>
                                            </form>
                                        @else
                                            {{-- Ergebnisse anzeigen --}}
                                            @php 
                                                $gesamtStimmen = $antwort->umfrageOptionen->sum(fn($o) => $o->stimmen->count());
                                            @endphp
                                            <div class="space-y-3">
                                                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">📊 Umfrage-Ergebnisse (Gesamt: {{ $gesamtStimmen }} Stimmen)</p>
                                                
                                                @foreach($antwort->umfrageOptionen as $option)
                                                    @php 
                                                        $anzahl = $option->stimmen->count();
                                                        $prozent = $gesamtStimmen > 0 ? round(($anzahl / $gesamtStimmen) * 100) : 0;
                                                    @endphp
                                                    <div>
                                                        <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1">
                                                            <span>{{ $option->option_text }}</span>
                                                            <span class="text-gray-500">{{ $anzahl }} St. ({!! $prozent !!}%)</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                            <div class="bg-[#6ba9dc] h-2.5 rounded-full transition-all" style="width: {{ $prozent }}%"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <span class="text-[11px] text-green-600 flex items-center gap-1 mt-2 font-medium">✓ Du hast bei dieser Umfrage bereits abgestimmt.</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 bg-white rounded-xl border border-gray-200 shadow-sm">Schreibe den ersten Beitrag im Thread!</div>
                        @endforelse
                    </div>

                    {{-- NEUER ANTWORT-INPUT (Mit optionaler Umfrage-Funktion für jeden Folgebeitrag) --}}
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mt-4">
                        <h3 class="text-sm font-bold text-gray-700 mb-3">Einen Beitrag verfassen</h3>
                        <form method="POST" action="{{ route('diskussion.antworten', $diskussion->id) }}">
                            @csrf
                            <div class="mb-4">
                                <textarea name="beitrag" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Schreibe eine Nachricht oder stelle eine Frage..." required></textarea>
                            </div>

                            {{-- Optionale Umfrage für die Antwort --}}
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-600 uppercase">
                                    <input type="checkbox" name="ist_umfrage" value="1" class="rounded text-[#0066cc] focus:ring-[#0066cc] h-3.5 w-3.5" onchange="document.getElementById('reply_poll_fields').classList.toggle('hidden', !this.checked)">
                                    <span>📊 Diesen Beitrag als neue Umfrage starten</span>
                                </label>
                                
                                <div id="reply_poll_fields" class="hidden mt-3 space-y-2 border-t border-gray-200 pt-3">
                                    <p class="text-[10px] text-gray-400 mb-1">Gib mindestens 2 Optionen ein:</p>
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-[#0066cc]" placeholder="Option 1">
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-[#0066cc]" placeholder="Option 2">
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:border-[#0066cc]" placeholder="Option 3 (optional)">
                                </div>
                            </div>

                            <button type="submit" class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-5 py-2 rounded-md text-sm font-bold transition shadow-sm">🚀 Nachricht senden</button>
                        </form>
                    </div>
                </div>

                {{-- SEITENLEISTE --}}
                <div class="flex flex-col gap-4">
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Projekt-Kontext</div>
                        <h3 class="font-bold text-sm text-gray-800 mb-2">{{ $diskussion->projekt->projektname }}</h3>
                        <a href="{{ route('projekte.details', $diskussion->project_id) }}" class="block text-center border border-gray-300 hover:bg-gray-50 text-gray-600 py-2 rounded-md text-xs font-bold transition">👁️ Projekt anzeigen</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection