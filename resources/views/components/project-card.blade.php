@props(['title', 'status','beschreibung' => '', 'needsSupervision' => false, 'projektId' => 1, 'id'])

@php
$statusConfig = match(strtolower($status)) {
'abgeschlossen' => ['bg' => 'bg-gray-100 text-gray-600', 'label' => 'Abgeschlossen'],
'in bearbeitung', 'in_bearbeitung' => ['bg' => 'bg-blue-100 text-blue-700', 'label' => 'In Bearbeitung'],
'offen' => ['bg' => 'bg-green-100 text-green-700', 'label' => 'Offen'],
'betreuer gesucht','betreuer_gesucht' => ['bg' => 'bg-yellow-100 text-yellow-700','label' => 'Betreuer gesucht'],
default => ['bg' => 'bg-gray-100 text-gray-500', 'label' => $status],
};
@endphp

<div class="bg-white p-6 rounded-xl border border-gray-200 flex flex-col h-full
            shadow-sm transition-all duration-200
            hover:border-blue-300 hover:shadow-md hover:ring-2 hover:ring-blue-100">

    <div class="flex justify-between items-start">

        <!-- Linke Seite: Titel und Label -->
        <div class="flex flex-col items-start">
            <h3 class="text-xl font-bold text-[#1a202c]">{{ $title }}</h3>

            @if($needsSupervision)
            <!-- Das gelbe "Sucht Betreuer" Label  -->
            <span
                class="inline-flex items-center gap-1 mt-2 px-2 py-1 text-[11px] font-medium rounded bg-[#fff8e1] text-[#b7791f] border border-[#f6e05e]">
                🔍 Sucht Betreuer
            </span>
            @endif
        </div>
        <span class="px-3 py-1 text-xs font-semibold rounded-full ml-4 whitespace-nowrap {{ $statusConfig['bg'] }}">
            {{ $statusConfig['label'] }}
        </span>
    </div>

    <p class="text-gray-500 text-sm mt-6 mb-6 flex-grow">
        {{ Str::limit($beschreibung, 80)}}
    </p>

    <div>
        {{-- Link zur Diskussionsseite --}}
        <a href="/projekt/{{ $projektId }}" class="text-[#0066cc] font-medium text-sm hover:underline">
            Details ansehen &rarr;
        </a>
    </div>
</div>