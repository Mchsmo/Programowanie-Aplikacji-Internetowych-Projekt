<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

    
        Rating::updateOrCreate(
            [
                'id_user' => auth()->id(),
                'id_recipe' => $recipe->id_recipe
            ],
            [
                'rating' => $request->rating,
                'date_added' => now()
            ]
        );

        return redirect()->back()->with('success', 'Dziękujemy! Twoja ocena została zapisana.');
    }
}