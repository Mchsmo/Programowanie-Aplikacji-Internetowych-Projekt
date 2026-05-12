<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    // Wyświetlanie listy wszystkich przepisów
    public function index()
    {
        $recipes = Recipe::with('category')->get();
        return view('recipes.index', compact('recipes'));
    }

    public function create()
    {
        $categories = Category::all(); 
        return view('recipes.create', compact('categories'));
    }

    // Zapisywanie nowego przepisu do bazy
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'prep_time' => 'required|integer',
            'calories' => 'nullable|integer',
            'id_category' => 'required|exists:categories,id', 
            'recipe_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Obsługa przesyłania obrazu
        if ($request->hasFile('recipe_image')) {
            // Pliki trafią do storage/app/public/recipes_photos
            $path = $request->file('recipe_image')->store('recipes_photos', 'public');
            $validated['image_path'] = $path;
        }
        $validated['id_user'] = auth()->id();

        Recipe::create($validated);

        return redirect()->route('dashboard')->with('success', 'Przepis dodany pomyślnie!');
    }
}