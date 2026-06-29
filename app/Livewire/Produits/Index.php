<?php

namespace App\Livewire\Produits;

use App\Models\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Gestion des Produits')]
class Index extends Component
{
    use WithPagination;

    public string $recherche = '';
    public string $filtreCategorie = '';
    public string $filtreStock = '';

    public function updatingRecherche(): void
    {
        $this->resetPage();
    }

    public function supprimer(int $id): void
    {
        Produit::findOrFail($id)->delete();
        session()->flash('succes', 'Produit supprimé avec succès.');
    }

    public function render()
    {
        $produits = Produit::query()
            ->with(['categorie', 'fournisseur'])
            ->when($this->recherche, fn($q) => $q->recherche($this->recherche))
            ->when($this->filtreCategorie, fn($q) => $q->where('categorie_id', $this->filtreCategorie))
            ->when($this->filtreStock === 'rupture', fn($q) => $q->enRuptureDeStock())
            ->when($this->filtreStock === 'disponible', fn($q) => $q->whereColumn('quantite_disponible', '>', 'seuil_alerte'))
            ->orderBy('nom')
            ->paginate(10);

        $categories = \App\Models\Categorie::orderBy('nom')->get();

        return view('livewire.produits.index', compact('produits', 'categories'));
    }
}
