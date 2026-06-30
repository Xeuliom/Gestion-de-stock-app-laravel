<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button href="{{ route('produits.index') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm" />
        <div>
            <flux:heading size="xl">{{ $produit ? 'Modifier le produit' : 'Nouveau produit' }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $produit ? $produit->nom : 'Ajouter un produit au catalogue' }}</flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
        <form wire:submit="sauvegarder" class="flex flex-col gap-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="reference" label="Référence" placeholder="Ex: ORD-001" required />
                <flux:input wire:model="nom" label="Nom du produit" placeholder="Ex: Ordinateur portable HP" required />
            </div>
            <flux:textarea wire:model="description" label="Description (optionnelle)" rows="2" placeholder="Description du produit..." />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="categorie_id" label="Catégorie">
                    <flux:select.option value="">-- Sélectionner une catégorie --</flux:select.option>
                    @foreach($categories as $cat)
                    <flux:select.option value="{{ $cat->id }}">{{ $cat->nom }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="fournisseur_id" label="Fournisseur">
                    <flux:select.option value="">-- Sélectionner un fournisseur --</flux:select.option>
                    @foreach($fournisseurs as $f)
                    <flux:select.option value="{{ $f->id }}">{{ $f->nom }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:separator />
            <flux:heading size="sm">Tarification & Stock</flux:heading>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <flux:input wire:model="prix_achat" label="Prix d'achat (MAD)" type="number" step="0.01" placeholder="0.00" required />
                <flux:input wire:model="prix_vente" label="Prix de vente (MAD)" type="number" step="0.01" placeholder="0.00" required />
                <flux:input wire:model="quantite_disponible" label="Quantité initiale" type="number" placeholder="0" required />
                <flux:input wire:model="seuil_alerte" label="Seuil d'alerte" type="number" placeholder="5" required />
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <flux:button href="{{ route('produits.index') }}" wire:navigate variant="ghost">Annuler</flux:button>
                <flux:button type="submit" variant="primary" icon="save">
                    {{ $produit ? 'Enregistrer les modifications' : 'Créer le produit' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
