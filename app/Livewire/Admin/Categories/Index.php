<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Categorie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Gestion des Catégories')]
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
        Categorie::findOrFail($id)->delete();
        session()->flash('succes', 'Catégorie supprimée avec succès.');
    }

    public function render()
    {
        $categories = Categorie::query()
            ->withCount('produits')
            ->when($this->recherche, fn($q) => $q->where('nom', 'like', "%{$this->recherche}%"))
            ->orderBy('nom')
            ->paginate(10);

        return view('livewire.admin.categories.index', compact('categories'));
    }
}
