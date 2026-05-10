<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user()->load('roles');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:50', 'unique:users,name,' . $user->id],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil został zaktualizowany.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $passwordCorrect = Hash::check((string) $request->current_password, $user->password);

        try {
            $request->validate([
                'current_password' => ['required'],
                'password'         => ['required', 'confirmed', Password::min(8)],
            ]);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('tab', 'password');
        }

        if (!$passwordCorrect) {
            return back()
                ->withErrors(['current_password' => 'Obecne hasło jest nieprawidłowe.'])
                ->withInput()
                ->with('tab', 'password');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with([
            'success' => 'Hasło zostało zmienione.',
            'tab'     => 'password',
        ]);
    }
}