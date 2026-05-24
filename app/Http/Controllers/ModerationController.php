<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    /**
     * Wyświetlanie panelu moderacji
     */
    public function index()
    {
        $recipes = Recipe::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'recipes_page');

        $comments = Comment::with(['user', 'recipe'])
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'comments_page');

        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name', 'asc')
            ->paginate(10, ['*'], 'users_page');

        return view('moderation.index', compact('recipes', 'comments', 'users'));
    }

    /**
     * Usuwanie przepisu przez moderatora
     */
    public function destroyRecipe(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('moderation.index')->with('success', 'Przepis został pomyślnie usunięty.');
    }

    /**
     * Usuwanie komentarza przez moderatora
     */
    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('moderation.index')->with('success', 'Komentarz został pomyślnie usunięty.');
    }

    /**
     * Blokowanie / Odblokowywanie użytkownika (przełącznik is_active)
     */
    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->id_user_modified = auth()->id();
        $user->save();

        $status = $user->is_active ? 'odblokowany' : 'zablokowany';
        return redirect()->route('moderation.index')->with('success', "Użytkownik {$user->name} został {$status}.");
    }
}