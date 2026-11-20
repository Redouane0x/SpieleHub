<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-white tracking-tight">
                {{ $game->title }}
            </h2>
            <a href="{{ route('games.index') }}"
               class="inline-flex items-center px-3.5 py-2 rounded-xl text-sm font-medium bg-slate-800 text-slate-200 hover:bg-slate-700 border border-slate-700 transition">
                ← Retour au catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-slate-950 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('status'))
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Carte principale du jeu -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-lg space-y-6">
                <!-- Cover grand format -->
                @if($game->cover_url)
                    <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-950 max-h-96">
                        <img src="{{ $game->cover_url }}"
                             alt="Cover de {{ $game->title }}"
                             class="w-full h-full object-cover" />
                    </div>
                @endif

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
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

                        <p class="text-sm text-slate-400 pt-1">
                            <strong class="text-slate-200">{{ $game->developer }}</strong>
                            @if($game->publisher) • Éditeur : {{ $game->publisher }} @endif
                            @if($game->platform) • {{ $game->platform }} @endif
                        </p>

                        @if($game->release_date)
                            <p class="text-xs text-slate-500">
                                Date de sortie : {{ $game->release_date->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>

                    <!-- Score moyen & Hype -->
                    <div class="flex md:flex-col items-center md:items-end justify-between md:justify-start gap-2 bg-slate-950/70 border border-slate-800 rounded-xl px-4 py-3">
                        <div class="text-right">
                            <div class="text-2xl font-extrabold text-indigo-400">
                                {{ $averageRating ? '★ ' . $averageRating . ' / 5' : 'Non noté' }}
                            </div>
                            <div class="text-xs text-slate-400">
                                {{ $game->ratings->count() }} note(s) • {{ $game->reviews->count() }} avis
                            </div>
                        </div>

                        <div class="text-xs font-semibold text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-lg">
                            Hype : {{ $hypeScore }} pt(s) ({{ $hypeCount }})
                        </div>
                    </div>
                </div>

                <p class="text-slate-300 leading-relaxed text-base">
                    {{ $game->description }}
                </p>

                <!-- Formulaire de notation -->
                @auth
                    <form method="POST" action="{{ route('games.ratings.store', $game) }}"
                          class="pt-5 border-t border-slate-800 flex flex-wrap items-center gap-3">
                        @csrf
                        <label for="score" class="text-sm font-medium text-slate-300">
                            Ta note :
                        </label>
                        <select id="score" name="score"
                                class="rounded-xl bg-slate-950 border-slate-700 text-slate-100 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" @selected(optional($userRating)->score === $i)>
                                    {{ $i }} / 5
                                </option>
                            @endfor
                        </select>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow transition">
                            Enregistrer la note
                        </button>
                    </form>
                @else
                    <p class="pt-5 border-t border-slate-800 text-sm text-slate-400">
                        <a href="{{ route('login') }}" class="text-indigo-400 font-medium hover:underline">Connecte-toi</a>
                        pour attribuer une note à ce jeu et publier un avis.
                    </p>
                @endauth
            </div>

            <!-- Formulaire d'avis -->
            @auth
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4">Publier un avis</h3>

                    <form method="POST" action="{{ route('games.reviews.store', $game) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-300">Titre (optionnel)</label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}"
                                   placeholder="Ex. Une claque visuelle et narrative"
                                   class="mt-1.5 block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-500 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('title') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-slate-300">Votre avis</label>
                            <textarea id="content" name="content" rows="4" required
                                      placeholder="Partage ton ressenti sur le gameplay, l'histoire, la technique..."
                                      class="mt-1.5 block w-full rounded-xl bg-slate-950 border-slate-800 text-slate-100 placeholder-slate-500 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                            @error('content') <p class="text-xs text-rose-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 pt-1">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-300 cursor-pointer">
                                <input type="checkbox" name="contains_spoilers" value="1"
                                       class="rounded bg-slate-950 border-slate-700 text-indigo-600 focus:ring-indigo-500" />
                                Contient des spoilers
                            </label>

                            <button type="submit"
                                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow transition">
                                Publier l'avis
                            </button>
                        </div>
                    </form>
                </div>
            @endauth

            <!-- Liste des avis -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-lg space-y-5">
                <h3 class="text-lg font-bold text-white">Avis de la communauté</h3>

                @forelse($game->reviews->sortByDesc('created_at') as $review)
                    <article class="p-5 rounded-xl bg-slate-950/70 border border-slate-800 space-y-2.5">
                        <div class="flex items-center justify-between gap-2 text-xs text-slate-400">
                            <div>
                                <strong class="text-slate-100 font-semibold">{{ $review->user->name }}</strong>
                                <span class="mx-1">•</span>
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            @if($review->contains_spoilers)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Spoiler
                                </span>
                            @endif
                        </div>

                        @if($review->title)
                            <h4 class="font-bold text-white text-base">{{ $review->title }}</h4>
                        @endif

                        <p class="text-sm text-slate-300 whitespace-pre-line leading-relaxed">{{ $review->content }}</p>

                        @if(auth()->id() === $review->user_id)
                            <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="pt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-rose-400 hover:text-rose-300 hover:underline">
                                    Supprimer mon avis
                                </button>
                            </form>
                        @endif
                    </article>
                @empty
                    <p class="text-sm text-slate-400">Aucun avis pour le moment. Sois le premier à partager ton retour !</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
