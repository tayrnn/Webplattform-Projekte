@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">


    <div class="flex gap-12 items-start">

        <section class="flex-1">

     <div class="flex gap-8 border-b border-gray-300 mb-8">
    
    <!-- Tab 1: Alle Ideen -->
    <a href="/lehrende/alle-ideen" 
       class="pb-3 font-bold text-lg transition-colors 
              {{ request()->is('lehrende/alle-ideen') || request()->is('lehrende') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
        Alle Ideen
    </a>
    
    <!-- Tab 2: Betreute Projekte -->
    <a href="/lehrende/betreute-projekte" 
       class="pb-3 font-bold text-lg transition-colors 
              {{ request()->is('lehrende/betreute-projekte') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
        Betreute Projekte
    </a>

</div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <x-project-card
                    title="Erstes Projekt"
                    status="offen"
                    :needsSupervision="true"
                />

                <x-project-card
                    title="Zweites Projekt"
                    status="In Bearbeitung"
                    :needsSupervision="false"
                />

                <x-project-card
                    title="Drittes Projekt"
                    status="Abgeschlossen"
                    :needsSupervision="true"
                />
                
            </div>

        </section>
    </div>
</div>
@endsection