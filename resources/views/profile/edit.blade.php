@extends('layouts.app')

@section('title', 'Edytuj profil')

@push('styles')
<style>
    .avatar-circle {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: #ed786a;
        color: #fff;
        font-family: 'Arvo', serif;
        font-size: 2rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1em;
        box-shadow: 0.125em 0.175em 0 0 rgba(0,0,0,0.125);
    }
 
    .profile-tabs {
        display: flex;
        margin-bottom: 2em;
        border-bottom: solid 2px #e5e5e5;
    }
 
    .profile-tabs .profile-tab {
        padding: 0.6em 1.4em;
        font-weight: 600;
        font-size: 0.85em;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        border: none !important;
        border-bottom: solid 3px transparent !important;
        border-radius: 0 !important;
        background: none !important;
        background-color: transparent !important;
        box-shadow: none !important;
        color: #aaa !important;         
        margin-bottom: -2px;
        transition: color 0.15s, border-color 0.15s;
        font-family: inherit;
        line-height: inherit;
        height: auto;
    }
 
    .profile-tabs .profile-tab.active {
        color: #ed786a !important;
        border-bottom-color: #ed786a !important;
    }
 
    .profile-tabs .profile-tab:hover:not(.active) {
        color: #555 !important;
        background: none !important;
        box-shadow: none !important;
    }
 
    .tab-panel        { display: none; }
    .tab-panel.active { display: block; }
 
    .alert-success {
        background: #f0fdf4;
        border-left: solid 4px #aed6a0;
        padding: 0.8em 1.2em;
        margin-bottom: 1.5em;
        color: #3a7d44;
        font-weight: 600;
    }
 
    .alert-error {
        background: #fff5f5;
        border-left: solid 4px #ed786a;
        padding: 0.8em 1.2em;
        margin-bottom: 1.5em;
        color: #c0392b;
        font-weight: 600;
    }
 
    .field-error {
        color: #c0392b;
        font-size: 0.82em;
        margin-top: 0.3em;
        display: block;
    }
</style>
@endpush

@section('content')
<section id="main">
    <div class="container">
        <div class="row gtr-150">

            <!-- Główna treść -->
            <div class="col-8 col-12-medium" id="content">
                <article>
                    <header>
                        <h2>Edytuj profil</h2>
                        <p>Zmień swoje dane lub zaktualizuj hasło.</p>
                    </header>

                    <div style="text-align:center; margin-bottom:2em;">
                        <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <h3 style="text-align:center; font-size:1.2em; margin-bottom:0.2em;">{{ $user->name }}</h3>
                        <p style="text-align:center; margin-bottom:0;">{{ $user->email }}</p>
                    </div>

                    <!-- Zakładki -->
                    <div class="profile-tabs">
                        <button class="profile-tab {{ session('tab') !== 'password' ? 'active' : '' }}"
                                onclick="switchTab('info', this)">
                            Dane konta
                        </button>
                        <button class="profile-tab {{ session('tab') === 'password' ? 'active' : '' }}"
                                onclick="switchTab('password', this)">
                            Zmiana hasła
                        </button>
                    </div>

                    <!-- Panel: Dane konta -->
                    <div class="tab-panel {{ session('tab') !== 'password' ? 'active' : '' }}" id="tab-info">

                        @if(session('success') && session('tab') !== 'password')
                            <div class="alert-success">✓ {{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="row gtr-uniform">
                                <div class="col-12">
                                    <label for="name">Nazwa użytkownika</label>
                                    <input type="text" id="name" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           maxlength="50" required>
                                    @error('name')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="email">Adres e-mail</label>
                                    <input type="email" id="email" name="email"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <ul class="actions">
                                        <li><input type="submit" value="Zapisz zmiany"></li>
                                        <li><a href="{{ route('dashboard') }}" class="button alt">← Wróć</a></li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Panel: Zmiana hasła -->
                    <div class="tab-panel {{ session('tab') === 'password' ? 'active' : '' }}" id="tab-password">

                        @if(session('success') && session('tab') === 'password')
                            <div class="alert-success">✓ {{ session('success') }}</div>
                        @endif

                        @if($errors->has('current_password'))
                            <div class="alert-error">✗ {{ $errors->first('current_password') }}</div>
                        @endif

                        <form method="POST" action="{{ route('profile.password') }}">
                            @csrf
                            @method('PATCH')

                            <div class="row gtr-uniform">
                                <div class="col-12">
                                    <label for="current_password">Obecne hasło</label>
                                    <input type="password" id="current_password"
                                           name="current_password" required
                                           autocomplete="current-password">
                                </div>

                                <div class="col-12">
                                    <hr style="border:none; border-top:1px solid #e5e5e5; margin:0.5em 0;">
                                </div>

                                <div class="col-12">
                                    <label for="password">Nowe hasło</label>
                                    <input type="password" id="password" name="password"
                                           required autocomplete="new-password"
                                           oninput="checkStrength(this.value)">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strength-fill"></div>
                                    </div>
                                    <span class="strength-label" id="strength-label"></span>
                                    @error('password')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password_confirmation">Powtórz nowe hasło</label>
                                    <input type="password" id="password_confirmation"
                                           name="password_confirmation" required
                                           autocomplete="new-password">
                                    <small style="color:#aaa;">Minimum 8 znaków</small>
                                </div>

                                <div class="col-12">
                                    <ul class="actions">
                                        <li><input type="submit" value="Zmień hasło"></li>
                                        <li><a href="{{ route('dashboard') }}" class="button alt">← Wróć</a></li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>

                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-4 col-12-medium" id="sidebar">

                <section>
                    <h3>Informacje o koncie</h3>
                    <ul class="icons">
                        <li class="icon solid fa-envelope"> {{ $user->email }}</li>
                        <li class="icon solid fa-calendar"> Dołączył(a) {{ $user->created_at->format('d.m.Y') }}</li>
                        <li class="icon solid fa-shield-alt">
                            {{ $user->roles->count() }}
                            {{ $user->roles->count() === 1 ? 'rola' : 'role' }}
                        </li>
                    </ul>
                </section>

                <section>
                    <h3>Nawigacja</h3>
                    <ul class="divided">
                        <li><a href="{{ route('dashboard') }}">← Panel użytkownika</a></li>
                        <li><a href="#">Moje przepisy</a></li>
                        <li><a href="#">Ulubione</a></li>
                        <li><a href="#">Przeglądaj wszystkie</a></li>
                    </ul>
                </section>

            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function switchTab(tab, btn) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.profile-tab').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        btn.classList.add('active');
    }
</script>
@endpush