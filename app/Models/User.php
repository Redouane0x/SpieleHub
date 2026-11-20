<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function hypes(): HasMany
    {
        return $this->hasMany(Hype::class);
    }

    public function ratedGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'ratings')
            ->withPivot(['score'])
            ->withTimestamps();
    }

    public function reviewedGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'reviews')
            ->withPivot(['title', 'content', 'contains_spoilers'])
            ->withTimestamps();
    }

    public function hypedGames(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'hypes')
            ->withPivot(['hype_level', 'is_post_release'])
            ->withTimestamps();
    }
}
