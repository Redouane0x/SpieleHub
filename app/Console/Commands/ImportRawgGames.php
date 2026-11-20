<?php

namespace App\Console\Commands;

use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportRawgGames extends Command
{
    protected $signature = 'games:import {--pages=2 : Nombre de pages RAWG à importer} {--page-size=20 : Nombre de jeux par page (max 40)}';

    protected $description = 'Importe des jeux vidéo et leurs covers depuis l’API RAWG dans la table games';

    public function handle(): int
    {
        $apiKey = config('services.rawg.key');
        $baseUrl = rtrim((string) config('services.rawg.base_url'), '/');

        if (! $apiKey) {
            $this->error('Clé RAWG manquante : définis RAWG_API_KEY dans ton fichier .env.');
            return self::FAILURE;
        }

        $pages = max(1, (int) $this->option('pages'));
        $pageSize = min(40, max(1, (int) $this->option('page-size')));
        $importedCount = 0;

        for ($page = 1; $page <= $pages; $page++) {
            $this->info("Récupération de la page {$page}/{$pages} depuis RAWG...");

            $response = Http::timeout(20)->get("{$baseUrl}/games", [
                'key' => $apiKey,
                'page' => $page,
                'page_size' => $pageSize,
                'ordering' => '-added',
            ]);

            if ($response->failed()) {
                $this->error("Erreur HTTP {$response->status()} lors de l'appel à RAWG (page {$page}).");
                return self::FAILURE;
            }

            $results = $response->json('results', []);

            foreach ($results as $item) {
                $title = $item['name'] ?? null;
                if (! $title) {
                    continue;
                }

                $slug = Str::slug($title);
                if ($slug === '') {
                    continue;
                }

                $releaseDate = ! empty($item['released']) ? $item['released'] : null;
                $isReleased = false;

                if ($releaseDate) {
                    try {
                        $isReleased = Carbon::parse($releaseDate)->lte(Carbon::today());
                    } catch (\Throwable) {
                        $isReleased = false;
                    }
                }

                $platforms = collect($item['platforms'] ?? [])
                    ->map(fn ($p) => $p['platform']['name'] ?? null)
                    ->filter()
                    ->take(3)
                    ->implode(' / ');

                $genre = $item['genres'][0]['name'] ?? 'Jeu vidéo';

                $description = $item['description_raw']
                    ?? "Jeu vidéo {$title} ({$genre}) disponible sur " . ($platforms ?: 'plateformes variées') . '.';

                Game::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => $title,
                        'slug' => $slug,
                        'description' => $description,
                        'cover_url' => $item['background_image'] ?? null,
                        'developer' => $item['developers'][0]['name'] ?? 'Studio inconnu',
                        'publisher' => $item['publishers'][0]['name'] ?? 'Éditeur inconnu',
                        'platform' => $platforms ?: 'Multiplateforme',
                        'genre' => $genre,
                        'release_date' => $releaseDate,
                        'is_released' => $isReleased,
                    ]
                );

                $importedCount++;
            }
        }

        $this->info("Import terminé : {$importedCount} jeu(x) synchronisé(s) avec leurs covers.");
        return self::SUCCESS;
    }
}
