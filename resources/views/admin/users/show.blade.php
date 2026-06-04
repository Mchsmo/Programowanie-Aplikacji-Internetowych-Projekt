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
                          color:#c62828;padding:.2em .7em;border-radius:3px;margin-left:.5em;font-weight:700;">
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
                    <h3><span class="icon solid fa-chart-bar"></span> Aktywność w serwisie</h3>
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

            <form method="POST" action="{{ route('admin.users.sync-roles', $user) }}" id="roles-form" style="margin:0;">
                @csrf
                <div style="display:flex;gap:.75em;flex-wrap:wrap;margin-bottom:1.5em;" id="roles-container">
                    @foreach ($allRoles as $role)
                        @php $checked = $user->roles->contains($role->id); @endphp
                        <button type="button"
                                data-role-id="{{ $role->id }}"
                                data-active="{{ $checked ? '1' : '0' }}"
                                onclick="toggleRole(this)"
                                style="
                                    cursor:pointer !important;
                                    padding:.6em 1.4em !important;
                                    border-radius:4px !important;
                                    height:auto !important;
                                    line-height:normal !important;
                                    margin:0 !important;
                                    border:2px solid {{ $checked ? '#c0392b' : '#bbb' }} !important;
                                    background:{{ $checked ? '#fdecea' : '#f5f5f5' }} !important;
                                    color:{{ $checked ? '#c0392b' : '#333333' }} !important;
                                    font-weight:{{ $checked ? '700' : '600' }} !important;
                                    font-size:.9em !important;
                                    letter-spacing:.04em !important;
                                    box-shadow:none !important;
                                    transition:all .15s ease-in-out;
                                ">
                            <span style="margin-right:.4em; font-weight:bold; color:inherit;">{{ $checked ? '✓' : '+' }}</span>
                            {{ strtoupper($role->name) }}
                        </button>
                        @if ($checked)
                            <input type="hidden" name="roles[]" value="{{ $role->id }}" id="role-input-{{ $role->id }}">
                        @else
                            <input type="hidden" name="roles[]" value="" id="role-input-{{ $role->id }}" disabled>
                        @endif
                    @endforeach
                </div>
                <button type="submit" class="button small icon solid fa-save" style="margin:0 !important; height:auto !important; padding: 0.75em 1.5em !important; line-height: normal !important;">
                    Zapisz uprawnienia ról
                </button>
            </form>
        </div>

        {{-- Przyciski akcji (dolny pasek) --}}
        <div style="display:flex; gap:1em; flex-wrap:wrap; margin-top:2.5em; align-items:center; border-top:1px solid #dee2e6; padding-top:1.5em;">

            @if ($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="margin:0 !important; display:inline-block;">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="button icon solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"
                            style="margin:0 !important; padding:0 1.5em !important; height:3em !important; line-height:3em !important; display:inline-flex !important; align-items:center;">
                        {{ $user->is_active ? 'Dezaktywuj konto' : 'Aktywuj konto' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin:0 !important; display:inline-block;">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="button icon solid fa-trash"
                            style="background:#c62828 !important; border-color:#c62828 !important; color:#fff !important; margin:0 !important; padding:0 1.5em !important; height:3em !important; line-height:3em !important; display:inline-flex !important; align-items:center;"
                            onclick="return confirm('Na pewno trwale usunąć konto użytkownika „{{ addslashes($user->name) }}”?')">
                        Usuń użytkownika
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.users.index') }}" class="button alt icon solid fa-arrow-left"
               style="margin-left:auto !important; margin-top:0 !important; margin-bottom:0 !important; margin-right:0 !important; padding:0 1.5em !important; height:3em !important; line-height:3em !important; display:inline-flex !important; align-items:center; justify-content:center; text-align:center;">
                Wróć do listy użytkowników
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
        btn.style.setProperty('border', '2px solid #bbb', 'important');
        btn.style.setProperty('background', '#f5f5f5', 'important');
        btn.style.setProperty('color', '#333333', 'important');
        btn.style.setProperty('font-weight', '600', 'important');
        btn.querySelector('span').textContent = '+';
        input.disabled            = true;
        input.value               = '';
    } else {
        btn.dataset.active        = '1';
        btn.style.setProperty('border', '2px solid #c0392b', 'important');
        btn.style.setProperty('background', '#fdecea', 'important');
        btn.style.setProperty('color', '#c0392b', 'important');
        btn.style.setProperty('font-weight', '700', 'important');
        btn.querySelector('span').textContent = '✓';
        input.disabled            = false;
        input.value               = id;
    }
}
</script>
@endpush
@endsection