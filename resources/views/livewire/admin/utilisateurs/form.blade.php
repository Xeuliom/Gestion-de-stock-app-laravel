<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <flux:button href="{{ route('admin.utilisateurs.index') }}" wire:navigate variant="ghost" icon="arrow-left" size="sm" />
        <div>
            <flux:heading size="xl">{{ $utilisateur ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $utilisateur ? $utilisateur->name : 'Créer un nouveau compte utilisateur' }}</flux:text>
        </div>
    </div>

    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm p-6">
        <form wire:submit="sauvegarder" class="flex flex-col gap-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Nom complet" placeholder="Jean Dupont" required />
                <flux:input wire:model="username" label="Nom d'utilisateur" placeholder="jean.dupont" required />
            </div>

            <flux:input wire:model="email" label="Email (optionnel)" type="email" placeholder="jean@exemple.com" />

            <flux:select wire:model="role" label="Rôle">
                <flux:select.option value="magasinier">Magasinier</flux:select.option>
                <flux:select.option value="admin">Administrateur</flux:select.option>
            </flux:select>

            <flux:separator />

            <flux:input wire:model="password" label="{{ $utilisateur ? 'Nouveau mot de passe (laisser vide pour ne pas changer)' : 'Mot de passe' }}" type="password" placeholder="••••••••" viewable :required="!$utilisateur" />
            <flux:input wire:model="password_confirmation" label="Confirmer le mot de passe" type="password" placeholder="••••••••" viewable />

            <div class="flex justify-end gap-3 pt-2">
                <flux:button href="{{ route('admin.utilisateurs.index') }}" wire:navigate variant="ghost">
                    Annuler
                </flux:button>
                <flux:button type="submit" variant="primary" icon="save">
                    {{ $utilisateur ? 'Enregistrer les modifications' : 'Créer l\'utilisateur' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
