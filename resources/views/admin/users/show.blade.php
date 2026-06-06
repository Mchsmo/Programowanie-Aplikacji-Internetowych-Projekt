@extends('layouts.app')

@push('styles')
<style>
    /* Statusy i Odznaki */
    .badge {
        font-size: .55em;
        vertical-align: middle;
        padding: .2em .7em;
        border-radius: 3px;
        margin-left: .5em;
        font-weight: 700;
    }
    .badge-inactive {
        background: #ffcdd2;
        color: #c62828;
    }
    .status-active {
        color: #2e7d32;
        font-weight: 600;
    }
    .status-inactive {
        color: #c62828;
        font-weight: 600;
    }

    /* Komunikaty / Alerty */
    .alert-box {
        padding: 1em 1.5em;
        margin-bottom: 1.5em;
    }
    .alert-success {
        background: #e8f5e9;
        border-left: 4px solid #2e7d32;
    }
    .alert-success .alert-icon {
        color: #2e7d32;
        margin-right: .5em;
    }
    .alert-danger {
        background: #ffebee;
        border-left: 4px solid #c62828;
    }
    .alert-danger .alert-icon {
        color: #c62828;
        margin-right: .5em;
    }

    /* Sekcja Ról */
    .roles-container {
        display: flex;
        gap: .75em;
        flex-wrap: wrap;
        margin-bottom: 1.5em;
    }
    .role-btn {    
        /* Domyślny stan nieaktywny */
        border: 2px solid #bbb !important;
        background: #f5f5f5 !important;
        color: #333333 !important;
        font-weight: 600 !important;
    }
    .role-btn.active {
        /* Stan aktywny */
        border: 2px solid #c0392b !important;
        background: #fdecea !important;
        color: #c0392b !important;
        font-weight: 700 !important;
    }
    .role-btn-icon {
        margin-right: .4em;
        font-weight: bold;
        color: inherit;
    }

    /* Dolny pasek akcji */
    .action-bar {
        display: flex;
        gap: 1em;
        flex-wrap: wrap;
        margin-top: 2.5em;
        align-items: center;
        border-top: 1px solid #dee2e6;
        padding-top: 1.5em;
    }
    .flex-btn {
        margin: 0 !important;
        padding: 0 1.5em !important;
        height: 3em !important;
        line-height: 3em !important;
        display: inline-flex !important;
        align-items: center;
    }
    .btn-danger {
        background: #c62828 !important;
        border-color: #c62828 !important;
        color: #fff !important;
    }
    .btn-back {
        margin-left: auto !important;
        justify-content: center;
        text-align: center;
    }
</style>
@endpush

@section('content')
<section class="wrapper style1">
    <div class="container">

        <header class="major">
            <h2>
                <span class="icon solid fa-user"></span>
                {{ $user->name }}
                @if (!$user->is_active)
                    <span class="badge badge-inactive">Nieaktywny</span>
                @endif
            </h2>
            <p>{{ $user->email }}</p>
        </header>

        {{-- Komunikaty --}}
        @if (session('success'))
            <div class="box special alert-box alert-success">
                <span class="icon solid fa-check-circle alert-icon"></span>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="box special alert-box alert-danger">
                <span class="icon solid fa-exclamation-circle alert-icon"></span>
                {{ session('error') }}
            </div>
        @endif

        <div class="row">

            {{-- Informacje podstawowe --}}
            <div class="col-6 col-12-medium">
                <div class="box">
                    <h3><span class="icon solid fa-info-circle"></span> Dane konta</h3>
                    <table class="user-info-table">
                        <tr><th>ID</th><td>{{ $user->id }}</td></tr>
                        <tr><th>Imię i nazwisko</th><td>{{ $user->name }}</td></tr>
                        <tr><th>E-mail</th><td>{{ $user->email }}</td></tr>
                        <tr><th>Status</th>
                            <td>
                                @if ($user->is_active)
                                    <span class="status-active">Aktywny</span>
                                @else
                                    <span class="status-inactive">Nieaktywny</span>
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

            <form method="POST" action="{{ route('admin.users.sync-roles', $user) }}" class="form-no-margin">
                @csrf
                <div class="roles-container">
                    @foreach ($allRoles as $role)
                        @php $checked = $user->roles->contains($role->id); @endphp
                        <button type="button"
                                data-role-id="{{ $role->id }}"
                                data-active="{{ $checked ? '1' : '0' }}"
                                onclick="toggleRole(this)"
                                class="role-btn {{ $checked ? 'active' : '' }}">
                            <span class="role-btn-icon">{{ $checked ? '✓' : '+' }}</span>
                            {{ strtoupper($role->name) }}
                        </button>
                        
                        <input type="hidden" 
                               name="roles[]" 
                               value="{{ $checked ? $role->id : '' }}" 
                               id="role-input-{{ $role->id }}" 
                               @disabled(!$checked)>
                    @endforeach
                </div>
                <button type="submit" class="button small icon solid fa-save btn-custom-height">
                    Zapisz uprawnienia ról
                </button>
            </form>
        </div>

        {{-- Przyciski akcji (dolny pasek) --}}
        <div class="action-bar">

            @if ($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="form-inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="button icon solid flex-btn {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}">
                        {{ $user->is_active ? 'Dezaktywuj konto' : 'Aktywuj konto' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="form-inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="button icon solid fa-trash btn-danger flex-btn"
                            onclick="return confirm('Na pewno trwale usunąć konto użytkownika „{{ addslashes($user->name) }}”?')">
                        Usuń użytkownika
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.users.index') }}" class="button alt icon solid fa-arrow-left btn-back flex-btn">
                Wróć do listy użytkowników
            </a>

        </div>

    </div>
</section>

@push('scripts')
<script>
function toggleRole(btn) {
    const id = btn.dataset.roleId;
    const isActive = btn.dataset.active === '1';
    const input = document.getElementById('role-input-' + id);

    if (isActive) {
        btn.dataset.active = '0';
        btn.classList.remove('active');
        btn.querySelector('.role-btn-icon').textContent = '+';
        input.disabled = true;
        input.value = '';
    } else {
        btn.dataset.active = '1';
        btn.classList.add('active');
        btn.querySelector('.role-btn-icon').textContent = '✓';
        input.disabled = false;
        input.value = id;
    }
}
</script>
@endpush
@endsection