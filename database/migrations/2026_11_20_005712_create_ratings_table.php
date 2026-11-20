<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_url')->nullable();
            $table->string('developer')->nullable();
            $table->string('publisher')->nullable();
            $table->string('platform')->nullable(); // ex. PC, PS5, Xbox, Switch, Multi
            $table->string('genre')->nullable();
            $table->date('release_date')->nullable();
            $table->boolean('is_released')->default(false);
            $table->timestamps();

            $table->index(['release_date', 'is_released']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
