<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Game $game): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'content' => ['required', 'string', 'min:10', 'max:3000'],
            'contains_spoilers' => ['nullable', 'boolean'],
        ]);

        Review::create([
            'user_id' => $request->user()->id,
            'game_id' => $game->id,
            'title' => $validated['title'] ?? null,
            'content' => $validated['content'],
            'contains_spoilers' => $request->boolean('contains_spoilers'),
        ]);

        return back()->with('status', 'Votre avis a été publié.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        if ($review->user_id !== $request->user()->id) {
            abort(403);
        }

        $review->delete();

        return back()->with('status', 'Avis supprimé.');
    }
}
