@extends('layouts.app')

@section('title', 'Panel użytkownika')

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
    .role-badge {
        display: inline-block;
        font-size: 0.75em; letter-spacing: 2px; text-transform: uppercase;
        border-radius: 4px; padding: 0.3em 1em; margin: 0.2em;
        box-shadow: 0.125em 0.175em 0 0 rgba(0,0,0,0.125);
        font-weight: 600;
    }
    .role-badge.admin     { background: #ed786a; color: #fff; }
    .role-badge.moderator { background: #878787; color: #fff; }
    .role-badge.default   { background: #e8e8e8; color: #777; }
    .roles-wrap { margin-bottom: 2em; }
    .role-panel { border-left: solid 4px #e5e5e5; padding: 1em 0 1em 1.5em; margin-bottom: 1.5em; }
    .role-panel.admin     { border-color: #ed786a; }
    .role-panel.moderator { border-color: #878787; }
    .role-panel.user      { border-color: #aed6a0; }
    .role-panel h3 { margin-bottom: 0.4em; }
    .role-panel p  { margin-bottom: 0; text-align: left; }
    ul.actions { text-align: center; }
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
                        <h2>Twój profil</h2>
                        <p>Zarządzaj swoim kontem i sprawdź swoje uprawnienia.</p>
                    </header>

                    <div style="text-align:center; margin-bottom:2em;">
                        <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        <h3 style="text-align:center; font-size:1.4em;">{{ $user->name }}</h3>
                        <p style="text-align:center; margin-bottom:0.5em;">{{ $user->email }}</p>
                    </div>

                    <h3>Twoje role</h3>
                    <div class="roles-wrap">
                        @forelse ($user->roles as $role)
                            @php
                                $cls = match($role->name) {
                                    'admin'     => 'admin',
                                    'moderator' => 'moderator',
                                    default     => 'default',
                                };
                            @endphp
                            <span class="role-badge {{ $cls }}">{{ ucfirst($role->name) }}</span>
                        @empty
                            <span class="role-badge default">Użytkownik</span>
                        @endforelse
                    </div>

                    @if ($user->hasRole('admin'))
                    <div class="role-panel admin">
                        <h3>Panel Administratora</h3>
                        <p>Masz pełny dostęp do systemu - zarządzasz użytkownikami, rolami i całą aplikacją.</p>
                    </div>
                    @endif

                    @if ($user->hasRole('moderator'))
                    <div class="role-panel moderator">
                        <h3>Panel Moderatora</h3>
                        <p>Możesz moderować treści i zarządzać zgłoszeniami użytkowników.</p>
                    </div>
                    @endif

                    @if ($user->hasRole('użytkownik'))
                    <div class="role-panel user">
                        <h3>Panel Użytkownika</h3>
                        <p>Przeglądaj i dodawaj przepisy oraz zarządzaj swoim profilem.</p>
                    </div>
                    @endif

                    <ul class="actions">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <input type="submit" value="Wyloguj się" class="alt icon solid fa-sign-out-alt">
                            </form>
                        </li>
                        <li><a href="{{ route('profile.edit') }}" class="button">Edytuj profil</a></li>
                    </ul>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-4 col-12-medium" id="sidebar">

                <section>
                    <h3>Szybkie linki</h3>
                    <ul class="divided">
                        <li><a href="#">Moje przepisy</a></li>
                        <li><a href="#">Ulubione</a></li>
                        <li><a href="#">Przeglądaj wszystkie</a></li>
                        <li><a href="#">Dodaj przepis</a></li>
                    </ul>
                </section>

                <section>
                    <h3>Informacje o koncie</h3>
                    <ul class="icons">
                        <li class="icon solid fa-envelope"> {{ $user->email }}</li>
                        <li class="icon solid fa-calendar"> Dołączył(a) {{ $user->created_at->format('d.m.Y') }}</li>
                        <li class="icon solid fa-shield-alt"> {{ $user->roles->count() }} {{ $user->roles->count() === 1 ? 'rola' : 'role' }}</li>
                    </ul>
                </section>

            </div>

        </div>
    </div>
</section>
@endsection