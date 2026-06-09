<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @stack('styles')
</head>
<body class="is-preload">
<div id="page-wrapper">

    <!-- ===== HEADER ===== -->
    <header id="header">
        <div class="container">
            <nav id="nav">
                <ul>
                    @guest
                    <li @class(['active' => request()->routeIs('login')])>
                        <a href="{{ route('login') }}"><span class="icon solid fa-lock"></span> <span>Logowanie</span></a>
                    </li>
                    <li @class(['active' => request()->routeIs('register')])>
                        <a href="{{ route('register') }}"><span class="icon solid fa-user-plus"></span> <span>Rejestracja</span></a>
                    </li>
                    @endguest

                    @auth
                    <li @class(['active' => request()->routeIs('dashboard')])>
                        <a href="{{ route('dashboard') }}"><span class="icon solid fa-user"></span> <span>Konto</span></a>
                    </li>
                    <li @class(['active' => request()->routeIs('recipes.*')])>
                        <a href="#"><span class="icon solid fa-book-open"></span> <span>Przepisy</span></a>
                        <ul>
                            <li><a href="{{ route('recipes.index') }}">Przeglądaj wszystkie</a></li>
                            <li><a href="{{ route('recipes.my-recipes') }}">Moje przepisy</a></li>
                            <li><a href="{{ route('recipes.favorites') }}">Ulubione</a></li>
                            <li><a href="{{ route('recipes.create') }}">Dodaj nowy przepis</a></li>
                        </ul>
                    </li>

                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator'))
                    <li @class(['active' => request()->routeIs('admin.*') || request()->routeIs('moderation.*')])>
                        <a href="#"><span class="icon solid fa-cog"></span> <span>Panel Zarządzania</span></a>
                        <ul>
                            @if (auth()->user()->hasRole('admin'))
                                <li>
                                    <a href="{{ route('admin.users.index') }}">Użytkownicy</a>
                                </li>
                            @endif
                            @if (auth()->user()->hasRole('moderator'))
                                <li>
                                    <a href="{{ route('moderation.index') }}">Moderacja treści</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none">
                            @csrf
                        </form>
                        <a href="#" onclick="document.getElementById('logout-form').submit(); return false;">
                            <span class="icon solid fa-sign-out-alt"></span> <span>Wyloguj</span>
                        </a>
                    </li>
                    @endauth
                </ul>
            </nav>

        </div>
    </header>

    <!-- ===== TREŚĆ STRONY ===== -->
    @yield('content')

    <!-- ===== FOOTER ===== -->
    <footer id="footer">
        <div class="container">
            <div id="copyright">
                <ul class="links">
                    <li>Szablon: <a href="https://html5up.net/strongly-typed" target="_blank">Strongly Typed</a> by HTML5 UP</li>
                </ul>
            </div>
        </div>
    </footer>

</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.dropotron.min.js') }}"></script>
<script src="{{ asset('js/browser.min.js') }}"></script>
<script src="{{ asset('js/breakpoints.min.js') }}"></script>
<script src="{{ asset('js/util.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@stack('scripts')
</body>
</html>