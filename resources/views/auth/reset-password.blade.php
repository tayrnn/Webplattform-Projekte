@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-20 mb-12 px-6">
    <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm">
        <h1 class="text-2xl font-bold text-[#1a202c] mb-1">Neues Passwort festlegen</h1>
        <p class="text-gray-500 text-sm mb-6">Lege dein persönliches Passwort für den Login fest.</p>

        @if($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $fehlermeldung)
                <li>{{ $fehlermeldung }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-5">
                <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    E-Mail
                </label>
                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                    required autofocus autocomplete="username">
            </div>

            <div class="mb-5" x-data="{ zeigen: false }">
                <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Passwort
                </label>
                <div class="relative">
                    <input :type="zeigen ? 'text' : 'password'" id="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                        required autocomplete="new-password">
                    <button type="button" @click="zeigen = !zeigen"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!zeigen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="zeigen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mb-6" x-data="{ zeigen: false }">
                <label for="password_confirmation"
                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                    Passwort bestätigen
                </label>
                <div class="relative">
                    <input :type="zeigen ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 text-sm text-gray-800 focus:outline-none focus:border-[#0066cc] focus:ring-2 focus:ring-blue-100"
                        required autocomplete="new-password">
                    <button type="button" @click="zeigen = !zeigen"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg x-show="!zeigen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="zeigen" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-[#6ba9dc] hover:bg-[#5a91c4] text-white px-6 py-3 rounded-md font-bold transition-colors shadow-sm">
                Passwort festlegen →
            </button>
        </form>
    </div>
</div>
@endsection