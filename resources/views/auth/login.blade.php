{{-- 
    Login Seite - View
    Erstellt von: Tayrit
    Beschreibung: Login UI mit Logo, Email & Passwort Feld
--}}

<x-guest-layout>
<div style="display:flex; min-height:100vh;">

    {{-- Linke Seite - Logo Bereich --}}
    <div style="background:#5b7fa6; width:40%; 
        display:flex; flex-direction:column;
        justify-content:center; align-items:center;
        padding:40px;">
        
        {{-- Logo Bild - wird nach Merge mit Taqwa verfügbar --}}
        <img src="{{ asset('images/logo.png') }}" 
            alt="UniProjekte Logo"
            style="width:150px; height:150px; 
            object-fit:contain;">
        
        {{-- Plattform Name --}}
        <h2 style="color:white; margin-top:20px; 
            font-size:22px; font-weight:bold;">
            UniProjekte
        </h2>
    </div>

    {{-- Rechte Seite - Login Formular --}}
    <div style="width:60%; display:flex; 
        justify-content:center; align-items:center; 
        background:white; padding:60px 40px;">
        <div style="width:100%; max-width:380px;">

            {{-- Begrüßungstext --}}
            <h1 style="font-size:26px; font-weight:bold; 
                color:#1a1a1a; margin-bottom:6px;">
                Willkommen zurück!
            </h1>
            <p style="color:#888; font-size:13px; 
                margin-bottom:32px;">
                Melden Sie sich bei Ihrem Konto an
            </p>

            {{-- Session Status (Fehlermeldungen) --}}
            <x-auth-session-status class="mb-4" 
                :status="session('status')" />

            {{-- Anmeldeformular --}}
            <form method="POST" 
                action="{{ route('login') }}">
                @csrf

                {{-- Email Eingabefeld --}}
                <div style="margin-bottom:20px;">
                    <label style="display:block; 
                        font-size:13px; color:#444; 
                        margin-bottom:6px;">
                        E-Mail
                    </label>
                    <input type="email" name="email"
                        placeholder="name@beispiel.de"
                        value="{{ old('email') }}"
                        required
                        style="width:100%; padding:10px 14px;
                        border:1px solid #ccc; border-radius:6px;
                        font-size:14px; outline:none;">
                    {{-- Email Fehlermeldung --}}
                    @error('email')
                        <p style="color:red; font-size:12px; 
                            margin-top:4px;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Passwort Eingabefeld --}}
                <div style="margin-bottom:28px;">
                    <label style="display:block; 
                        font-size:13px; color:#444; 
                        margin-bottom:6px;">
                        Passwort
                    </label>
                    <input type="password" 
                        name="password"
                        placeholder="••••••••••"
                        required
                        style="width:100%; padding:10px 14px;
                        border:1px solid #ccc; border-radius:6px;
                        font-size:14px; outline:none;">
                    {{-- Passwort Fehlermeldung --}}
                    @error('password')
                        <p style="color:red; font-size:12px; 
                            margin-top:4px;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Anmelden Button --}}
                <button type="submit"
                    style="width:100%; padding:12px;
                    background:#4a90d9; color:white;
                    border:none; border-radius:6px;
                    font-size:15px; cursor:pointer;">
                    Anmelden
                </button>

            </form>
        </div>
    </div>

</div>

{{-- Responsives Design für Tablet & Handy --}}
<style>
@media (max-width: 768px) {
    div[style*="display:flex; min-height"] {
        flex-direction: column !important;
    }
    div[style*="width:40%"] {
        width: 100% !important;
        padding: 30px !important;
        min-height: 200px !important;
    }
    div[style*="width:60%"] {
        width: 100% !important;
        padding: 30px 25px !important;
    }
}
</style>

</x-guest-layout>