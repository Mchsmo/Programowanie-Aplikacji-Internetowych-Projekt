<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    // Wyświetlanie listy wszystkich przepisów
    public function index(Request $request)
    {

    $query = Recipe::with(['category', 'user'])->where('is_visible', true);

    // 1. WYSZUKIWANIE: Po frazie w tytule 
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // 2. FILTROWANIE: Po wybranej kategorii 
    if ($request->filled('category')) {
        $query->where('id_category', $request->category);
    }

    // 3. STRONICOWANIE: Zwraca 6 przepisów na stronę 
    $recipes = $query->latest()->paginate(6)->withQueryString();
    
    $categories = Category::where('is_active', true)->get();

    return view('recipes.index', compact('recipes', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('recipes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:150',
            'description' => 'required',
            'id_category' => 'required|exists:categories,id_category',
            'recipe_image' => 'nullable|image|max:5120',
            'prep_time' => 'required|integer',
        ]);

        if ($request->hasFile('recipe_image')) {
            $path = $request->file('recipe_image')->store('recipes_photos', 'public');
            $validated['image_path'] = $path;
        }

        $validated['id_user'] = auth()->id();
        $validated['is_visible'] = true;

        \App\Models\Recipe::create($validated);
        
        return redirect()->route('recipes.index')->with('success', 'Przepis został dodany i jest już widoczny!');
    }
}