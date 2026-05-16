<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'id_user' => auth()->id(),
            'id_recipe' => $recipe->id_recipe,
            'content' => $request->content,
            'date_added' => now(),
        ]);

        return redirect()->back()->with('success', 'Komentarz został pomyślnie dodany!');
    }
}