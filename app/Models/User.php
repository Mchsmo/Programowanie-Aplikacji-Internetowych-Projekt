<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'id_user_created',
        'id_user_modified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')
                    ->withTimestamps();
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_user');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'id_user');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'id_user');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_user');
    }

    /** Kto stworzył tego użytkownika */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_created');
    }

    /** Kto ostatnio zmodyfikował tego użytkownika */
    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user_modified');
    }
	
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }
}