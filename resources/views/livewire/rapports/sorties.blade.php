<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <flux:heading size="xl">Rapport des Sorties</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Historique des sorties et consommations</flux:text>
        </div>
        <flux:badge color="orange" size="lg" class="self-start sm:self-auto">Total : {{ $totalQuantite }} unités</flux:badge>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <div class="flex gap-2 w-full md:w-auto">
            <flux:input wire:model.live="dateDebut" label="Du" type="date" class="flex-1 md:w-44" />
            <flux:input wire:model.live="dateFin" label="Au" type="date" class="flex-1 md:w-44" />
        </div>
        <flux:input wire:model.live.debounce.300ms="rechercheProduit" placeholder="Filtrer par produit..." icon="magnifying-glass" class="flex-1" />
    </div>

    {{-- Desktop Version --}}
    <div class="hidden md:block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Date</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Produit</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Catégorie</th>
                    <th class="text-center px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Quantité</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Motif</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Opérateur</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($mouvements as $m)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <td class="px-4 py-3 text-zinc-500 text-xs">{{ $m->date_mouvement->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 font-medium text-zinc-900 dark:text-white">{{ $m->produit?->nom }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $m->produit?->categorie?->nom ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        <flux:badge color="orange">-{{ $m->quantite }}</flux:badge>
                    </td>
                    <td class="px-4 py-3 text-zinc-500">{{ $m->motif ?? '—' }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $m->user?->name ?? 'Système' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-zinc-400">Aucune sortie sur cette période.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Version --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($mouvements as $m)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3">
            <div class="flex justify-between items-start">
                <div class="min-w-0 flex-1 pr-2">
                    <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $m->produit?->nom }}</h3>
                    <p class="text-[10px] text-zinc-400 dark:text-zinc-500">{{ $m->produit?->categorie?->nom ?? 'Sans catégorie' }}</p>
                </div>
                <flux:badge color="orange" size="sm">-{{ $m->quantite }}</flux:badge>
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Date</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $m->date_mouvement->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Opérateur</span>
                    <span class="font-medium text-zinc-800 dark:text-zinc-200 truncate block">{{ $m->user?->name ?? 'Système' }}</span>
                </div>
            </div>

            @if($m->motif)
            <div class="text-xs border-t border-zinc-100 dark:border-zinc-800 pt-2">
                <span class="block text-[10px] text-zinc-400 dark:text-zinc-500 uppercase font-medium">Motif / Dest</span>
                <p class="text-zinc-700 dark:text-zinc-300">{{ $m->motif }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-400">
            Aucune sortie sur cette période.
        </div>
        @endforelse
    </div>

    @if($mouvements->hasPages())
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3 shadow-sm">
        {{ $mouvements->links() }}
    </div>
    @endif
</div>
