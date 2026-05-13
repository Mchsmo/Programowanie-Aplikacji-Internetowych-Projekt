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
        $user = User::first();
        $category = Category::first();

        if ($user && $category) {
            Recipe::create([
                'title' => 'Klasyczna Margherita',
                'description' => 'Tradycyjna włoska pizza na cienkim cieście z sosem pomidorowym i mozzarellą.',
                'prep_time' => 45,
                'calories' => 850,
                'id_category' => $category->id_category, 
                'id_user' => $user->id,         
                'image_path' => 'recipes_photos/pizza.jpg', 
                'is_visible' => true,
            ]);
		}
	}
}