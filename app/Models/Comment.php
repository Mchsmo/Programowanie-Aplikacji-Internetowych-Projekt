<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_comment';

    public $timestamps = false;

    protected $fillable = [
		'id_user',
		'id_recipe',
		'content',
		'date_added',
		'date_modified',
	];

    protected $casts = [
        'date_added'    => 'datetime',
        'date_modified' => 'datetime',
    ];

    /**
     * Autor komentarza
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Przepis pod którym jest komentarz
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'id_recipe', 'id_recipe');
    }
}
