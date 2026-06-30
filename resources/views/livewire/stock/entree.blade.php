<div class="max-w-2xl mx-auto">
    @if(session('succes'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('succes') }}</flux:callout>
    @endif

    <div class="mb-6">
        <flux:heading size="xl">Entrée de Stock</flux:heading>
        <flux:text class="text-zinc-500">Enregistrez une réception de marchandises</flux:text>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
        <form wire:submit="enregistrer" class="flex flex-col gap-5">
            <flux:select wire:model.live="produit_id" label="Produit" required>
                <flux:select.option value="">-- Sélectionner un produit --</flux:select.option>
                @foreach($produits as $p)
                <flux:select.option value="{{ $p->id }}">{{ $p->nom }} ({{ $p->reference }}) — Stock : {{ $p->quantite_disponible }}</flux:select.option>
                @endforeach
            </flux:select>

            @if($produitSelectionne)
            <div class="rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 p-4 grid grid-cols-3 gap-2 text-sm">
                <div>
                    <p class="text-zinc-500 text-xs">Stock actuel</p>
                    <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $produitSelectionne->quantite_disponible }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-xs">Seuil d'alerte</p>
                    <p class="text-xl font-bold text-orange-500">{{ $produitSelectionne->seuil_alerte }}</p>
                </div>
                <div>
                    <p class="text-zinc-500 text-xs">Catégorie</p>
                    <p class="font-medium text-zinc-700 dark:text-zinc-300 truncate">{{ $produitSelectionne->categorie?->nom ?? '—' }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="quantite" label="Quantité reçue" type="number" min="1" placeholder="0" required />
                <flux:input wire:model="date_mouvement" label="Date et heure" type="datetime-local" required />
            </div>

            <flux:input wire:model="motif" label="Motif / Référence commande (optionnel)" placeholder="Ex: Commande N°123, Réapprovisionnement..." />

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="submit" variant="primary" icon="arrow-down-tray">
                    Enregistrer l'entrée
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Lien vers la consultation --}}
    <div class="mt-4 text-center">
        <flux:link href="{{ route('stock.consultation') }}" wire:navigate class="text-sm text-zinc-500">
            Voir l'état du stock →
        </flux:link>
    </div>
</div>
