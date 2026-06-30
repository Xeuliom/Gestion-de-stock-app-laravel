<div>
    @if(session('succes'))
        <flux:callout variant="success" icon="check-circle" class="mb-4">{{ session('succes') }}</flux:callout>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Gestion des Utilisateurs</flux:heading>
            <flux:text class="text-zinc-500 text-sm">Comptes utilisateurs du système</flux:text>
        </div>
        <flux:button href="{{ route('admin.utilisateurs.create') }}" wire:navigate icon="plus" variant="primary">
            Nouveau
        </flux:button>
    </div>

    {{-- Filtres --}}
    <div class="flex flex-col md:flex-row gap-3 mb-4">
        <flux:input wire:model.live.debounce.300ms="recherche" placeholder="Rechercher un utilisateur..." icon="magnifying-glass" class="flex-1" />
        <flux:select wire:model.live="filtreRole" class="w-full md:w-48">
            <flux:select.option value="">Tous les rôles</flux:select.option>
            <flux:select.option value="admin">Administrateur</flux:select.option>
            <flux:select.option value="magasinier">Magasinier</flux:select.option>
        </flux:select>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm overflow-hidden mb-4">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Nom complet</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Nom d'utilisateur</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Email</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Rôle</th>
                    <th class="text-left px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Créé le</th>
                    <th class="text-right px-4 py-3 font-medium text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($utilisateurs as $utilisateur)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <flux:avatar :name="$utilisateur->name" :initials="$utilisateur->initials()" size="sm" />
                            <span class="font-medium text-zinc-900 dark:text-white">{{ $utilisateur->name }}</span>
                            @if($utilisateur->id === auth()->id())
                                <flux:badge color="blue" size="sm">Vous</flux:badge>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono text-zinc-600 dark:text-zinc-400">{{ $utilisateur->username }}</td>
                    <td class="px-4 py-3 text-zinc-500">{{ $utilisateur->email ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <flux:badge color="{{ $utilisateur->role === 'admin' ? 'blue' : 'green' }}" size="sm">
                            {{ $utilisateur->role === 'admin' ? 'Administrateur' : 'Magasinier' }}
                        </flux:badge>
                    </td>
                    <td class="px-4 py-3 text-zinc-500 text-xs">{{ $utilisateur->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <flux:button href="{{ route('admin.utilisateurs.edit', $utilisateur->id) }}" wire:navigate size="sm" variant="ghost" icon="pencil">
                                Modifier
                            </flux:button>
                            @if($utilisateur->id !== auth()->id())
                            <flux:button wire:click="supprimer({{ $utilisateur->id }})"
                                wire:confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ?"
                                size="sm" variant="ghost" icon="trash" class="text-red-500 hover:text-red-600">
                                Supprimer
                            </flux:button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-zinc-400">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Version --}}
    <div class="block md:hidden space-y-3 mb-4">
        @forelse($utilisateurs as $utilisateur)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4 shadow-sm space-y-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <flux:avatar :name="$utilisateur->name" :initials="$utilisateur->initials()" size="sm" />
                    <div>
                        <h3 class="font-semibold text-zinc-900 dark:text-white flex items-center gap-1.5">
                            {{ $utilisateur->name }}
                            @if($utilisateur->id === auth()->id())
                                <flux:badge color="blue" size="sm">Vous</flux:badge>
                            @endif
                        </h3>
                        <p class="font-mono text-[10px] text-zinc-500">{{ $utilisateur->username }}</p>
                    </div>
                </div>
                <flux:badge color="{{ $utilisateur->role === 'admin' ? 'blue' : 'green' }}" size="sm">
                    {{ $utilisateur->role === 'admin' ? 'Admin' : 'Magasinier' }}
                </flux:badge>
            </div>

            <div class="text-xs space-y-1">
                <div>
                    <span class="text-zinc-400 dark:text-zinc-500 uppercase text-[9px] font-medium block">Email</span>
                    <span class="text-zinc-750 dark:text-zinc-250">{{ $utilisateur->email ?? '—' }}</span>
                </div>
                <div>
                    <span class="text-zinc-400 dark:text-zinc-500 uppercase text-[9px] font-medium block">Créé le</span>
                    <span class="text-zinc-700 dark:text-zinc-300">{{ $utilisateur->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button href="{{ route('admin.utilisateurs.edit', $utilisateur->id) }}" wire:navigate size="xs" variant="ghost" icon="pencil">
                    Modifier
                </flux:button>
                @if($utilisateur->id !== auth()->id())
                <flux:button wire:click="supprimer({{ $utilisateur->id }})"
                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ?"
                    size="xs" variant="ghost" icon="trash" class="text-red-500 hover:text-red-600">
                    Supprimer
                </flux:button>
                @endif
            </div>
        </div>
        @empty
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-8 text-center text-zinc-400">
            Aucun utilisateur trouvé.
        </div>
        @endforelse
    </div>

    @if($utilisateurs->hasPages())
    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3 shadow-sm">
        {{ $utilisateurs->links() }}
    </div>
    @endif
</div>
