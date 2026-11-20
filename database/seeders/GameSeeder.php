<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            [
                'title' => 'Grand Theft Auto VI',
                'description' => 'Nouvel épisode majeur de la série Grand Theft Auto situé dans l’État de Leonida.',
                'developer' => 'Rockstar Games',
                'publisher' => 'Rockstar Games',
                'platform' => 'PS5 / Xbox Series X|S',
                'genre' => 'Action / Monde ouvert',
                'release_date' => '2026-11-19',
                'is_released' => true,
            ],
            [
                'title' => 'Elden Ring',
                'description' => 'RPG d’action en monde ouvert conçu par FromSoftware et George R. R. Martin.',
                'developer' => 'FromSoftware',
                'publisher' => 'Bandai Namco',
                'platform' => 'PC / PS5 / Xbox',
                'genre' => 'Action RPG',
                'release_date' => '2022-02-25',
                'is_released' => true,
            ],
            [
                'title' => 'Baldur’s Gate 3',
                'description' => 'RPG tactique inspiré de Donjons & Dragons avec une liberté narrative poussée.',
                'developer' => 'Larian Studios',
                'publisher' => 'Larian Studios',
                'platform' => 'PC / PS5 / Xbox',
                'genre' => 'RPG tactique',
                'release_date' => '2023-08-03',
                'is_released' => true,
            ],
            [
                'title' => 'Cyberpunk 2077',
                'description' => 'RPG futuriste en monde ouvert dans la mégalopole de Night City.',
                'developer' => 'CD Projekt Red',
                'publisher' => 'CD Projekt',
                'platform' => 'PC / PS5 / Xbox',
                'genre' => 'Action RPG',
                'release_date' => '2020-12-10',
                'is_released' => true,
            ],
            [
                'title' => 'Hollow Knight: Silksong',
                'description' => 'Suite très attendue de Hollow Knight centrée sur Hornet dans un royaume inédit.',
                'developer' => 'Team Cherry',
                'publisher' => 'Team Cherry',
                'platform' => 'PC / Consoles',
                'genre' => 'Metroidvania',
                'release_date' => '2027-03-15',
                'is_released' => false,
            ],
            [
                'title' => 'The Witcher IV',
                'description' => 'Nouvelle saga dans l’univers de The Witcher développée sous Unreal Engine 5.',
                'developer' => 'CD Projekt Red',
                'publisher' => 'CD Projekt',
                'platform' => 'PC / PS5 / Xbox',
                'genre' => 'Action RPG',
                'release_date' => '2027-09-01',
                'is_released' => false,
            ],
        ];

        foreach ($games as $gameData) {
            $slug = Str::slug($gameData['title']);

            Game::updateOrCreate(
                ['slug' => $slug],
                array_merge($gameData, ['slug' => $slug])
            );
        }
    }
}
