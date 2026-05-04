@extends('layouts.app')

@section('title', 'Rejestracja')

@push('styles')
<style>
    .auth-box {
        background: #fff;
        border: solid 2px #e5e5e5;
        border-radius: 4px;
        padding: 3em 3em 2.5em;
        max-width: 500px;
        margin: 0 auto;
    }
    
    .auth-box h2 { 
        text-align: center; 
        margin-bottom: 1.5em;
        letter-spacing: 2px;
    }

    .auth-box label {
        display: block;
        text-align: left;
        margin-bottom: 0.5em;
        font-weight: bold;
    }

    .auth-box input[type="email"],
    .auth-box input[type="password"] {
        margin-bottom: 1.5em;
        width: 100%;
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

    .auth-box .actions li input[type="submit"],
    .auth-box .actions li .button {
        width: 100% !important;
        display: block;
        height: 3.5em;       
        line-height: 3.5em;  
        padding: 0 !important;
        text-align: center;
        border-radius: 4px;
        font-weight: bold;
        text-decoration: none;
    }
</style>
@endpush

@section('content')
<section id="main">
    <div class="container">
        <div class="auth-box">

            <h2>Utwórz konto</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row gtr-uniform">
                    <div class="col-12">
                        <label for="name">Nazwa użytkownika</label>
                        <input id="name" type="text" name="name"
                               value="{{ old('name') }}"
                               required autofocus maxlength="50">
                        @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12">
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
                               placeholder="Min. 8 znaków"
                               required>
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
                    <li><input type="submit" value="Zarejestruj się" class="icon solid fa-user-plus"></li>
                    <li><a href="{{ route('login') }}" class="button alt">Mam już konto</a></li>
                </ul>
            </form>

        </div>
    </div>
</section>
@endsection