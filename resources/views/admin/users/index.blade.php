@extends('layouts.app')

@push('styles')
<style>
    /* Wspólne pomocnicze klasy */
    .w-100 {
        width: 100%;
    }
    .text-nowrap {
        white-space: nowrap;
    }
    .form-inline {
        margin: 0 !important;
        display: inline !important;
    }

    /* Alerty i Komunikaty */
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

    /* Formularz filtrów */
    .filters-form {
        display: flex;
        gap: 1em;
        flex-wrap: wrap;
        align-items: flex-end;
        padding: 1.2em 1.5em;
    }
    .flex-input-search {
        flex: 2;
        min-width: 200px;
    }
    .flex-input-select {
        flex: 1;
        min-width: 140px;
    }
    .filters-actions {
        display: flex;
        gap: .5em;
    }

    /* Wygląd danych użytkownika */
    .user-link {
        font-weight: 600;
    }
    .role-badge {
        display: inline-block;
        padding: .2em .6em;
        border-radius: 3px;
        background: #e3f2fd;
        color: #1565c0;
        font-size: .8em;
        font-weight: 600;
        margin: 1px;
        text-transform: uppercase;
    }
    .role-empty {
        color: #999;
        font-size: .85em;
        font-style: italic;
    }
    .status-active {
        color: #2e7d32;
        font-weight: 600;
    }
    .status-inactive {
        color: #c62828;
        font-weight: 600;
    }

    /* Przyciski Akcji */
    .actions-wrapper {
        display: inline-flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .action-btn {
        display: inline-block !important;
        width: 40px !important;
        height: 40px !important;
        padding: 0 !important;
        text-align: center !important;
        margin: 0 !important;
    }
    .btn-danger {
        background: #c62828 !important;
        border-color: #c62828 !important;
        color: #fff !important;
    }

    /* Brak wyników w tabeli */
    .empty-results {
        text-align: center;
        padding: 2em;
        color: #888;
    }
    .empty-icon {
        font-size: 1.5em;
        display: block;
        margin-bottom: .5em;
    }

    /* Paginacja */
    .pagination-wrapper {
        margin-top: 1.5em;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .5em;
    }
    .pagination-info {
        color: #888;
        font-size: .9em;
        margin: 0;
    }
</style>
@endpush

@section('content')
<section class="wrapper style1">
    <div class="container">

        {{-- Nagłówek --}}
        <header class="major">
            <h2><span class="icon solid fa-users"></span> Zarządzanie użytkownikami</h2>
            <p>Lista wszystkich zarejestrowanych kont</p>
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

        {{-- Filtry --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="box filters-form">
            <div class="flex-input-search">
                <label for="search">Szukaj</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nazwa lub e-mail…" class="w-100">
            </div>
            <div class="flex-input-select">
                <label for="role">Rola</label>
                <select id="role" name="role" class="w-100">
                    <option value="">— wszystkie —</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-input-select">
                <label for="status">Status</label>
                <select id="status" name="status" class="w-100">
                    <option value="">— wszystkie —</option>
                    <option value="active"   @selected(request('status') === 'active')>Aktywni</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nieaktywni</option>
                </select>
            </div>
            <div class="filters-actions">
                <button type="submit" class="button small">
                    <span class="icon solid fa-search"></span> Filtruj
                </button>
                @if (request()->hasAny(['search','role','status']))
                    <a href="{{ route('admin.users.index') }}" class="button small alt">
                        <span class="icon solid fa-times"></span> Wyczyść
                    </a>
                @endif
            </div>
        </form>

        {{-- Tabela --}}
        <div class="table-responsive">
            <table class="users-table">
                <thead>
                    <tr>
                        <th class="col-id">#</th>
                        <th>Użytkownik</th>
                        <th>E-mail</th>
                        <th>Role</th>
                        <th class="col-status">Status</th>
                        <th class="col-date">Zarejestrowany</th>
                        <th class="col-actions">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="align-middle">
                            <td class="cell-padding">{{ $user->id }}</td>
                            <td class="cell-padding">
                                <a href="{{ route('admin.users.show', $user) }}" class="user-link">{{ $user->name }}</a>
                            </td>
                            <td class="cell-padding">{{ $user->email }}</td>
                            <td class="cell-padding">
                                @forelse ($user->roles as $role)
                                    <span class="role-badge">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="role-empty">brak</span>
                                @endforelse
                            </td>
                            <td class="cell-padding text-nowrap">
                                @if ($user->is_active)
                                    <span class="status-active">
                                        <span class="icon solid fa-check-circle"></span> Aktywny
                                    </span>
                                @else
                                    <span class="status-inactive">
                                        <span class="icon solid fa-ban"></span> Nieaktywny
                                    </span>
                                @endif
                            </td>
                            <td class="cell-padding text-nowrap">{{ $user->created_at->format('d.m.Y') }}</td>
                            <td class="cell-padding">
                                <div class="actions-wrapper">
                                    {{-- Podgląd --}}
                                    <a href="{{ route('admin.users.show', $user) }}" class="button small icon solid fa-eye action-btn" title="Podgląd"></a>

                                    @if ($user->id !== auth()->id())
                                        {{-- Toggle aktywności --}}
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="form-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                    class="button small icon solid action-btn {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"
                                                    title="{{ $user->is_active ? 'Dezaktywuj' : 'Aktywuj' }}"
                                                    onclick="return confirm('{{ $user->is_active ? 'Dezaktywować' : 'Aktywować' }} tego użytkownika?')">
                                            </button>
                                        </form>

                                        {{-- Usuń --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="form-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="button small icon solid fa-trash action-btn btn-danger"
                                                    title="Usuń"
                                                    onclick="return confirm('Na pewno usunąć użytkownika „{{ addslashes($user->name) }}”? Operacja jest nieodwracalna.')">
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-results">
                                <span class="icon solid fa-inbox empty-icon"></span>
                                Nie znaleziono użytkowników spełniających kryteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginacja --}}
        <div class="pagination-wrapper">
            <p class="pagination-info">
                Wyświetlono {{ $users->firstItem() }}–{{ $users->lastItem() }}
                z {{ $users->total() }} użytkowników
            </p>
            {{ $users->links() }}
        </div>

    </div>
</section>
@endsection
