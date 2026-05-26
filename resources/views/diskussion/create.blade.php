@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">
    <div class="flex gap-12 items-start">
        <section class="flex-1">
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
                <a href="{{ route('projekte.details', $projekt->id) }}" class="font-bold text-lg text-gray-400 hover:text-[#0066cc]">← Zurück zum Projekt</a>
            </div>

            <h1 class="text-2xl font-bold text-[#1a1a4b] mb-1">Neues Thema starten</h1>
            <p class="text-gray-500 text-sm mb-6">Für Projekt: <strong class="text-gray-700">{{ $projekt->projektname }}</strong></p>

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm max-w-2xl">
                <form method="POST" action="{{ route('diskussion.speichern', $projekt->id) }}">
                    @csrf
                    
                    {{-- Titel --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Thema / Titel *</label>
                        <input type="text" name="titel" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Worüber möchtest du sprechen?" value="{{ old('titel') }}" required>
                    </div>

                    {{-- Beitrag --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Dein Beitrag *</label>
                        <p class="text-[11px] text-gray-400 mb-2">Hinweis: Wenn du eine Umfrage startest, dient dieser Text gleichzeitig als Fragestellung.</p>
                        <textarea name="beitrag" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Beschreibe dein Anliegen..." required>{{ old('beitrag') }}</textarea>
                    </div>

                    <hr class="border-gray-200 my-6">

                    {{-- UMFRAGE-TOGGLE --}}
                    <div class="mb-5 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="ist_umfrage" id="ist_umfrage" value="1" class="rounded text-[#0066cc] focus:ring-[#0066cc] h-4 w-4" onchange="toggleUmfrage(this.checked)" {{ old('ist_umfrage') ? 'checked' : '' }}>
                            <span class="text-sm font-bold text-gray-700">📊 An diesen Beitrag eine Umfrage hängen</span>
                        </label>

                        <div id="umfrage_fields" class="{{ old('ist_umfrage') ? '' : 'hidden' }} mt-4 space-y-4 border-t border-gray-200 pt-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Antwortoptionen (2 bis 10 Optionen) *</label>
                                <p class="text-[11px] text-gray-400 mb-3">Mindestens die ersten beiden Felder ausfüllen. Zusätzliche Optionen werden bei Bedarf eingeblendet.</p>
                                
                                <div id="options_container" class="space-y-2">
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Option 1">
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Option 2">
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Option 3 (optional)">
                                    <input type="text" name="optionen[]" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#0066cc]" placeholder="Option 4 (optional)">
                                </div>
                                
                                <button type="button" onclick="addOptionField()" class="mt-3 text-xs text-[#0066cc] hover:underline font-bold flex items-center gap-1">
                                    ➕ Weitere Option hinzufügen (Max. 10)
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-6 py-3 rounded-md font-bold transition-colors">Erstellen →</button>
                        <a href="{{ route('projekte.details', $projekt->id) }}" class="px-6 py-3 border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50 transition">Abbrechen</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<script>
    function toggleUmfrage(show) {
        const fields = document.getElementById('umfrage_fields');
        const inputs = fields.querySelectorAll('input[type="text"]');
        if(show) {
            fields.classList.remove('hidden');
            inputs[0].required = true;
            inputs[1].required = true;
        } else {
            fields.classList.add('hidden');
            inputs.forEach(input => input.required = false);
        }
    }

    function addOptionField() {
        const container = document.getElementById('options_container');
        const currentCount = container.querySelectorAll('input').length;
        
        if (currentCount >= 10) {
            alert('Es sind maximal 10 Optionen erlaubt.');
            return;
        }

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'optionen[]';
        input.className = 'w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#0066cc]';
        input.placeholder = `Option ${currentCount + 1} (optional)`;
        
        container.appendChild(input);
    }
</script>
@endsection