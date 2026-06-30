<div>
    @if(session('succes'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('succes') }}</flux:callout>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Gestion des Produits</flux:heading>
            <flux:text class="text-zinc-500">Catalogue complet des produits</flux:text>
        </div>
        @if(auth()->user()->isAdmin())
        <flux:button href="{{ route('produits.create') }}" wire:navigate icon="plus" variant="primary">
            Nouveau
        </flux:button>
        @endif
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <flux:input wire:model.live.debounce.300ms="recherche" placeholder="Rechercher par nom ou référence..." icon="magnifying-glass" class="flex-1" />
        <div class="flex gap-2 w-full md:w-auto">
            <flux:select wire:model.live="filtreCategorie" class="flex-1 md:w-48">
                <flux:select.option value="">Toutes les catégories</flux:select.option>
                @foreach($categories as $cat)
                <flux:select.option value="{{ $cat->id }}">{{ $cat->nom }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select wire:model.live="filtreStock" class="flex-1 md:w-48">
                <flux:select.option value="">Tout le stock</flux:select.option>
                <flux:select.option value="rupture">En rupture</flux:select.option>
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
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Nom</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Catégorie</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Fournisseur</th>
                    <th class="text-right px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Prix vente</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Quantité</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Statut</th>
                    @if(auth()->user()->isAdmin())
                    <th class="text-right px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($produits as $produit)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors {{ $produit->isEnRupture() ? 'bg-red-50/50 dark:bg-red-950/10' : '' }}">
                    <td class="px-4 py-3 font-mono text-xs text-zinc-500">{{ $produit->reference }}</td>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $produit->nom }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $produit->fournisseur?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-right font-medium">{{ number_format($produit->prix_vente, 2, ',', ' ') }} MAD</td>
                    <td class="px-4 py-3 text-center font-bold {{ $produit->quantite_disponible == 0 ? 'text-red-600' : ($produit->isEnRupture() ? 'text-orange-500' : 'text-emerald-600') }}">
                        {{ $produit->quantite_disponible }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($produit->quantite_disponible == 0)
                            <flux:badge color="red" size="sm">Épuisé</flux:badge>
                        @elseif($produit->isEnRupture())
                            <flux:badge color="orange" size="sm">Alerte</flux:badge>
                        @else
                            <flux:badge color="green" size="sm">Disponible</flux:badge>
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <flux:button href="{{ route('produits.edit', $produit->id) }}" wire:navigate size="sm" variant="ghost" icon="pencil">Modifier</flux:button>
                            <flux:button wire:click="supprimer({{ $produit->id }})"
                                wire:confirm="Supprimer ce produit et tous ses mouvements de stock ?"
                                size="sm" variant="ghost" icon="trash" class="text-red-500 hover:text-red-600">
                                Supprimer
                            </flux:button>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" class="px-4 py-12 text-center text-zinc-400">
                        Aucun produit trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Version --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($produits as $produit)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3 {{ $produit->isEnRupture() ? 'border-l-4 border-red-500' : '' }}">
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
                    <flux:badge color="green" size="sm">Disponible</flux:badge>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Catégorie</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate block">{{ $produit->categorie?->nom ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Fournisseur</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate block">{{ $produit->fournisseur?->nom ?? '—' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Prix Vente</span>
                    <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ number_format($produit->prix_vente, 2, ',', ' ') }} MAD</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Quantité</span>
                    <span class="font-bold text-sm {{ $produit->quantite_disponible == 0 ? 'text-red-600' : ($produit->isEnRupture() ? 'text-orange-500' : 'text-emerald-600') }}">
                        {{ $produit->quantite_disponible }}
                    </span>
                </div>
            </div>

            @if(auth()->user()->isAdmin())
            <div class="flex justify-end gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button href="{{ route('produits.edit', $produit->id) }}" wire:navigate size="xs" variant="ghost" icon="pencil">Modifier</flux:button>
                <flux:button wire:click="supprimer({{ $produit->id }})"
                    wire:confirm="Supprimer ce produit et tous ses mouvements de stock ?"
                    size="xs" variant="ghost" icon="trash" class="text-red-500 hover:text-red-600">
                    Supprimer
                </flux:button>
            </div>
            @endif
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
