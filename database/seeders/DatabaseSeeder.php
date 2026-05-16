<?php

namespace database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'moderator', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'użytkownik', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@portal.pl',
            'password' => Hash::make('haslo123'),
        ]);
        
        $mod = User::create([
            'name' => 'Moderator',
            'email' => 'mod@portal.pl',
            'password' => Hash::make('haslo123'),
        ]);
        
        $user = User::create([
            'name' => 'Jan Kowalski',
            'email' => 'user@portal.pl',
            'password' => Hash::make('haslo123'),
        ]);

        DB::table('user_role')->insert([
            ['user_id' => $admin->id, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $mod->id, 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'role_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('categories')->insert([
            ['name' => 'Obiady', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Desery', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Śniadania', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Przekąski', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('recipes')->insert([
            'title' => 'Klasyczna Margherita',
            'description' => "1. Rozwałkuj ciasto.\n2. Posmaruj sosem pomidorowym.\n3. Posyp mozzarellą.\n4. Piecz w 220 stopniach przez 10 minut.",
            'prep_time' => 15,
            'calories' => 600,
            'id_category' => 1, 
            'id_user' => $admin->id,
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}