<nav x-data="{ open: false }" class="bg-slate-900/95 backdrop-blur border-b border-slate-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo SpieleHub -->
                <a href="{{ route('games.index') }}" class="flex items-center gap-2.5 group">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white font-black text-sm shadow-sm group-hover:bg-indigo-500 transition">
                        SH
                    </span>
                    <span class="font-extrabold tracking-tight text-lg text-white">
                        Spiele<span class="text-indigo-400">Hub</span>
                    </span>
                </a>

                <!-- Liens principaux -->
                <div class="hidden sm:flex sm:items-center sm:space-x-6">
                    <a href="{{ route('games.index') }}"
                       class="text-sm font-medium transition {{ request()->routeIs('games.*') || request()->routeIs('home') ? 'text-white border-b-2 border-indigo-500 pb-1' : 'text-slate-400 hover:text-white' }}">
                        Catalogue
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'text-white border-b-2 border-indigo-500 pb-1' : 'text-slate-400 hover:text-white' }}">
                            Dashboard
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Zone compte / invité (Desktop) -->
            @auth
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-sm font-medium text-slate-200 bg-slate-800 hover:bg-slate-700 border border-slate-700 transition">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="fill-current h-4 w-4 text-slate-400" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            @guest
                <div class="hidden sm:flex sm:items-center sm:gap-3">
                    <a href="{{ route('login') }}"
                       class="px-3.5 py-2 text-sm font-medium text-slate-300 hover:text-white transition">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 text-sm font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 shadow-sm transition">
                        Inscription
                    </a>
                </div>
            @endguest

            <!-- Bouton menu mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu mobile -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('games.index') }}" class="block py-2 text-sm font-medium text-slate-200 hover:text-white">
                Catalogue
            </a>
            @auth
                <a href="{{ route('dashboard') }}" class="block py-2 text-sm font-medium text-slate-200 hover:text-white">
                    Dashboard
                </a>
            @endauth
        </div>

        <div class="pt-3 pb-4 border-t border-slate-800 px-4">
            @auth
                <div class="text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-400 mb-3">{{ Auth::user()->email }}</div>
                <div class="space-y-2">
                    <a href="{{ route('profile.edit') }}" class="block text-sm text-slate-300 hover:text-white">
                        {{ __('Profile') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-rose-400 hover:text-rose-300">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="flex flex-col gap-2">
                    <a href="{{ route('login') }}" class="w-full text-center py-2 rounded-lg bg-slate-800 text-slate-200 text-sm font-medium">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="w-full text-center py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold">
                        Inscription
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
