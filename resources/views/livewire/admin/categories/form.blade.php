<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button href="{{ route('admin.categories.index') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm" />
        <div>
            <flux:heading size="xl">{{ $categorie ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $categorie ? $categorie->nom : 'Créer une nouvelle catégorie de produits' }}</flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
        <form wire:submit="sauvegarder" class="flex flex-col gap-5">
            <flux:input wire:model="nom" label="Nom de la catégorie" placeholder="Ex: Informatique" required />
            <flux:textarea wire:model="description" label="Description (optionnelle)" placeholder="Décrivez cette catégorie..." rows="3" />
            <div class="flex justify-end gap-3 pt-2">
                <flux:button href="{{ route('admin.categories.index') }}" wire:navigate variant="ghost">Annuler</flux:button>
                <flux:button type="submit" variant="primary" icon="save">
                    {{ $categorie ? 'Enregistrer' : 'Créer la catégorie' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
