@extends('layouts.app')

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

        {{-- Filtry --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="box" style="display:flex;gap:1em;flex-wrap:wrap;align-items:flex-end;padding:1.2em 1.5em;">
            <div style="flex:2;min-width:200px;">
                <label for="search">Szukaj</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}"
                       placeholder="Imię lub e-mail…" style="width:100%;">
            </div>
            <div style="flex:1;min-width:140px;">
                <label for="role">Rola</label>
                <select id="role" name="role" style="width:100%;">
                    <option value="">— wszystkie —</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:140px;">
                <label for="status">Status</label>
                <select id="status" name="status" style="width:100%;">
                    <option value="">— wszystkie —</option>
                    <option value="active"   @selected(request('status') === 'active')>Aktywni</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nieaktywni</option>
                </select>
            </div>
            <div style="display:flex;gap:.5em;">
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
        <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Użytkownik</th>
                    <th>E-mail</th>
                    <th>Role</th>
                    <th style="width:130px;">Status</th>
                    <th style="width:140px;">Zarejestrowany</th>
                    <th style="width:180px; text-align:center;">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="vertical-align:middle;">
                        <td style="padding:0.75em 0.5em;">{{ $user->id }}</td>
                        <td style="padding:0.75em 0.5em;">
                            <a href="{{ route('admin.users.show', $user) }}" style="font-weight:600;">{{ $user->name }}</a>
                        </td>
                        <td style="padding:0.75em 0.5em;">{{ $user->email }}</td>
                        <td style="padding:0.75em 0.5em;">
                            @forelse ($user->roles as $role)
                                <span style="display:inline-block;padding:.2em .6em;border-radius:3px;
                                      background:#e3f2fd;color:#1565c0;font-size:.8em;font-weight:600;margin:1px;text-transform:uppercase;">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span style="color:#999;font-size:.85em;font-style:italic;">brak</span>
                            @endforelse
                        </td>
                        <td style="padding:0.75em 0.5em; white-space:nowrap;">
                            @if ($user->is_active)
                                <span style="color:#2e7d32;font-weight:600;">
                                    <span class="icon solid fa-check-circle"></span> Aktywny
                                </span>
                            @else
                                <span style="color:#c62828;font-weight:600;">
                                    <span class="icon solid fa-ban"></span> Nieaktywny
                                </span>
                            @endif
                        </td>
                        <td style="padding:0.75em 0.5em; white-space:nowrap;">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td style="padding:0.75em 0.5em; text-align:center;">
                            <div style="display:inline-flex; gap:6px; justify-content:center; align-items:center; width:100%;">
                                {{-- Podgląd --}}
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="button small icon solid fa-eye" title="Podgląd" 
                                   style="display:inline-block !important; width:36px !important; height:36px !important; line-height:36px !important; padding:0 !important; text-align:center !important; margin:0 !important;"></a>

                                @if ($user->id !== auth()->id())
                                    {{-- Toggle aktywności --}}
                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" style="margin:0 !important; display:inline !important;">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="button small icon solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"
                                                title="{{ $user->is_active ? 'Dezaktywuj' : 'Aktywuj' }}"
                                                style="display:inline-block !important; width:36px !important; height:36px !important; line-height:36px !important; padding:0 !important; text-align:center !important; margin:0 !important;"
                                                onclick="return confirm('{{ $user->is_active ? 'Dezaktywować' : 'Aktywować' }} tego użytkownika?')">
                                        </button>
                                    </form>

                                    {{-- Usuń --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="margin:0 !important; display:inline !important;">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="button small icon solid fa-trash"
                                                style="background:#c62828 !important; border-color:#c62828 !important; color:#fff !important; display:inline-block !important; width:36px !important; height:36px !important; line-height:36px !important; padding:0 !important; text-align:center !important; margin:0 !important;"
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
                        <td colspan="7" style="text-align:center;padding:2em;color:#888;">
                            <span class="icon solid fa-inbox" style="font-size:1.5em;display:block;margin-bottom:.5em;"></span>
                            Nie znaleziono użytkowników spełniających kryteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        {{-- Paginacja --}}
        <div style="margin-top:1.5em;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5em;">
            <p style="color:#888;font-size:.9em;margin:0;">
                Wyświetlono {{ $users->firstItem() }}–{{ $users->lastItem() }}
                z {{ $users->total() }} użytkowników
            </p>
            {{ $users->links() }}
        </div>

    </div>
</section>
@endsection