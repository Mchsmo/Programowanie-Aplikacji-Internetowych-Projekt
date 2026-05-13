<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Śniadania'],
            ['name' => 'Obiady'],
            ['name' => 'Kolacje'],
            ['name' => 'Desery'],
            ['name' => 'Przekąski'],
            ['name' => 'Pizze'],
            ['name' => 'Zupy'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}