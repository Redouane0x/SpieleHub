<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_url',
        'developer',
        'publisher',
        'platform',
        'genre',
        'release_date',
        'is_released',
    ];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'is_released' => 'boolean',
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

    public function preReleaseHypes(): HasMany
    {
        return $this->hasMany(Hype::class)->where('is_post_release', false);
    }

    public function postReleaseHypes(): HasMany
    {
        return $this->hasMany(Hype::class)->where('is_post_release', true);
    }

    public function averageRating(): ?float
    {
        $avg = $this->ratings()->avg('score');

        return $avg !== null ? round((float) $avg, 1) : null;
    }

    public function totalHypeScore(): int
    {
        return (int) $this->hypes()->sum('hype_level');
    }
}
