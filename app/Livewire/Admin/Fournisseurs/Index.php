<?php

namespace App\Livewire\Admin\Fournisseurs;

use App\Models\Fournisseur;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Gestion des Fournisseurs')]
class Index extends Component
{
    use WithPagination;

    public string $recherche = '';

    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    public function supprimer(int $id): void
    {
        Fournisseur::findOrFail($id)->delete();
        session()->flash('succes', 'Fournisseur supprimé avec succès.');
    }

    public function render()
    {
        $fournisseurs = Fournisseur::query()
            ->withCount('produits')
            ->when($this->recherche, fn ($q) => $q->where('nom', 'like', "%{$this->recherche}%")
                ->orWhere('contact', 'like', "%{$this->recherche}%")
                ->orWhere('telephone', 'like', "%{$this->recherche}%")
            )
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.fournisseurs.index', compact('fournisseurs'));
    }
}
