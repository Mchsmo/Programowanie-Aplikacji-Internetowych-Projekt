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

    public function toggleUserStatus(User $user)
{
    if ($user->hasRole('admin')) {
        abort(403, 'Nie masz uprawnień do modyfikowania statusu konta administratora.');
    }

    // Odwracamy status (true -> false / false -> true)
    $user->is_active = !$user->is_active;
    
    // Zapisujemy zmiany w bazie danych
    $user->save(); 

    $status = $user->is_active ? 'odblokowane' : 'zablokowane';
    
    return redirect()->route('moderation.index')->with('success', "Konto użytkownika {$user->name} zostało pomyślnie {$status}.");
}
}