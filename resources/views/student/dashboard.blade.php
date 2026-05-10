@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto px-6 mt-8 mb-12">


    <div class="flex gap-12 items-start">

        
        <section class="flex-1">

            <!-- Obere Leiste: Tabs auf der linken Seite, Button auf der rechten Seite -->
            <div class="flex justify-between items-end border-b border-gray-300 mb-8 pb-3">
                
                <!-- Die Tabs -->
                <div class="flex gap-8">
                    <a href="/student/alle-ideen" 
                       class="font-bold text-lg transition-colors {{ request()->is('student/alle-ideen') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
                        Alle Ideen
                    </a>
                    
                    <a href="/student/meine-projekte" 
                       class="font-bold text-lg transition-colors {{ request()->is('student/meine-projekte') ? 'border-b-2 border-[#0066cc] text-[#0066cc]' : 'text-gray-400 hover:text-[#0066cc]' }}">
                        Meine Projekte
                    </a>
                </div>

                <!-- Der "+ Neue Idee" Button -->
                <a href="/student/neue-idee" class="bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-5 py-2 rounded-md font-bold transition-colors flex items-center gap-2 shadow-sm mb-1">
                    <span>+</span> Neue Idee
                </a>
            </div>

            <!-- Die Projektkarten im Grid -->
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