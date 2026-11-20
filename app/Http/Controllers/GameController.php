<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(Request $request): View
    {
        $query = Game::query()
            ->withAvg('ratings', 'score')
            ->withCount('reviews');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('developer', 'like', "%{$search}%")
                    ->orWhere('genre', 'like', "%{$search}%");
            });
        }

        if ($request->filled('genre')) {
            $query->where('genre', $request->string('genre')->toString());
        }

        if ($request->filled('status')) {
            if ($request->string('status')->toString() === 'released') {
                $query->where('is_released', true);
            } elseif ($request->string('status')->toString() === 'upcoming') {
                $query->where('is_released', false);
            }
        }

        $games = $query->orderByDesc('release_date')->paginate(12)->withQueryString();
        $genres = Game::query()->whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre');

        return view('games.index', compact('games', 'genres'));
    }

    public function show(Game $game): View
    {
        $game->load([
            'reviews.user',
            'ratings',
        ]);

        $averageRating = $game->ratings->isNotEmpty()
            ? round((float) $game->ratings->avg('score'), 1)
            : null;

        $userRating = auth()->check()
            ? $game->ratings->firstWhere('user_id', auth()->id())
            : null;

        return view('games.show', compact('game', 'averageRating', 'userRating'));
    }
}
