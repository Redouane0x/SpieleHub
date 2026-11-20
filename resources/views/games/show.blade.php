<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $game->title }}
            </h2>
            <a href="{{ route('games.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                ← Retour au catalogue
            </a>
        </div>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('status'))
            <div class="p-4 rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $game->developer }} • {{ $game->publisher }} • {{ $game->platform }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $game->genre }} —
                        @if($game->release_date)
                            Sortie : {{ $game->release_date->format('d/m/Y') }}
                        @endif
                    </p>
                </div>

                <div class="text-right">
                    <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ $averageRating ? $averageRating . ' / 5' : 'Pas encore noté' }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $game->ratings->count() }} note(s) • {{ $game->reviews->count() }} avis
                    </div>
                    <div class="text-xs font-medium text-amber-600 dark:text-amber-400 mt-1">
                        Hype : {{ $hypeScore }} pt(s) ({{ $hypeCount }} vote(s))
                    </div>
                </div>
            </div>

            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                {{ $game->description }}
            </p>

            @auth
                <form method="POST" action="{{ route('games.ratings.store', $game) }}" class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap items-center gap-3">
                    @csrf
                    <label for="score" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Ta note :
                    </label>
                    <select id="score" name="score" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" @selected(optional($userRating)->score === $i)>
                                {{ $i }} / 5
                            </option>
                        @endfor
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-500">
                        Enregistrer la note
                    </button>
                </form>
            @else
                <p class="pt-4 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 underline">Connecte-toi</a> pour noter ce jeu et publier un avis.
                </p>
            @endauth
        </div>

        @auth
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Publier un avis</h3>

                <form method="POST" action="{{ route('games.reviews.store', $game) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre (optionnel)</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Votre avis</label>
                        <textarea id="content" name="content" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ old('content') }}</textarea>
                        @error('content') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" name="contains_spoilers" value="1" class="rounded border-gray-300 dark:border-gray-700" />
                            Contient des spoilers
                        </label>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-500">
                            Publier l'avis
                        </button>
                    </div>
                </form>
            </div>
        @endauth

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Avis de la communauté</h3>

            @forelse($game->reviews->sortByDesc('created_at') as $review)
                <article class="p-4 rounded-md border border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>
                            <strong class="text-gray-900 dark:text-gray-100">{{ $review->user->name }}</strong>
                            • {{ $review->created_at->diffForHumans() }}
                        </span>

                        @if($review->contains_spoilers)
                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                Spoiler
                            </span>
                        @endif
                    </div>

                    @if($review->title)
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $review->title }}</h4>
                    @endif

                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $review->content }}</p>

                    @if(auth()->id() === $review->user_id)
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="pt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">
                                Supprimer mon avis
                            </button>
                        </form>
                    @endif
                </article>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">Aucun avis pour le moment. Sois le premier à partager ton retour !</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
