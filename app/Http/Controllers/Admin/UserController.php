<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::with('roles')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->input('role')));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'recipes', 'comments', 'ratings', 'favorites']);
        $allRoles = Role::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.show', compact('user', 'allRoles'));
    }

    public function edit(User $user): View
    {
        $allRoles = Role::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'allRoles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'is_active' => ['boolean'],
        ]);

        $data['id_user_modified'] = auth()->id();
        $user->update($data);

        return redirect()->route('admin.users.show', $user)
                         ->with('success', 'Dane użytkownika zostały zaktualizowane.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nie możesz usunąć własnego konta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', "Użytkownik {$user->name} został usunięty.");
    }

    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nie możesz dezaktywować własnego konta.');
        }

        $user->update([
            'is_active'         => !$user->is_active,
            'id_user_modified'  => auth()->id(),
        ]);

        $status = $user->is_active ? 'aktywowany' : 'dezaktywowany';

        return back()->with('success', "Użytkownik został {$status}.");
    }

    public function syncRoles(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'roles'   => ['nullable', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ]);

        $newRoleIds = collect($request->input('roles', []))->map(fn($id) => (int) $id);

        // Walidacja 1: użytkownik musi mieć przynajmniej jedną rolę
        if ($newRoleIds->isEmpty()) {
            return back()->with('error', 'Użytkownik musi posiadać przynajmniej jedną rolę.');
        }

        // Walidacja 2: nie można odebrać roli admin, jeśli to ostatni administrator
        $adminRole = \App\Models\Role::where('name', 'admin')->first();

        if ($adminRole && $user->roles->contains($adminRole->id) && !$newRoleIds->contains($adminRole->id)) {
            $adminCount = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'Nie można odebrać roli administratora — jest to jedyny administrator w systemie.');
            }
        }

        $user->roles()->sync($newRoleIds->all());

        return back()->with('success', 'Role użytkownika zostały zaktualizowane.');
    }
}