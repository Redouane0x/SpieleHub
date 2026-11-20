<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            // 1 = Curieux / Intéressé, 2 = Attendu, 3 = Très attendu / Hype max
            $table->unsignedTinyInteger('hype_level')->default(1);
            // Permet de distinguer l'engouement avant sortie (false) et après sortie (true)
            $table->boolean('is_post_release')->default(false);
            $table->timestamps();

            // On conserve l'historique dans le temps (pas de contrainte unique user_id + game_id)
            $table->index(['game_id', 'created_at']);
            $table->index(['game_id', 'is_post_release']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hypes');
    }
};
