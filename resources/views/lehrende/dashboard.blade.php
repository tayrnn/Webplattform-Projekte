@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">
        Betreute Projekte
    </h1>

    @if($projekte->isEmpty())

        <div class="bg-white p-6 rounded-lg shadow">
            <p class="text-gray-600">
                Du betreust aktuell keine Projekte.
            </p>
        </div>

    @else

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($projekte as $projekt)

                <div class="bg-white rounded-xl shadow p-5 border">

                    <h2 class="text-xl font-semibold mb-3">
                        {{ $projekt->titel }}
                    </h2>

                    <p class="text-gray-600 mb-4">
                        {{ Str::limit($projekt->beschreibung, 120) }}
                    </p>

                    <div class="mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                            {{ $projekt->status }}
                        </span>
                    </div>

                    <a href="{{ route('projekte.details', $projekt->id) }}"
                       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Details ansehen
                    </a>

                </div>

            @endforeach

        </div>

    @endif

</div>

@endsection