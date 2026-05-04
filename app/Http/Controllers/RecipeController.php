<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    // Wyświetlanie listy przepisów
    public function index()
    {
        $recipes = Recipe::all();
        return view('recipes.index', compact('recipes'));
    }

    // Formularz dodawania (widok)
    public function create()
    {
        return view('recipes.create');
    }

    // Zapisywanie nowego przepisu do bazy
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'prep_time' => 'required|integer',
            'calories' => 'nullable|integer',
            'id_category' => 'required|exists:categories,id_category',
            'recipe_image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // max 5MB
        ]);

        // 2. Obsługa przesyłania obrazu
        if ($request->hasFile('recipe_image')) {
            $path = $request->file('recipe_image')->store('recipes_photos', 'public');
            $validated['image_path'] = $path;
        }

        // 3. Dodanie id_user (pobieramy od zalogowanego użytkownika)
        $validated['id_user'] = auth()->id();

        // 4. Tworzenie rekordu w bazie
        Recipe::create($validated);

        return redirect()->route('recipes.index')->with('success', 'Przepis dodany pomyślnie!');
    }
}