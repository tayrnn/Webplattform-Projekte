{{--
    Login Seite - View
    Erstellt von: Tayrit
    Beschreibung: Login UI mit Logo, Email & Passwort Feld
--}}
<x-guest-layout>

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        margin: 0;
    }
    </style>

    <div style="display:flex; min-height:100vh; width:100%; overflow:hidden;">

        {{-- Linke Seite - Logo Bereich --}}
        <div style="background:#87afc7; width:40%; 
        display:flex; flex-direction:column;
        justify-content:center; align-items:center;
        padding:40px;">

            {{-- Logo Bild --}}
            <img src="{{ asset('images/logo.png') }}" alt="UniProjekte Logo" style="width:150px; height:150px; 
            object-fit:contain;">

            {{-- Plattform Name --}}
            <h2 style="margin-top:20px; font-size:40px; font-weight:bold; 
    letter-spacing:-0.025em; display:flex; align-items:center; gap:4px;">
                <span style="color:#0066cc;">Uni</span>
                <span style="color:#1a1a4b;">Projekte</span>
            </h2>

        </div>

        {{-- Rechte Seite - Login Formular --}}
        <div style="width:60%; display:flex; 
        justify-content:center; align-items:center; 
        background:white; padding:60px 40px;">
            <div style="width:100%; max-width:380px;">

                {{-- Begrüßungstext --}}
                <h1 style="font-size:26px; font-weight:bold; 
            color:#0066cc; margin-bottom:6px;
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            letter-spacing:-0.025em;">
                    Willkommen zurück!
                </h1>
                <p style="color:#888; font-size:13px; 
                margin-bottom:32px;">
                    Melden Sie sich bei Ihrem Konto an
                </p>

                {{-- Session Status (Fehlermeldungen) --}}
                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- Anmeldeformular --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email Eingabefeld --}}
                    <div style="margin-bottom:20px;">
                        <label style="display:block; 
                        font-size:13px; color:#444; 
                        margin-bottom:6px;">
                            E-Mail
                        </label>
                        <input type="email" name="email" placeholder="name@beispiel.de" value="{{ old('email') }}"
                            required style="width:100%; padding:10px 14px;
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
                        <div style="position:relative;">
                            <input type="password" name="password" id="passwortFeld" placeholder="••••••••••" required
                                style="width:100%; padding:10px 40px 10px 14px;
        border:1px solid #ccc; border-radius:6px;
        font-size:14px; outline:none; box-sizing:border-box;">
                            <span onclick="passwortAnzeigen()" style="position:absolute; right:12px; top:50%;
        transform:translateY(-50%); cursor:pointer;
        user-select:none; color:#888;">
                                <svg id="augeAuf" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg id="augeZu" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </span>
                        </div>
                        {{-- Passwort Fehlermeldung --}}
                        @error('password')
                        <p style="color:red; font-size:12px; 
        margin-top:4px;">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Anmelden Button --}}
                    <button type="submit" style="width:100%; padding:12px;
                    background:#6ba9dc; color:white;
                    border:none; border-radius:6px;
                    font-size:15px; cursor:pointer;">
                        Anmelden
                    </button>

                </form>
            </div>
        </div>

    </div>

    <script>
    function passwortAnzeigen() {
        const feld = document.getElementById('passwortFeld');
        const augeAuf = document.getElementById('augeAuf');
        const augeZu = document.getElementById('augeZu');

        if (feld.type === 'password') {
            feld.type = 'text';
            augeAuf.style.display = 'none';
            augeZu.style.display = 'block';
        } else {
            feld.type = 'password';
            augeAuf.style.display = 'block';
            augeZu.style.display = 'none';
        }
    }
    </script>

    {{-- Responsives Design für Tablet & Handy --}}
    <style>
    @media (max-width: 600px) {
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