<?php

namespace App\Livewire;

use App\Models\MouvementStock;
use App\Models\Produit;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Tableau de Bord')]
class Dashboard extends Component
{
    public function render()
    {
        $totalProduits = Produit::count();
        $enRupture = Produit::enRuptureDeStock()->count();
        $entreesAujourdhui = MouvementStock::where('type', 'entree')
            ->whereDate('date_mouvement', today())
            ->sum('quantite');
        $sortiesAujourdhui = MouvementStock::where('type', 'sortie')
            ->whereDate('date_mouvement', today())
            ->sum('quantite');
        $valeurStock = Produit::selectRaw('SUM(quantite_disponible * prix_achat) as total')->value('total') ?? 0;
        $totalUtilisateurs = User::count();

        $derniersMovements = MouvementStock::with(['produit', 'user'])
            ->orderByDesc('date_mouvement')
            ->limit(8)
            ->get();

        $produitsAlerte = Produit::with('categorie')
            ->enRuptureDeStock()
            ->orderBy('quantite_disponible')
            ->limit(5)
            ->get();

        return view('livewire.dashboard', compact(
            'totalProduits',
            'enRupture',
            'entreesAujourdhui',
            'sortiesAujourdhui',
            'valeurStock',
            'totalUtilisateurs',
            'derniersMovements',
            'produitsAlerte'
        ));
    }
}
