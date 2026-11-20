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

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('developer', 'like', "%{$search}%")
                        ->orWhere('genre', 'like', "%{$search}%");
                });
            }
        }

        // Filtre par genre
        if ($request->filled('genre')) {
            $genre = trim($request->string('genre')->toString());
            if ($genre !== '') {
                $query->where('genre', $genre);
            }
        }

        // Filtre par statut (sorti/à venir)
        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            if ($status === 'released') {
                $query->where('is_released', true);
            } elseif ($status === 'upcoming') {
                $query->where('is_released', false);
            }
        }

        // Récupération des jeux avec priorisation sur ceux ayant une cover, puis tri par date
        $games = $query
            ->orderByRaw('CASE WHEN cover_url IS NOT NULL AND cover_url != "" THEN 0 ELSE 1 END')
            ->orderByDesc('release_date')
            ->paginate(12)
            ->withQueryString();

        // Liste des genres uniques pour le filtre
        $genres = Game::query()
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        return view('games.index', compact('games', 'genres'));
    }

    public function show(Game $game): View
    {
        // Chargement des relations nécessaires pour la fiche détail
        $game->load([
            'reviews.user', // Pour afficher l'auteur de chaque avis
            'ratings',      // Pour calculer la moyenne et la note de l'utilisateur
            'hypes',        // Pour le compteur de hype
        ]);

        // Calcul de la note moyenne
        $averageRating = $game->ratings->isNotEmpty()
            ? round((float) $game->ratings->avg('score'), 1)
            : null;

        // Récupération de la note de l'utilisateur connecté (si authentifié)
        $userRating = auth()->check()
            ? $game->ratings->firstWhere('user_id', auth()->id())
            : null;

        // Calcul du niveau de hype
        $hypeScore = (int) $game->hypes->sum('hype_level');
        $hypeCount = $game->hypes->count();

        return view('games.show', compact(
            'game',
            'averageRating',
            'userRating',
            'hypeScore',
            'hypeCount'
        ));
    }
}
