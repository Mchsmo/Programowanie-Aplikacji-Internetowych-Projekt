@extends('layouts.app')

@section('title', 'Logowanie')

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

            <h2>Zaloguj się</h2>

            @if ($errors->any())
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-wrap">
                    <label for="email">Adres e-mail</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="twoj@email.pl"
                           required autofocus>
                    
                    <label for="password">Hasło</label>
                    <input id="password" type="password" name="password"
                           placeholder="••••••••"
                           required>
                </div>

                <ul class="actions">
                    <li><input type="submit" value="Zaloguj się" class="primary"></li>
                    <li><a href="{{ route('register') }}" class="button alt">Zarejestruj się</a></li>
                </ul>
            </form>

        </div>
    </div>
</section>
@endsection