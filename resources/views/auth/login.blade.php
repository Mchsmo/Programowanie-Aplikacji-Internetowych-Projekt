@extends('layouts.app')

@section('title', 'Logowanie')

@push('styles')
<style>
    .auth-box {
        background: #ffffff;
        border: solid 2px #444444;
        border-radius: 6px;
        padding: 3em 3em 2.5em;
        max-width: 500px;
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

    .auth-box input[type="email"],
    .auth-box input[type="password"] {
        margin-bottom: 1.5em;
        width: 100%;
        height: 3em !important; 
        padding: 0 1em !important; 
        background: #ffffff !important;
        border: 2px solid #666666 !important;
        border-radius: 4px !important;
        color: #111111 !important;
        font-size: 1em !important;
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

    .auth-box .actions li input[type="submit"].primary {
        background: #ed786a !important;
        color: #ffffff !important;
        border: none !important;
    }

    .auth-box .actions li .button.alt {
        background: #f4f4f4 !important;
        color: #111111 !important;
        border: 2px solid #444444 !important;
    }

    .error-list {
        background: #f8d7da;
        color: #721c24;
        padding: 1em 2em;
        border-radius: 4px;
        margin-bottom: 1.5em;
        list-style-type: disc;
        text-align: left;
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
            <form method="POST" action="{{ route('login') }}\">                
            @csrf               
                <div class="field-wrap ">                    
                    <label for="email">Adres e-mail</label>                 
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="twoj@email.pl" required autofocus>    
                    <label for="password">Hasło</label> 
                    <input id="password" type="password" name="password" placeholder="••••••••" required>       
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