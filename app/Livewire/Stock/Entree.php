<?php

namespace App\Livewire\Stock;

use App\Models\MouvementStock;
use App\Models\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Entrée de Stock')]
class Entree extends Component
{
    public string $produit_id = '';
    public string $quantite = '';
    public string $motif = '';
    public string $date_mouvement = '';

    public ?Produit $produitSelectionne = null;

    public function mount(): void
    {
        $this->date_mouvement = now()->format('Y-m-d\TH:i');
    }

    public function updatedProduitId(string $value): void
    {
        $this->produitSelectionne = $value ? Produit::find($value) : null;
    }

    public function enregistrer(): void
    {
        $this->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'motif' => 'nullable|string|max:500',
            'date_mouvement' => 'required|date',
        ], [
            'produit_id.required' => 'Veuillez sélectionner un produit.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité doit être supérieure à 0.',
            'date_mouvement.required' => 'La date est obligatoire.',
        ]);

        MouvementStock::create([
            'produit_id' => $this->produit_id,
            'type' => 'entree',
            'quantite' => $this->quantite,
            'motif' => $this->motif ?: null,
            'user_id' => auth()->id(),
            'date_mouvement' => $this->date_mouvement,
        ]);

        // Mettre à jour la quantité disponible
        $produit = Produit::find($this->produit_id);
        $produit->increment('quantite_disponible', (int) $this->quantite);

        session()->flash('succes', "Entrée de {$this->quantite} unité(s) enregistrée pour « {$produit->nom} ».");

        $this->reset(['produit_id', 'quantite', 'motif', 'produitSelectionne']);
        $this->date_mouvement = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $produits = Produit::orderBy('nom')->get();
        return view('livewire.stock.entree', compact('produits'));
    }
}
