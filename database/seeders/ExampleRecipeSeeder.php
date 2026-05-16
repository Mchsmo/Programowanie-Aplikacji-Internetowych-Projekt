<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\User;

class ExampleRecipeSeeder extends Seeder
{
    public function run(): void
    {
        Recipe::query()->delete();
        $this->command->info('Stare przepisy zostały pomyślnie usunięte z bazy danych.');

        $user = User::first(); 

        $pizzaCat   = Category::where('name', 'Pizze')->first() ?? Category::create(['name' => 'Pizze']);
        $dinnerCat  = Category::where('name', 'Obiady')->first() ?? Category::create(['name' => 'Obiady']);
        $dessertCat = Category::where('name', 'Desery')->first() ?? Category::create(['name' => 'Desery']);

        if (!$user) {
            $this->command->error('Błąd: Nie znaleziono użytkownika! Uruchom najpierw UserSeeder.');
            return;
        }

        $newRecipes = [
            [
                'title' => 'Włoska Pizza Margherita',
                'description' => 'Klasyczna włoska pizza na cienkim cieście z domowym sosem pomidorowym, świeżą mozzarellą i listkami bazylii.',
                'prep_time' => 45,
                'calories' => 850,
                'id_category' => $pizzaCat->id_category, 
                'id_user' => $user->id,
                'image_path' => 'recipes_photos/pizza.jpg',
                'is_visible' => true,
            ],
            [
                'title' => 'Kremowa Carbonara',
                'description' => 'Tradycyjne rzymskie spaghetti carbonara przygotowywane wyłącznie na bazie żółtek, sera Pecorino Romano i chrupiącego boczku guanciale, bez dodatku śmietany.',
                'prep_time' => 20,
                'calories' => 710,
                'id_category' => $dinnerCat->id_category,
                'id_user' => $user->id,
                'image_path' => 'recipes_photos/carbonara.jpg',
                'is_visible' => true,
            ],
            [
                'title' => 'Puszyste Pancakes',
                'description' => 'Amerykańskie, puszyste naleśniki podawane na słodko z syropem klonowym, masłem i świeżymi borówkami. Idealna propozycja na weekendowe śniadanie.',
                'prep_time' => 15,
                'calories' => 480,
                'id_category' => $dessertCat->id_category,
                'id_user' => $user->id,
                'image_path' => 'recipes_photos/pancakes.jpg',
                'is_visible' => true,
            ],
        ];

        foreach ($newRecipes as $recipeData) {
            Recipe::create($recipeData);
        }

        $this->command->info('Pomyślnie dodano nowe, przykładowe przepisy!');
    }
}