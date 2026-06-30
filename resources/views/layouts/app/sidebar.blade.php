<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-1">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-white text-sm font-bold">GS</div>
                    <span class="font-semibold text-zinc-900 dark:text-white text-sm">Gestion de Stock</span>
                </a>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                {{-- Tableau de bord --}}
                <flux:sidebar.group heading="Général" class="grid">
                    <flux:sidebar.item icon="squares-2x2" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        Tableau de bord
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Stock --}}
                <flux:sidebar.group heading="Gestion du Stock" class="grid">
                    <flux:sidebar.item icon="cube" :href="route('produits.index')" :current="request()->routeIs('produits.*')" wire:navigate>
                        Produits
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-down-tray" :href="route('stock.entree')" :current="request()->routeIs('stock.entree')" wire:navigate>
                        Entrée de stock
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-up-tray" :href="route('stock.sortie')" :current="request()->routeIs('stock.sortie')" wire:navigate>
                        Sortie de stock
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="eye" :href="route('stock.consultation')" :current="request()->routeIs('stock.consultation')" wire:navigate>
                        Consultation stock
                    </flux:sidebar.item>
                </flux:sidebar.group>

                {{-- Administration (admin seulement) --}}
                @if(auth()->user()?->isAdmin())
                <flux:sidebar.group heading="Administration" class="grid">
                    <flux:sidebar.item icon="users" :href="route('admin.utilisateurs.index')" :current="request()->routeIs('admin.utilisateurs.*')" wire:navigate>
                        Utilisateurs
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="tag" :href="route('admin.categories.index')" :current="request()->routeIs('admin.categories.*')" wire:navigate>
                        Catégories
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="truck" :href="route('admin.fournisseurs.index')" :current="request()->routeIs('admin.fournisseurs.*')" wire:navigate>
                        Fournisseurs
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group heading="Rapports" class="grid">
                    <flux:sidebar.item icon="arrow-trending-up" :href="route('admin.rapports.entrees')" :current="request()->routeIs('admin.rapports.entrees')" wire:navigate>
                        Rapport entrées
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="arrow-trending-down" :href="route('admin.rapports.sorties')" :current="request()->routeIs('admin.rapports.sorties')" wire:navigate>
                        Rapport sorties
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chart-bar" :href="route('admin.rapports.stock-actuel')" :current="request()->routeIs('admin.rapports.stock-actuel')" wire:navigate>
                        Stock actuel
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="exclamation-triangle" :href="route('admin.rapports.rupture-stock')" :current="request()->routeIs('admin.rapports.rupture-stock')" wire:navigate>
                        Rupture de stock
                    </flux:sidebar.item>
                </flux:sidebar.group>
                @endif
            </flux:sidebar.nav>

            <flux:spacer />

            {{-- Badge rôle utilisateur --}}
            <div class="px-3 pb-2">
                <div class="flex items-center gap-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 px-3 py-2 text-xs">
                    <div class="h-2 w-2 rounded-full {{ auth()->user()?->isAdmin() ? 'bg-blue-500' : 'bg-green-500' }}"></div>
                    <span class="text-zinc-500 dark:text-zinc-400">
                        {{ auth()->user()?->isAdmin() ? 'Administrateur' : 'Magasinier' }}
                    </span>
                </div>
            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile Header -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <span class="font-semibold text-sm text-zinc-900 dark:text-white">Gestion de Stock</span>
            <flux:spacer />
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate text-xs">{{ auth()->user()->isAdmin() ? 'Administrateur' : 'Magasinier' }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                            Déconnexion
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
