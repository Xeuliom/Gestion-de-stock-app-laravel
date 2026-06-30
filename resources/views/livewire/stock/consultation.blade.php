<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Consultation du Stock</flux:heading>
            <flux:text class="text-zinc-500">État des quantités disponibles par produit</flux:text>
        </div>
        <div class="flex gap-2">
            <flux:button href="{{ route('stock.entree') }}" wire:navigate icon="arrow-down-tray" variant="ghost" size="sm">Entrée</flux:button>
            <flux:button href="{{ route('stock.sortie') }}" wire:navigate icon="arrow-up-tray" variant="ghost" size="sm">Sortie</flux:button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <flux:input wire:model.live.debounce.300ms="recherche" placeholder="Rechercher un produit..." icon="magnifying-glass" class="flex-1" />
        <div class="flex gap-2 w-full md:w-auto">
            <flux:select wire:model.live="filtreCategorie" class="flex-1 md:w-48">
                <flux:select.option value="">Toutes les catégories</flux:select.option>
                @foreach($categories as $cat)
                <flux:select.option value="{{ $cat->id }}">{{ $cat->nom }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="filtreStock" class="flex-1 md:w-48">
                <flux:select.option value="">Tout le stock</flux:select.option>
                <flux:select.option value="rupture">En rupture / alerte</flux:select.option>
                <flux:select.option value="disponible">Disponible</flux:select.option>
            </flux:select>
        </div>
    </div>

    {{-- Desktop Version --}}
    <div class="hidden md:block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Référence</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Produit</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Catégorie</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Fournisseur</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Quantité</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Seuil</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($produits as $produit)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 {{ $produit->isEnRupture() ? 'bg-red-50/40 dark:bg-red-950/10' : '' }}">
                    <td class="px-4 py-3 font-mono text-xs text-zinc-500">{{ $produit->reference }}</td>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $produit->nom }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->fournisseur?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-xl font-bold {{ $produit->quantite_disponible == 0 ? 'text-red-600' : ($produit->isEnRupture() ? 'text-orange-500' : 'text-emerald-600') }}">
                            {{ $produit->quantite_disponible }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center text-zinc-400">{{ $produit->seuil_alerte }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($produit->quantite_disponible == 0)
                            <flux:badge color="red" size="sm">Épuisé</flux:badge>
                        @elseif($produit->isEnRupture())
                            <flux:badge color="orange" size="sm">⚠ Alerte</flux:badge>
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
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3 {{ $produit->isEnRupture() ? 'border-l-4 border-orange-500' : '' }}">
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $produit->nom }}</h3>
                    <p class="font-mono text-[10px] text-zinc-500">{{ $produit->reference }}</p>
                </div>
                @if($produit->quantite_disponible == 0)
                    <flux:badge color="red" size="sm">Épuisé</flux:badge>
                @elseif($produit->isEnRupture())
                    <flux:badge color="orange" size="sm">⚠ Alerte</flux:badge>
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
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Seuil alerte</span>
                    <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $produit->seuil_alerte }} unités</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Stock actuel</span>
                    <span class="font-bold text-base {{ $produit->quantite_disponible == 0 ? 'text-red-600' : ($produit->isEnRupture() ? 'text-orange-500' : 'text-emerald-600') }}">
                        {{ $produit->quantite_disponible }}
                    </span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Fournisseur</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate block">{{ $produit->fournisseur?->nom ?? '—' }}</span>
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
