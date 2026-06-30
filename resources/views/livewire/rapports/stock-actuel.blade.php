<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Rapport Stock Actuel</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Inventaire complet et valorisation du stock</flux:text>
        </div>
    </div>

    {{-- Résumé --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
            <flux:text class="text-zinc-500 text-xs mb-1">Total produits</flux:text>
            <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalProduits }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
            <flux:text class="text-zinc-500 text-xs mb-1">Valeur totale du stock</flux:text>
            <p class="text-2xl font-bold text-violet-600 dark:text-violet-400">{{ number_format($valeurTotale, 2, ',', ' ') }} MAD</p>
        </div>
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
            <flux:text class="text-zinc-500 text-xs mb-1">Produits en alerte</flux:text>
            <p class="text-3xl font-bold {{ $enRupture > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $enRupture }}</p>
        </div>
    </div>

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
    <div class="hidden md:block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Référence</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Produit</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Catégorie</th>
                    <th class="text-right px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Prix achat</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Quantité</th>
                    <th class="text-right px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Valeur stock</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($produits as $produit)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 font-mono text-xs text-zinc-500">{{ $produit->reference }}</td>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $produit->nom }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-right text-zinc-600">{{ number_format($produit->prix_achat, 2, ',', ' ') }}</td>
                    <td class="px-4 py-3 text-center font-bold {{ $produit->isEnRupture() ? 'text-red-600' : 'text-emerald-600' }}">
                        {{ $produit->quantite_disponible }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-violet-600 dark:text-violet-400">
                        {{ number_format($produit->quantite_disponible * $produit->prix_achat, 2, ',', ' ') }} MAD
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($produit->quantite_disponible == 0)
                            <flux:badge color="red" size="sm">Épuisé</flux:badge>
                        @elseif($produit->isEnRupture())
                            <flux:badge color="orange" size="sm">Alerte</flux:badge>
                        @else
                            <flux:badge color="green" size="sm">OK</flux:badge>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-zinc-400">Aucun produit trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Version --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($produits as $produit)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3">
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $produit->nom }}</h3>
                    <p class="font-mono text-[10px] text-zinc-500">{{ $produit->reference }}</p>
                </div>
                @if($produit->quantite_disponible == 0)
                    <flux:badge color="red" size="sm">Épuisé</flux:badge>
                @elseif($produit->isEnRupture())
                    <flux:badge color="orange" size="sm">Alerte</flux:badge>
                @else
                    <flux:badge color="green" size="sm">OK</flux:badge>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Catégorie</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate block">{{ $produit->categorie?->nom ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Quantité</span>
                    <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $produit->quantite_disponible }} unités</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">P.A. Moyen</span>
                    <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ number_format($produit->prix_achat, 2, ',', ' ') }} MAD</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Valeur Stock</span>
                    <span class="font-bold text-violet-600 dark:text-violet-400">{{ number_format($produit->quantite_disponible * $produit->prix_achat, 2, ',', ' ') }} MAD</span>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-400">
            Aucun produit trouvé.
        </div>
        @endforelse
    </div>

    @if($produits->hasPages())
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3 shadow-sm">
        {{ $produits->links() }}
    </div>
    @endif
</div>
