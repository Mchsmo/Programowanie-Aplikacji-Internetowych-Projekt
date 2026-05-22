@extends('layouts.app')

@section('title', 'Rejestracja')

@push('styles')
<style>
    .auth-box {
        background: #ffffff;
        border: solid 2px #444444; 
        border-radius: 6px;
        padding: 3em 3em 2.5em;
        max-width: 650px; 
        margin: 0 auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .auth-box h2 { 
        text-align: center; 
        margin-bottom: 1.2em;
        letter-spacing: 2px;
        color: #111111 !important;
        font-weight: 700 !important;
    }

    .auth-box label {
        display: block;
        text-align: left;
        margin-bottom: 0.4em;
        font-weight: 600 !important;
        color: #222222 !important;
    }

    .auth-box input[type="text"],
    .auth-box input[type="email"],
    .auth-box input[type="password"] {
        margin-bottom: 1.2em;
        width: 100%;
        height: 3em !important;
        padding: 0 1em !important; 
        background: #ffffff !important;
        border: 2px solid #666666 !important; 
        border-radius: 4px !important;
        color: #111111 !important;
        font-size: 1em !important;
    }

    .auth-box input:focus {
        border-color: #ed786a !important;
        box-shadow: 0 0 5px rgba(237, 120, 106, 0.5) !important;
    }

    .auth-box .actions {
        display: flex;
        flex-direction: column;
        gap: 0.75em; 
        padding: 0;
        margin: 2em 0 0 0;
        list-style: none;
    }

    .auth-box .actions li {
        width: 100%;
        padding: 0 !important;
        margin: 0 !important; 
    }

    /* Stylizacja przycisków na dole formularza */
    .auth-box .actions li input[type="submit"],
    .auth-box .actions li .button {
        display: block;
        width: 100%;
        height: 3em !important;
        line-height: 3em !important;
        text-align: center;
        border-radius: 4px !important;
        font-weight: bold !important;
        text-decoration: none !important;
        padding: 0 !important;
    }

    .auth-box .actions li input[type="submit"] {
        background: #ed786a !important;
        color: #ffffff !important;
        border: none !important;
        cursor: pointer;
    }

    .auth-box .actions li input[type="submit"]:hover {
        background: #df5d4f !important;
    }

    .auth-box .actions li .button.alt {
        background: #f4f4f4 !important;
        color: #111111 !important;
        border: 2px solid #444444 !important;
    }

    .auth-box .actions li .button.alt:hover {
        background: #e2e2e2 !important;
    } 

    .field-error {
        color: #c0392b;
        font-size: 0.85em;
        display: block;
        margin-top: -0.8em;
        margin-bottom: 1em;
        text-align: left;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<section id="main">
    <div class="container">
        <div class="auth-box">

            <h2>Zarejestruj się</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row gtr-50">
                    <div class="col-6 col-12-small">
                        <label for="name">Imię / Nick</label>
                        <input id="name" type="text" name="name" 
                               value="{{ old('name') }}" 
                               placeholder="Twoje imię" 
                               required autofocus>
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 col-12-small">
                        <label for="email">Adres e-mail</label>
                        <input id="email" type="email" name="email" 
                               value="{{ old('email') }}" 
                               placeholder="twoj@email.pl" 
                               required>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 col-12-small">
                        <label for="password">Hasło</label>
                        <input id="password" type="password" name="password" 
                               placeholder="Min. 8 znaków" required>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-6 col-12-small">
                        <label for="password_confirmation">Powtórz hasło</label>
                        <input id="password_confirmation" type="password" 
                               name="password_confirmation" 
                               placeholder="Powtórz hasło" 
                               required>
                    </div>
                </div>

                <ul class="actions">
                    <li><input type="submit" value="Zarejestruj się"></li>
                    <li><a href="{{ route('login') }}" class="button alt">Mam już konto</a></li>
                </ul>
            </form>

        </div>
    </div>
</section>
@endsection