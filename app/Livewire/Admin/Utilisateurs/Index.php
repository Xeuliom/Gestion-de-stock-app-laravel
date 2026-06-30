<?php

namespace App\Livewire\Admin\Utilisateurs;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Gestion des Utilisateurs')]
class Index extends Component
{
    use WithPagination;

    public string $recherche = '';

    public string $filtreRole = '';

    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    public function supprimer(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('erreur', 'Vous ne pouvez pas supprimer votre propre compte.');

            return;
        }
        User::findOrFail($id)->delete();
        session()->flash('succes', 'Utilisateur supprimé avec succès.');
    }

    public function render()
    {
        $utilisateurs = User::query()
            ->when($this->recherche, fn ($q) => $q->where('name', 'like', "%{$this->recherche}%")
                ->orWhere('username', 'like', "%{$this->recherche}%")
            )
            ->when($this->filtreRole, fn ($q) => $q->where('role', $this->filtreRole))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.utilisateurs.index', compact('utilisateurs'));
    }
}
