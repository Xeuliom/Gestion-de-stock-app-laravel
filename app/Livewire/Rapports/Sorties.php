<?php

namespace App\Livewire\Rapports;

use App\Models\MouvementStock;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Rapport des Sorties')]
class Sorties extends Component
{
    use WithPagination;

    public string $dateDebut = '';
    public string $dateFin = '';
    public string $rechercheProduit = '';

    public function mount(): void
    {
        $this->dateDebut = now()->startOfMonth()->format('Y-m-d');
        $this->dateFin = now()->format('Y-m-d');
    }

    public function render()
    {
        $mouvements = MouvementStock::query()
            ->with(['produit.categorie', 'user'])
            ->where('type', 'sortie')
            ->when($this->dateDebut, fn($q) => $q->whereDate('date_mouvement', '>=', $this->dateDebut))
            ->when($this->dateFin, fn($q) => $q->whereDate('date_mouvement', '<=', $this->dateFin))
            ->when($this->rechercheProduit, fn($q) =>
                $q->whereHas('produit', fn($p) => $p->where('nom', 'like', "%{$this->rechercheProduit}%"))
            )
            ->orderByDesc('date_mouvement')
            ->paginate(15);

        $totalQuantite = MouvementStock::query()
            ->where('type', 'sortie')
            ->when($this->dateDebut, fn($q) => $q->whereDate('date_mouvement', '>=', $this->dateDebut))
            ->when($this->dateFin, fn($q) => $q->whereDate('date_mouvement', '<=', $this->dateFin))
            ->sum('quantite');

        return view('livewire.rapports.sorties', compact('mouvements', 'totalQuantite'));
    }
}
