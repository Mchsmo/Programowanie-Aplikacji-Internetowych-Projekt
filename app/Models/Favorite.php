<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_favourite';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_recipe',
        'date_added',
        'notes',
    ];

    protected $casts = [
        'date_added' => 'datetime',
    ];

    /**
     * Użytkownik który dodał do ulubionych
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Przepis dodany do ulubionych
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'id_recipe', 'id_recipe');
    }
}
