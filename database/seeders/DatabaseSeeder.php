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
        $images = [
            'zdjecie1.jpg',
            'zdjecie2.jpg',
            'zdjecie3.jpg',
            'zdjecie4.jpg',
            'zdjecie5.jpg',
            'zdjecie6.jpg',
            'zdjecie7.jpg',
            'zdjecie8.jpg',
            'zdjecie9.jpg',
            'zdjecie10.jpg',
            'zdjecie11.jpg',
        ];

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

        $recipesRaw = [
            [
                'title' => 'Klasyczna Margherita',
                'description' => "1. Rozwałkuj ciasto.\n2. Posmaruj sosem pomidorowym.\n3. Posyp mozzarellą.\n4. Piecz w 220 stopniach przez 10 minut.",
                'prep_time' => 15,
                'calories' => 600,
                'id_category' => 1,
                'id_user' => $admin->id,
                'is_visible' => true,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'title' => 'Spaghetti Carbonara',
                'description' => "1. Ugotuj makaron al dente.\n2. Podsmaż guanciale lub boczek.\n3. Wymieszaj jajka z serem pecorino i pieprzem.\n4. Połącz wszystko poza ogniem, dodając odrobinę wody z makaronu.",
                'prep_time' => 20,
                'calories' => 750,
                'id_category' => 1,
                'id_user' => $admin->id,
                'is_visible' => true,
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(9),
            ],
            [
                'title' => 'Puszyste Naleśniki',
                'description' => "1. Zmieszaj mąkę, mleko, jajka i szczyptę soli.\n2. Smaż na rozgrzanej patelni z odrobiną masła.\n3. Podawaj z dżemem, twarogiem lub owocami.",
                'prep_time' => 25,
                'calories' => 450,
                'id_category' => 3,
                'id_user' => $user->id,
                'is_visible' => true,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ],
            [
                'title' => 'Czekoladowe Brownie',
                'description' => "1. Roztop gorzką czekoladę z masłem.\n2. Ubij jajka z cukrem.\n3. Dodaj mąkę i roztopioną czekoladę.\n4. Piecz w 180 stopniach przez około 20-25 minut.",
                'prep_time' => 35,
                'calories' => 520,
                'id_category' => 2,
                'id_user' => $mod->id,
                'is_visible' => true,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'title' => 'Tradycyjna Jajecznica na Boczku',
                'description' => "1. Pokrój boczek w kostkę i podsmaż na patelni.\n2. Wbij jajka bezpośrednio na patelnię.\n3. Smaż na małym ogniu do pożądanego ścięcia.\n4. Posyp świeżym szczypiorkiem.",
                'prep_time' => 10,
                'calories' => 380,
                'id_category' => 3,
                'id_user' => $user->id,
                'is_visible' => true,
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subDays(6),
            ],
            [
                'title' => 'Sałatka Cezar',
                'description' => "1. Grilluj pierś z kurczaka i pokrój w paski.\n2. Porwij sałatę rzymską i wymieszaj z sosem cezar.\n3. Dodaj grzanki i obficie posyp startym parmezanem.",
                'prep_time' => 15,
                'calories' => 420,
                'id_category' => 1,
                'id_user' => $admin->id,
                'is_visible' => true,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Domowe Frytki z Batatów',
                'description' => "1. Pokrój bataty w słupki.\n2. Wymieszaj z oliwą, papryką słodką i solą.\n3. Wyłóż na blachę do pieczenia.\n4. Piecz w temperaturze 200 stopni przez 25 minut.",
                'prep_time' => 30,
                'calories' => 290,
                'id_category' => 4,
                'id_user' => $user->id,
                'is_visible' => true,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'title' => 'Koktajl Truskawkowy z Chia',
                'description' => "1. Wsyp nasiona chia do mleka roślinnego i odstaw na 15 minut.\n2. Dodaj świeże truskawki oraz banana.\n3. Blenduj na gładką masę. Podawaj schłodzone.",
                'prep_time' => 5,
                'calories' => 210,
                'id_category' => 4,
                'id_user' => $mod->id,
                'is_visible' => true,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'title' => 'Owsianka z Jabłkiem i Cynamonem',
                'description' => "1. Zagotuj płatki owsiane w mleku lub wodzie.\n2. Dodaj starte na tarce jabłko oraz cynamon.\n3. Gotuj na wolnym ogniu przez 5 minut. Udekoruj orzechami.",
                'prep_time' => 12,
                'calories' => 340,
                'id_category' => 3,
                'id_user' => $user->id,
                'is_visible' => true,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Zupa Krem z Pomidorów',
                'description' => "1. Podpiecz w piekarniku pomidory, czosnek i cebulę.\n2. Przełóż warzywa do bulionu i zagotuj.\n3. Zblenduj na aksamitny krem, dodając świeżą bazylię i odrobinę śmietanki.",
                'prep_time' => 40,
                'calories' => 310,
                'id_category' => 1,
                'id_user' => $admin->id,
                'is_visible' => true,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ],
            [
                'title' => 'Szybkie Muffinki Jagodowe',
                'description' => "1. W jednej misce wymieszaj składniki suche, w drugiej mokre.\n2. Połącz zawartość obu misek i dodaj świeże jagody.\n3. Przełóż masę do papilotek.\n4. Piecz 20 minut w 180 stopniach.",
                'prep_time' => 25,
                'calories' => 320,
                'id_category' => 2,
                'id_user' => $mod->id,
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Dołączanie ścieżki do obrazów
        $recipes = array_map(function ($recipe, $index) use ($images) {
            $recipe['image_path'] = 'recipes_photos/' . $images[$index];
            return $recipe;
        }, $recipesRaw, array_keys($recipesRaw));

        DB::table('recipes')->insert($recipes);
    }
}