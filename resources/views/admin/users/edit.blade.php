@extends('layouts.app')

@section('content')
<section class="wrapper style1">
    <div class="container" style="max-width:640px;">

        <header class="major">
            <h2><span class="icon solid fa-user-edit"></span> Edycja użytkownika</h2>
            <p>{{ $user->name }} &mdash; {{ $user->email }}</p>
        </header>

        @if ($errors->any())
            <div class="box special" style="background:#ffebee;border-left:4px solid #c62828;padding:1em 1.5em;margin-bottom:1.5em;">
                <ul style="margin:0;padding-left:1.2em;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="box">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')

                <div class="row gtr-uniform">
                    <div class="col-12">
                        <label for="name">Imię i nazwisko</label>
                        <input type="text" id="name" name="name"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Pełne imię i nazwisko" required>
                    </div>

                    <div class="col-12">
                        <label for="email">Adres e-mail</label>
                        <input type="email" id="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               placeholder="adres@domena.pl" required>
                    </div>

                    <div class="col-12">
                        <label style="display:flex;align-items:center;gap:.5em;cursor:pointer;">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1"
                                   @checked(old('is_active', $user->is_active))>
                            Konto aktywne
                        </label>
                    </div>

                    <div class="col-12" style="display:flex;gap:.75em;flex-wrap:wrap;margin-top:.5em;">
                        <button type="submit" class="button icon solid fa-save">
                            Zapisz zmiany
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="button alt icon solid fa-times">
                            Anuluj
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </div>
</section>
@endsection
