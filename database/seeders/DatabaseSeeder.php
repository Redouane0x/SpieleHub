<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@spielehub.local'],
            [
                'name' => 'Joueur Test',
                'password' => bcrypt('password123'),
            ]
        );

        $this->call([
            GameSeeder::class,
        ]);
    }
}
