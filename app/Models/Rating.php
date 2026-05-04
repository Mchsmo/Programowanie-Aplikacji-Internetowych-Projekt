<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_rating';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_recipe',
        'date_added',
    ];

    protected $casts = [
        'date_added' => 'datetime',
    ];

    /**
     * Użytkownik który wystawił ocenę
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Oceniany przepis
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'id_recipe', 'id_recipe');
    }
}
