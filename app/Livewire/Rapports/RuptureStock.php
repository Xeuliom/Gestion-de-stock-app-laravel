<?php

namespace App\Livewire\Rapports;

use App\Models\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Produits en Rupture de Stock')]
class RuptureStock extends Component
{
    use WithPagination;

    public string $recherche = '';
    public string $filtreCategorie = '';

    public function render()
    {
        $produits = Produit::query()
            ->with(['categorie', 'fournisseur'])
            ->enRuptureDeStock()
            ->when($this->recherche, fn($q) => $q->recherche($this->recherche))
            ->when($this->filtreCategorie, fn($q) => $q->where('categorie_id', $this->filtreCategorie))
            ->orderBy('quantite_disponible')
            ->paginate(15);

        $categories = \App\Models\Categorie::orderBy('nom')->get();
        $totalRupture = Produit::enRuptureDeStock()->count();

        return view('livewire.rapports.rupture-stock', compact('produits', 'categories', 'totalRupture'));
    }
}
