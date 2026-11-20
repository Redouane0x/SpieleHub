<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Catalogue SpieleHub
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <form method="GET" action="{{ route('games.index') }}" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow grid grid-cols-1 md:grid-cols-4 gap-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Rechercher un jeu, studio..."
                class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
            />

            <select name="genre" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Tous les genres</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre }}" @selected(request('genre') === $genre)>{{ $genre }}</option>
                @endforeach
            </select>

            <select name="status" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                <option value="">Tous les statuts</option>
                <option value="released" @selected(request('status') === 'released')>Disponibles</option>
                <option value="upcoming" @selected(request('status') === 'upcoming')>À venir</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 w-full">
                    Filtrer
                </button>
                <a href="{{ route('games.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md">
                    Reset
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($games as $game)
                <a href="{{ route('games.show', $game) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col justify-between hover:ring-2 hover:ring-indigo-500 transition">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-2">
                            <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $game->genre ?? 'Jeu vidéo' }}
                            </span>
                            <span class="font-medium {{ $game->is_released ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $game->is_released ? 'Sorti' : 'À venir' }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $game->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $game->developer }} • {{ $game->platform }}
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-3 line-clamp-3">
                            {{ $game->description }}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>
                            Note :
                            <strong class="text-gray-900 dark:text-white">
                                {{ $game->ratings_avg_score ? number_format((float) $game->ratings_avg_score, 1) . '/5' : '—' }}
                            </strong>
                        </span>
                        <span>{{ $game->reviews_count }} avis</span>
                    </div>
                </a>
            @empty
                <p class="text-gray-600 dark:text-gray-400 col-span-full">Aucun jeu ne correspond à ces critères.</p>
            @endforelse
        </div>

        <div>
            {{ $games->links() }}
        </div>
    </div>
</x-app-layout>
