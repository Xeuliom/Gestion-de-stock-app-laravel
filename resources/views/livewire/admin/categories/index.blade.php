<div>
    @if(session('succes'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('succes') }}</flux:callout>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Gestion des Catégories</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Organisez vos produits par catégorie</flux:text>
        </div>
        <flux:button href="{{ route('admin.categories.create') }}" wire:navigate icon="plus" variant="primary">
            Nouvelle
        </flux:button>
    </div>

    <div class="mb-4">
        <flux:input wire:model.live.debounce.300ms="recherche" placeholder="Rechercher une catégorie..." icon="magnifying-glass" class="w-full md:max-w-sm" />
    </div>

    {{-- Desktop Version --}}
    <div class="hidden md:block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Nom</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Description</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Produits</th>
                    <th class="text-right px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($categories as $categorie)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $categorie->nom }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $categorie->description ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <flux:badge color="blue" size="sm">{{ $categorie->produits_count }}</flux:badge>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <flux:button href="{{ route('admin.categories.edit', $categorie->id) }}" wire:navigate size="sm" variant="ghost" icon="pencil">Modifier</flux:button>
                            <flux:button wire:click="supprimer({{ $categorie->id }})"
                                wire:confirm="Supprimer cette catégorie ? Les produits associés ne seront pas supprimés."
                                size="sm" variant="ghost" icon="trash" class="text-red-500 hover:text-red-600">
                                Supprimer
                            </flux:button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-12 text-center text-zinc-400">Aucune catégorie trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Version --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($categories as $categorie)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3">
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $categorie->nom }}</h3>
                </div>
                <flux:badge color="blue" size="sm">{{ $categorie->produits_count }} produits</flux:badge>
            </div>

            @if($categorie->description)
            <p class="text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">
                {{ $categorie->description }}
            </p>
            @endif

            <div class="flex justify-end gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button href="{{ route('admin.categories.edit', $categorie->id) }}" wire:navigate size="xs" variant="ghost" icon="pencil">Modifier</flux:button>
                <flux:button wire:click="supprimer({{ $categorie->id }})"
                    wire:confirm="Supprimer cette catégorie ? Les produits associés ne seront pas supprimés."
                    size="xs" variant="ghost" icon="trash" class="text-red-500 hover:text-red-600">
                    Supprimer
                </flux:button>
            </div>
        </div>
        @empty
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-400">
            Aucune catégorie trouvée.
        </div>
        @endforelse
    </div>

    @if($categories->hasPages())
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3 shadow-sm">
        {{ $categories->links() }}
    </div>
    @endif
</div>
