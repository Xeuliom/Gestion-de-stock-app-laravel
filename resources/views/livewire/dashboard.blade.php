<div>
    {{-- Messages flash --}}
    @if(session('succes'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">
            {{ session('succes') }}
        </flux:callout>
    @endif

    {{-- En-tête avec actions rapides intégrées --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <flux:heading size="xl">Tableau de bord</flux:heading>
            <flux:text class="text-zinc-500">Bienvenue, {{ auth()->user()->name }}</flux:text>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Badge rôle (visible uniquement sur mobile) --}}
            <flux:badge color="{{ auth()->user()->isAdmin() ? 'blue' : 'green' }}" size="sm" class="sm:hidden">
                {{ auth()->user()->isAdmin() ? 'Administrateur' : 'Magasinier' }}
            </flux:badge>

            {{-- Actions rapides --}}
            <flux:button
                href="{{ route('stock.entree') }}"
                wire:navigate
                icon="arrow-down-tray"
                variant="primary"
                size="sm"
                class="whitespace-nowrap"
            >
                Nouvelle entrée
            </flux:button>

            <flux:button
                href="{{ route('stock.sortie') }}"
                wire:navigate
                icon="arrow-up-tray"
                variant="ghost"
                size="sm"
                class="whitespace-nowrap"
            >
                Nouvelle sortie
            </flux:button>

            <flux:button
                href="{{ route('produits.index') }}"
                wire:navigate
                icon="cube"
                variant="ghost"
                size="sm"
                class="whitespace-nowrap"
            >
                Voir les produits
            </flux:button>
        </div>
    </div>
    
    {{-- Cartes KPI --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-8">
        <div
            class="col-span-1 sm:col-span-1 xl:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3 sm:p-5 shadow-sm aspect-square flex flex-col items-center justify-center text-center">
            <div class="flex items-center gap-1 sm:gap-3 mb-1 sm:mb-2">
                <div class="rounded-lg bg-blue-100 dark:bg-blue-900/40 p-2">
                    <flux:icon name="cube" class="h-4 w-4 sm:h-5 sm:w-5 text-blue-600 dark:text-blue-400" />
                </div>
                <flux:text class="text-zinc-500 text-xs sm:text-sm">Total Produits</flux:text>
            </div>
            <p class="text-xl sm:text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalProduits }}</p>
        </div>

        <div
            class="col-span-1 sm:col-span-1 xl:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3 sm:p-5 shadow-sm aspect-square flex flex-col items-center justify-center text-center">
            <div class="flex items-center gap-1 sm:gap-3 mb-1 sm:mb-2">
                <div class="rounded-lg bg-red-100 dark:bg-red-900/40 p-2">
                    <flux:icon name="exclamation-triangle"
                        class="h-4 w-4 sm:h-5 sm:w-5 text-red-600 dark:text-red-400" />
                </div>
                <flux:text class="text-zinc-500 text-xs sm:text-sm">Ruptures de stock</flux:text>
            </div>
            <p
                class="text-xl sm:text-3xl font-bold {{ $enRupture > 0 ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-white' }}">
                {{ $enRupture }}</p>
        </div>

        <div
            class="col-span-1 sm:col-span-1 xl:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3 sm:p-5 shadow-sm aspect-square flex flex-col items-center justify-center text-center">
            <div class="flex items-center gap-1 sm:gap-3 mb-1 sm:mb-2">
                <div class="rounded-lg bg-emerald-100 dark:bg-emerald-900/40 p-2">
                    <flux:icon name="arrow-trending-up"
                        class="h-4 w-4 sm:h-5 sm:w-5 text-emerald-600 dark:text-emerald-400" />
                </div>
                <flux:text class="text-zinc-500 text-xs sm:text-sm">Entrées aujourd'hui</flux:text>
            </div>
            <p class="text-xl sm:text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $entreesAujourdhui }}</p>
        </div>

        <div
            class="col-span-1 sm:col-span-1 xl:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3 sm:p-5 shadow-sm aspect-square flex flex-col items-center justify-center text-center">
            <div class="flex items-center gap-1 sm:gap-3 mb-1 sm:mb-2">
                <div class="rounded-lg bg-orange-100 dark:bg-orange-900/40 p-2">
                    <flux:icon name="arrow-trending-down"
                        class="h-4 w-4 sm:h-5 sm:w-5 text-orange-600 dark:text-orange-400" />
                </div>
                <flux:text class="text-zinc-500 text-xs sm:text-sm">Sorties aujourd'hui</flux:text>
            </div>
            <p class="text-xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $sortiesAujourdhui }}</p>
        </div>

        <div
            class="col-span-1 sm:col-span-1 xl:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3 sm:p-5 shadow-sm aspect-square flex flex-col items-center justify-center text-center">
            <div class="flex items-center gap-1 sm:gap-3 mb-1 sm:mb-2">
                <div class="rounded-lg bg-violet-100 dark:bg-violet-900/40 p-2">
                    <flux:icon name="chart-bar" class="h-4 w-4 sm:h-5 sm:w-5 text-violet-600 dark:text-violet-400" />
                </div>
                <flux:text class="text-zinc-500 text-xs sm:text-sm">Valeur du stock</flux:text>
            </div>
            <p class="text-lg sm:text-2xl font-bold text-violet-600 dark:text-violet-400">
                {{ number_format($valeurStock, 2, ',', ' ') }} MAD</p>
        </div>

        @if(auth()->user()->isAdmin())
            <div
                class="col-span-1 sm:col-span-1 xl:col-span-1 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-3 sm:p-5 shadow-sm aspect-square flex flex-col items-center justify-center text-center">
                <div class="flex items-center gap-1 sm:gap-3 mb-1 sm:mb-2">
                    <div class="rounded-lg bg-sky-100 dark:bg-sky-900/40 p-2">
                        <flux:icon name="users" class="h-4 w-4 sm:h-5 sm:w-5 text-sky-600 dark:text-sky-400" />
                    </div>
                    <flux:text class="text-zinc-500 text-xs sm:text-sm">Utilisateurs</flux:text>
                </div>
                <p class="text-xl sm:text-3xl font-bold text-sky-600 dark:text-sky-400">{{ $totalUtilisateurs }}</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Derniers mouvements --}}
        <div
            class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                <flux:heading size="sm">Derniers mouvements</flux:heading>
                <flux:link href="{{ route('stock.consultation') }}" wire:navigate class="text-xs">Voir tout</flux:link>
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($derniersMovements as $mouvement)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="flex-shrink-0">
                            @if($mouvement->type === 'entree')
                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30">
                                    <flux:icon name="plus" class="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
                                </span>
                            @else
                                <span
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30">
                                    <flux:icon name="minus" class="h-4 w-4 text-orange-600 dark:text-orange-400" />
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">
                                {{ $mouvement->produit?->nom }}</p>
                            <p class="text-xs text-zinc-400">{{ $mouvement->date_mouvement->format('d/m/Y H:i') }} •
                                {{ $mouvement->user?->name ?? 'Système' }}</p>
                        </div>
                        <flux:badge color="{{ $mouvement->type === 'entree' ? 'green' : 'orange' }}" size="sm">
                            {{ $mouvement->type === 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                        </flux:badge>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <flux:text class="text-zinc-400">Aucun mouvement enregistré.</flux:text>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Alertes rupture de stock --}}
        <div
            class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <flux:heading size="sm">Alertes de stock</flux:heading>
                    @if($enRupture > 0)
                        <flux:badge color="red" size="sm">{{ $enRupture }}</flux:badge>
                    @endif
                </div>
                @if(auth()->user()->isAdmin())
                    <flux:link href="{{ route('admin.rapports.rupture-stock') }}" wire:navigate class="text-xs">Voir rapport
                    </flux:link>
                @endif
            </div>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($produitsAlerte as $produit)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-zinc-900 dark:text-white truncate">{{ $produit->nom }}</p>
                            <p class="text-xs text-zinc-400">{{ $produit->categorie?->nom ?? 'Sans catégorie' }} • Seuil :
                                {{ $produit->seuil_alerte }}</p>
                        </div>
                        <flux:badge color="{{ $produit->quantite_disponible == 0 ? 'red' : 'orange' }}" size="sm">
                            {{ $produit->quantite_disponible }} unité(s)
                        </flux:badge>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <flux:icon name="check-circle" class="h-10 w-10 text-emerald-500 mx-auto mb-2" />
                        <flux:text class="text-zinc-400">Tous les stocks sont suffisants !</flux:text>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>