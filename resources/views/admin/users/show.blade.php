@extends('layouts.app')

@section('content')
<section class="wrapper style1">
    <div class="container">

        <header class="major">
            <h2>
                <span class="icon solid fa-user"></span>
                {{ $user->name }}
                @if (!$user->is_active)
                    <span style="font-size:.55em;vertical-align:middle;background:#ffcdd2;
                          color:#c62828;padding:.2em .7em;border-radius:3px;margin-left:.5em;">
                        Nieaktywny
                    </span>
                @endif
            </h2>
            <p>{{ $user->email }}</p>
        </header>

        {{-- Komunikaty --}}
        @if (session('success'))
            <div class="box special" style="background:#e8f5e9;border-left:4px solid #2e7d32;padding:1em 1.5em;margin-bottom:1.5em;">
                <span class="icon solid fa-check-circle" style="color:#2e7d32;margin-right:.5em;"></span>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="box special" style="background:#ffebee;border-left:4px solid #c62828;padding:1em 1.5em;margin-bottom:1.5em;">
                <span class="icon solid fa-exclamation-circle" style="color:#c62828;margin-right:.5em;"></span>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">

            {{-- Informacje podstawowe --}}
            <div class="col-6 col-12-medium">
                <div class="box">
                    <h3><span class="icon solid fa-info-circle"></span> Dane konta</h3>
                    <table>
                        <tr><th style="width:40%">ID</th><td>{{ $user->id }}</td></tr>
                        <tr><th>Imię i nazwisko</th><td>{{ $user->name }}</td></tr>
                        <tr><th>E-mail</th><td>{{ $user->email }}</td></tr>
                        <tr><th>Status</th>
                            <td>
                                @if ($user->is_active)
                                    <span style="color:#2e7d32;font-weight:600;">Aktywny</span>
                                @else
                                    <span style="color:#c62828;font-weight:600;">Nieaktywny</span>
                                @endif
                            </td>
                        </tr>
                        <tr><th>Zarejestrowany</th><td>{{ $user->created_at->format('d.m.Y H:i') }}</td></tr>
                        <tr><th>Ostatnia modyfikacja</th><td>{{ $user->updated_at->format('d.m.Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- Statystyki --}}
            <div class="col-6 col-12-medium">
                <div class="box">
                    <h3><span class="icon solid fa-chart-bar"></span> Aktywność</h3>
                    <table>
                        <tr>
                            <th><span class="icon solid fa-book-open"></span> Przepisy</th>
                            <td>{{ $user->recipes->count() }}</td>
                        </tr>
                        <tr>
                            <th><span class="icon solid fa-heart"></span> Ulubione</th>
                            <td>{{ $user->favorites->count() }}</td>
                        </tr>
                        <tr>
                            <th><span class="icon solid fa-star"></span> Oceny</th>
                            <td>{{ $user->ratings->count() }}</td>
                        </tr>
                        <tr>
                            <th><span class="icon solid fa-comment"></span> Komentarze</th>
                            <td>{{ $user->comments->count() }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

        {{-- Zarządzanie rolami --}}
        <div class="box">
            <h3><span class="icon solid fa-shield-alt"></span> Role użytkownika</h3>

            <form method="POST" action="{{ route('admin.users.sync-roles', $user) }}" id="roles-form">
                @csrf
                <div style="display:flex;gap:.75em;flex-wrap:wrap;margin-bottom:1.25em;" id="roles-container">
                    @foreach ($allRoles as $role)
                        @php $checked = $user->roles->contains($role->id); @endphp
                        <button type="button"
                                data-role-id="{{ $role->id }}"
                                data-active="{{ $checked ? '1' : '0' }}"
                                onclick="toggleRole(this)"
                                style="
                                    cursor:pointer;
                                    padding:.55em 1.2em;
                                    border-radius:4px;
                                    border:2px solid {{ $checked ? '#c0392b' : '#bbb' }};
                                    background:{{ $checked ? '#fdecea' : '#f5f5f5' }};
                                    color:{{ $checked ? '#c0392b' : '#666' }};
                                    font-weight:{{ $checked ? '700' : '400' }};
                                    font-size:.9em;
                                    letter-spacing:.04em;
                                    transition:all .15s;
                                ">
                            <span style="margin-right:.4em;">{{ $checked ? '✓' : '+' }}</span>
                            {{ strtoupper($role->name) }}
                        </button>
                        @if ($checked)
                            <input type="hidden" name="roles[]" value="{{ $role->id }}" id="role-input-{{ $role->id }}">
                        @else
                            <input type="hidden" name="roles[]" value="" id="role-input-{{ $role->id }}" disabled>
                        @endif
                    @endforeach
                </div>
                <button type="submit" class="button small icon solid fa-save">
                    Zapisz role
                </button>
            </form>
        </div>

        {{-- Przyciski akcji --}}
        <div style="display:flex;gap:.75em;flex-wrap:wrap;margin-top:1.5em;align-items:center;">

            <a href="{{ route('admin.users.edit', $user) }}" class="button icon solid fa-edit">
                Edytuj dane
            </a>

            @if ($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="margin:0;">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="button icon solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"
                            onclick="return confirm('{{ $user->is_active ? 'Dezaktywować' : 'Aktywować' }} użytkownika?')">
                        {{ $user->is_active ? 'Dezaktywuj' : 'Aktywuj' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin:0;">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="button icon solid fa-trash"
                            style="background:#c62828;border-color:#c62828;"
                            onclick="return confirm('Na pewno usunąć konto „{{ addslashes($user->name) }}"?')">
                        Usuń konto
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.users.index') }}" class="button alt icon solid fa-arrow-left"
               style="margin-left:auto;">
                Wróć do listy
            </a>

        </div>

    </div>
</section>
@push('scripts')
<script>
function toggleRole(btn) {
    const id      = btn.dataset.roleId;
    const isActive = btn.dataset.active === '1';
    const input   = document.getElementById('role-input-' + id);

    if (isActive) {
        btn.dataset.active        = '0';
        btn.style.border          = '2px solid #bbb';
        btn.style.background      = '#f5f5f5';
        btn.style.color           = '#666';
        btn.style.fontWeight      = '400';
        btn.querySelector('span').textContent = '+';
        input.disabled            = true;
        input.value               = '';
    } else {
        btn.dataset.active        = '1';
        btn.style.border          = '2px solid #c0392b';
        btn.style.background      = '#fdecea';
        btn.style.color           = '#c0392b';
        btn.style.fontWeight      = '700';
        btn.querySelector('span').textContent = '✓';
        input.disabled            = false;
        input.value               = id;
    }
}
</script>
@endpush

@endsection