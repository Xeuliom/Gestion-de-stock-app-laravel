<div class="max-w-2xl mx-auto">
    @if(session('succes'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('succes') }}</flux:callout>
    @endif

    <div class="mb-6">
        <flux:heading size="xl">Sortie de Stock</flux:heading>
        <flux:text class="text-zinc-500">Enregistrez une sortie ou consommation de produit</flux:text>
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
            <div class="rounded-lg p-4 grid grid-cols-3 gap-2 text-sm border
                {{ $produitSelectionne->quantite_disponible == 0 ? 'bg-red-50 dark:bg-red-950/30 border-red-200 dark:border-red-800'
                : ($produitSelectionne->isEnRupture() ? 'bg-orange-50 dark:bg-orange-950/30 border-orange-200 dark:border-orange-800'
                : 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800') }}">
                <div>
                    <p class="text-zinc-500 text-xs">Stock disponible</p>
                    <p class="text-xl font-bold {{ $produitSelectionne->quantite_disponible == 0 ? 'text-red-600' : ($produitSelectionne->isEnRupture() ? 'text-orange-500' : 'text-emerald-600') }}">
                        {{ $produitSelectionne->quantite_disponible }}
                    </p>
                </div>
                <div>
                    <p class="text-zinc-500 text-xs">Seuil d'alerte</p>
                    <p class="text-xl font-bold text-orange-500">{{ $produitSelectionne->seuil_alerte }}</p>
                </div>
                <div class="flex items-center justify-end">
                    @if($produitSelectionne->quantite_disponible == 0)
                        <flux:badge color="red">Épuisé</flux:badge>
                    @elseif($produitSelectionne->isEnRupture())
                        <flux:badge color="orange">Stock bas</flux:badge>
                    @else
                        <flux:badge color="green">OK</flux:badge>
                    @endif
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="quantite" label="Quantité à sortir" type="number" min="1" placeholder="0" required />
                <flux:input wire:model="date_mouvement" label="Date et heure" type="datetime-local" required />
            </div>

            <flux:input wire:model="motif" label="Motif / Destination (optionnel)" placeholder="Ex: Affectation bureau comptabilité, Consommation atelier..." />

            <div class="flex justify-end gap-3 pt-2">
                <flux:button type="submit" variant="primary" icon="arrow-up-tray"
                    class="bg-orange-600 hover:bg-orange-700 dark:bg-orange-600 dark:hover:bg-orange-700">
                    Enregistrer la sortie
                </flux:button>
            </div>
        </form>
    </div>
</div>
