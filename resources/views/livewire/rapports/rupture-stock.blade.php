<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <flux:heading size="xl">Produits en Rupture de Stock</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Produits dont la quantité est inférieure ou égale au seuil d'alerte</flux:text>
        </div>
        @if($totalRupture > 0)
            <flux:badge color="red" size="lg" class="self-start sm:self-auto">{{ $totalRupture }} produit(s) en alerte</flux:badge>
        @else
            <flux:badge color="green" size="lg" class="self-start sm:self-auto">Aucune rupture !</flux:badge>
        @endif
    </div>

    @if($totalRupture == 0)
    <div class="rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 p-12 text-center">
        <flux:icon name="check-circle" class="h-16 w-16 text-emerald-500 mx-auto mb-4" />
        <flux:heading size="lg" class="text-emerald-700 dark:text-emerald-400">Tous les stocks sont suffisants !</flux:heading>
        <flux:text class="text-emerald-600 dark:text-emerald-500 mt-2">Aucun produit n'est en dessous du seuil d'alerte.</flux:text>
    </div>
    @else
    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <flux:input wire:model.live.debounce.300ms="recherche" placeholder="Rechercher..." icon="magnifying-glass" class="flex-1" />
        <flux:select wire:model.live="filtreCategorie" class="w-full md:w-48">
            <flux:select.option value="">Toutes les catégories</flux:select.option>
            @foreach($categories as $cat)
            <flux:select.option value="{{ $cat->id }}">{{ $cat->nom }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    {{-- Desktop Version --}}
    <div class="hidden md:block rounded-xl border border-red-200 dark:border-red-800 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-red-50 dark:bg-red-950/30 border-b border-red-200 dark:border-red-800">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-red-600 dark:text-red-400">Produit</th>
                    <th class="text-left px-4 py-3 font-medium text-red-600 dark:text-red-400">Catégorie</th>
                    <th class="text-left px-4 py-3 font-medium text-red-600 dark:text-red-400">Fournisseur</th>
                    <th class="text-center px-4 py-3 font-medium text-red-600 dark:text-red-400">Stock actuel</th>
                    <th class="text-center px-4 py-3 font-medium text-red-600 dark:text-red-400">Seuil d'alerte</th>
                    <th class="text-center px-4 py-3 font-medium text-red-600 dark:text-red-400">Manque</th>
                    <th class="text-center px-4 py-3 font-medium text-red-600 dark:text-red-400">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($produits as $produit)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3">
                        <p class="font-medium text-zinc-900 dark:text-white">{{ $produit->nom }}</p>
                        <p class="text-xs font-mono text-zinc-400">{{ $produit->reference }}</p>
                    </td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->fournisseur?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-center text-2xl font-bold {{ $produit->quantite_disponible == 0 ? 'text-red-600' : 'text-orange-500' }}">
                        {{ $produit->quantite_disponible }}
                    </td>
                    <td class="px-4 py-3 text-center text-zinc-400">{{ $produit->seuil_alerte }}</td>
                    <td class="px-4 py-3 text-center font-semibold text-red-600">
                        {{ max(0, $produit->seuil_alerte - $produit->quantite_disponible + 1) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($produit->quantite_disponible == 0)
                            <flux:badge color="red">Épuisé</flux:badge>
                        @else
                            <flux:badge color="orange">Stock bas</flux:badge>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-zinc-400">Aucun résultat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Version --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($produits as $produit)
        <div class="rounded-xl border border-red-200 dark:border-red-800 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3">
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $produit->nom }}</h3>
                    <p class="font-mono text-[10px] text-zinc-500">{{ $produit->reference }}</p>
                </div>
                @if($produit->quantite_disponible == 0)
                    <flux:badge color="red" size="sm">Épuisé</flux:badge>
                @else
                    <flux:badge color="orange" size="sm">Stock bas</flux:badge>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Catégorie</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate block">{{ $produit->categorie?->nom ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Seuil</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $produit->seuil_alerte }} unités</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Stock Actuel</span>
                    <span class="font-bold text-sm {{ $produit->quantite_disponible == 0 ? 'text-red-600' : 'text-orange-500' }}">{{ $produit->quantite_disponible }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Quantité manquante</span>
                    <span class="font-bold text-sm text-red-600">{{ max(0, $produit->seuil_alerte - $produit->quantite_disponible + 1) }} unités</span>
                </div>
            </div>

            @if($produit->fournisseur)
            <div class="text-xs border-t border-zinc-100 dark:border-zinc-800 pt-2">
                <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Fournisseur</span>
                <p class="text-zinc-700 dark:text-zinc-300 font-medium">{{ $produit->fournisseur->nom }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-400">
            Aucun résultat.
        </div>
        @endforelse
    </div>

    @if($produits->hasPages())
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3 shadow-sm">
        {{ $produits->links() }}
    </div>
    @endif
    @endif
</div>
