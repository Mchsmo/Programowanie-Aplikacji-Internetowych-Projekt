<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        $moderator = User::create([
            'name' => 'Moderator User',
            'email' => 'mod@example.com',
            'password' => Hash::make('password'),
        ]);
        $moderator->roles()->attach(Role::where('name', 'moderator')->first());

        $user = User::create([
            'name' => 'Jan Kowalski',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach(Role::where('name', 'użytkownik')->first());
    }
}