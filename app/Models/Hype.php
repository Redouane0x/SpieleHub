<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hype extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'hype_level',
        'is_post_release',
    ];

    protected function casts(): array
    {
        return [
            'hype_level' => 'integer',
            'is_post_release' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
