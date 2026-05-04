<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_recipe';

    protected $fillable = [
        'title',
        'description',
        'prep_time',
        'calories',
        'image_path',
        'id_category',
        'id_user',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'calories'   => 'integer',
        'prep_time'  => 'integer',
    ];

    /**
     * Użytkownik który dodał przepis
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Kategoria przepisu
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    /**
     * Użytkownicy którzy dodali przepis do ulubionych
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'id_recipe', 'id_recipe');
    }

    /**
     * Oceny przepisu
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'id_recipe', 'id_recipe');
    }

    /**
     * Komentarze pod przepisem
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_recipe', 'id_recipe');
    }

    /**
     * Średnia ocena przepisu
     */
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0.0;
    }

    /**
     * Liczba komentarzy
     */
    public function commentsCount(): int
    {
        return $this->comments()->count();
    }
}