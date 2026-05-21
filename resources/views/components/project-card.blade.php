@props(['title', 'status','beschreibung' => '', 'needsSupervision' => false, 'id'])

@php
   
    // Die Farbe ändert sich automatisch, je nachdem welcher Text übergeben wird.
    $statusColor = match($status) {
        'Abgeschlossen' => 'bg-gray-400 text-gray-900',
        'In Bearbeitung' => 'bg-blue-500 text-white',
        'offen' => 'bg-[#8dc63f] text-gray-900', // Grün
        default => 'bg-gray-200 text-gray-800',
    };
@endphp

<!-- Das Design vom 1. Code (weiße Karte, Rahmen, Schatten) -->
<div class="bg-white p-6 rounded-xl border border-gray-200 flex flex-col h-full shadow-sm hover:shadow-md transition-shadow">
    
    <!-- Kopfbereich -->
    <div class="flex justify-between items-start">
        
        <!-- Linke Seite: Titel und Label -->
        <div class="flex flex-col items-start">
            <h3 class="text-xl font-bold text-[#1a202c]">{{ $title }}</h3>
            
            @if($needsSupervision)
                <!-- Das gelbe "Sucht Betreuer" Label  -->
                <span class="inline-flex items-center gap-1 mt-2 px-2 py-1 text-[11px] font-medium rounded bg-[#fff8e1] text-[#b7791f] border border-[#f6e05e]">
                    🔍 Sucht Betreuer
                </span>
            @endif
        </div>
        
        <!-- Rechte Seite: Status -->
        <span class="px-3 py-1 text-xs font-bold uppercase rounded-full ml-4 whitespace-nowrap {{ $statusColor }}">
            {{ $status }}
        </span>
    </div>

    <!-- Text-Bereich -->
    <p class="text-gray-500 text-sm mt-6 mb-6 flex-grow">
        {{ Str::limit($beschreibung, 80)}}
    </p>

    <!-- Link am Boden -->
    <div>
      <a href="{{ route('projekte.details', $id) }}" class="text-cyan-600 font-medium text-sm hover:underline">
    Details ansehen &rarr;
</a>
    </div>
</div>