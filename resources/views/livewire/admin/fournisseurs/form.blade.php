<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button href="{{ route('admin.fournisseurs.index') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm" />
        <div>
            <flux:heading size="xl">{{ $fournisseur ? 'Modifier le fournisseur' : 'Nouveau fournisseur' }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $fournisseur ? $fournisseur->nom : 'Ajouter un nouveau fournisseur' }}</flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
        <form wire:submit="sauvegarder" class="flex flex-col gap-5">
            <flux:input wire:model="nom" label="Nom du fournisseur" placeholder="Ex: TechDistrib Maroc" required />
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="contact" label="Personne de contact" placeholder="Nom du contact" />
                <flux:input wire:model="telephone" label="Téléphone" placeholder="+212 6xx xxx xxx" />
            </div>
            <flux:input wire:model="email" label="Email" type="email" placeholder="contact@fournisseur.ma" />
            <flux:textarea wire:model="adresse" label="Adresse" placeholder="Adresse complète du fournisseur" rows="2" />
            <div class="flex justify-end gap-3 pt-2">
                <flux:button href="{{ route('admin.fournisseurs.index') }}" wire:navigate variant="ghost">Annuler</flux:button>
                <flux:button type="submit" variant="primary" icon="save">
                    {{ $fournisseur ? 'Enregistrer' : 'Créer le fournisseur' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
