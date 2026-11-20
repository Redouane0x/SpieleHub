<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-bold text-2xl text-white tracking-tight">
                    Catalogue des jeux
                </h2>
                <p class="text-sm text-slate-400">
                    Explore les titres, consulte les notes de la communauté et découvre les prochaines sorties.
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-indigo-300 border border-slate-700">
                {{ $games->total() }} jeu(x)
            </span>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Barre de recherche & filtres -->
            <form method="GET" action="{{ route('games.index') }}"
                  class="bg-slate-900/90 border border-slate-800 p-4 rounded-2xl shadow-lg grid grid-cols-1 md:grid-cols-12 gap-3">
                <div class="md:col-span-5">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher un titre, studio, genre..."
                        class="w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-500 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    />
                </div>

                <div class="md:col-span-3">
                    <select name="genre"
                            class="w-full rounded-xl bg-slate-950 border-slate-800 text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tous les genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre }}" @selected(request('genre') === $genre)>{{ $genre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <select name="status"
                            class="w-full rounded-xl bg-slate-950 border-slate-800 text-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Tous les statuts</option>
                        <option value="released" @selected(request('status') === 'released')>Sortis</option>
                        <option value="upcoming" @selected(request('status') === 'upcoming')>À venir</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow transition">
                        Filtrer
                    </button>
                    <a href="{{ route('games.index') }}"
                       class="py-2.5 px-3 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium rounded-xl border border-slate-700 transition"
                       title="Réinitialiser">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Grille de jeux -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($games as $game)
                    <a href="{{ route('games.show', $game) }}"
                       class="group bg-slate-900 border border-slate-800 hover:border-indigo-500/60 rounded-2xl p-6 flex flex-col justify-between shadow-md hover:shadow-indigo-950/30 hover:-translate-y-0.5 transition duration-200 overflow-hidden">
                        <div>
                            <!-- Bloc image de couverture -->
                            <div class="mb-4 -mx-6 -mt-6 h-44 bg-slate-800 border-b border-slate-800 overflow-hidden">
                                @if($game->cover_url)
                                    <img src="{{ $game->cover_url }}"
                                         alt="Cover de {{ $game->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                         loading="lazy" />
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-800 via-slate-900 to-indigo-950 flex flex-col items-center justify-center px-4 text-center gap-1">
                                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-300/90">
                                            {{ $game->title }}
                                        </span>
                                        <span class="text-[11px] text-slate-400">
                                            Visuel à venir
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between gap-2 mb-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700/70">
                                    {{ $game->genre ?? 'Jeu vidéo' }}
                                </span>

                                @if($game->is_released)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        Sorti
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        À venir
                                    </span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-white group-hover:text-indigo-400 transition">
                                {{ $game->title }}
                            </h3>

                            <p class="text-xs font-medium text-slate-400 mt-1">
                                {{ $game->developer }} <span class="mx-1">•</span> {{ $game->platform }}
                            </p>

                            <p class="text-sm text-slate-300/90 mt-3 line-clamp-3 leading-relaxed">
                                {{ $game->description }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-800 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="text-amber-400 font-bold text-sm">★</span>
                                <span class="font-bold text-white text-sm">
                                    {{ $game->ratings_avg_score ? number_format((float) $game->ratings_avg_score, 1) : '—' }}
                                </span>
                                <span class="text-slate-500">/ 5</span>
                            </div>

                            <span class="text-slate-400 font-medium">
                                {{ $game->reviews_count }} avis
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-slate-900 border border-slate-800 rounded-2xl p-10 text-center">
                        <p class="text-slate-300 font-medium">Aucun jeu ne correspond à tes filtres.</p>
                        <a href="{{ route('games.index') }}" class="inline-block mt-3 text-sm text-indigo-400 hover:underline">
                            Voir tout le catalogue
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div>
                {{ $games->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
