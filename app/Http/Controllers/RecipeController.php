<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class RecipeController extends Controller
{
    /**
     * Główna lista wszystkich przepisów
     */
    public function index(Request $request)
    {
        $query = Recipe::where('is_visible', true);
        
        // Nakładamy wspólne filtry wyszukiwania i sortowania
        $recipes = $this->applyFiltersAndSort($query, $request)->paginate(9);
        $categories = Category::all();

        return view('recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Przepisy zalogowanego użytkownika ("Moje przepisy")
     */
    public function myRecipes(Request $request)
    {
        $query = Recipe::where('id_user', auth()->id());

        $recipes = $this->applyFiltersAndSort($query, $request)->paginate(9);
        $categories = Category::all();

        return view('recipes.my-recipes', compact('recipes', 'categories'));
    }

    /**
     * Dodaje przepis do ulubionych lub go z nich usuwa (Toggle)
     */
    public function toggleFavorite($id)
    {
        $userId = auth()->id();
        
        $favorite = \App\Models\Favorite::where('id_user', $userId)
                                        ->where('id_recipe', $id)
                                        ->first();

        if ($favorite) {
            $favorite->delete();
            $status = 'Przepis został usunięty z ulubionych.';
        } else {
            \App\Models\Favorite::create([
                'id_user'    => $userId,
                'id_recipe'  => $id,
                'date_added' => now()
            ]);
            $status = 'Przepis został dodany do ulubionych!';
        }

        return redirect()->back()->with('success', $status);
    }

    /**
     * Ulubione przepisy użytkownika
     */
    public function favorites(Request $request)
    {
        // Pobieramy zapytanie relacji ulubionych przepisów zalogowanego usera
        $query = Recipe::whereHas('favorites', function (Builder $q) {
            $q->where('id_user', auth()->id());
        });

        $recipes = $this->applyFiltersAndSort($query, $request)->paginate(9);
        $categories = Category::all();

        return view('recipes.favorites', compact('recipes', 'categories'));
    }

    public function show($id)
    {
        $recipe = Recipe::with(['user', 'category', 'comments.user', 'ratings'])
            ->findOrFail($id);

        return view('recipes.show', compact('recipe'));
    }

    private function applyFiltersAndSort(Builder $query, Request $request): Builder
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('id_category', $request->input('category'));
        }

        switch ($request->input('sort')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'prep_time_asc':
                $query->orderBy('prep_time', 'asc');
                break;
            case 'prep_time_desc':
                $query->orderBy('prep_time', 'desc');
                break;
            case 'calories_asc':
                $query->orderBy('calories', 'asc');
                break;
            case 'calories_desc':
                $query->orderBy('calories', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query;
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
            'prep_time' => 'required|integer|min:1',
            'calories' => 'nullable|integer|min:0',
        ], [
            'prep_time.min' => 'Czas przygotowania musi wynosić co najmniej 1 minutę.',
            'calories.min' => 'Kaloryczność nie może być liczbą ujemną.',
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

    public function destroy($id)
    {
        $recipe = \App\Models\Recipe::findOrFail($id);

        if (auth()->id() !== $recipe->id_user) {
            return redirect()->route('recipes.my-recipes')
                            ->with('error', 'Nie masz uprawnień do usunięcia tego przepisu.');
        }

        if ($recipe->favorites()) {
            $recipe->favorites()->delete();
        }
        if (method_exists($recipe, 'comments') && $recipe->comments()) {
            $recipe->comments()->delete();
        }
        if (method_exists($recipe, 'ratings') && $recipe->ratings()) {
            $recipe->ratings()->delete();
        }

        $recipe->delete();

        return redirect()->route('recipes.my-recipes')
                        ->with('success', 'Przepis został pomyślnie usunięty.');
    }

    /**
     * Aktualizuje prywatną notatkę użytkownika dla ulubionego przepisu
     */
    public function updateFavoriteNotes(Request $request, $id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $favorite = \App\Models\Favorite::where('id_user', auth()->id())
                                        ->where('id_recipe', $id)
                                        ->firstOrFail();

        $favorite->update([
            'notes' => $request->input('notes')
        ]);

        return redirect()->back()->with('success', 'Notatka została zapisana.');
    }
}