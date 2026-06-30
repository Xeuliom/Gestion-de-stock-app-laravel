<?php

namespace App\Livewire\Rapports;

use App\Models\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Stock Actuel')]
class StockActuel extends Component
{
    use WithPagination;

    public string $recherche = '';
    public string $filtreCategorie = '';

    public function render()
    {
        $produits = Produit::query()
            ->with(['categorie', 'fournisseur'])
            ->when($this->recherche, fn($q) => $q->recherche($this->recherche))
            ->when($this->filtreCategorie, fn($q) => $q->where('categorie_id', $this->filtreCategorie))
            ->orderBy('nom')
            ->paginate(15);

        $categories = \App\Models\Categorie::orderBy('nom')->get();

        $totalProduits = Produit::count();
        $valeurTotale = Produit::selectRaw('SUM(quantite_disponible * prix_achat) as total')->value('total') ?? 0;
        $enRupture = Produit::enRuptureDeStock()->count();

        return view('livewire.rapports.stock-actuel', compact('produits', 'categories', 'totalProduits', 'valeurTotale', 'enRupture'));
    }
}
