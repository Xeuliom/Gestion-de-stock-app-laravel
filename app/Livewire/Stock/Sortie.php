<?php

namespace App\Livewire\Stock;

use App\Models\MouvementStock;
use App\Models\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Sortie de Stock')]
class Sortie extends Component
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

        $produit = Produit::findOrFail($this->produit_id);

        if ((int) $this->quantite > $produit->quantite_disponible) {
            $this->addError('quantite', "Stock insuffisant. Disponible : {$produit->quantite_disponible} unité(s).");
            return;
        }

        MouvementStock::create([
            'produit_id' => $this->produit_id,
            'type' => 'sortie',
            'quantite' => $this->quantite,
            'motif' => $this->motif ?: null,
            'user_id' => auth()->id(),
            'date_mouvement' => $this->date_mouvement,
        ]);

        $produit->decrement('quantite_disponible', (int) $this->quantite);

        session()->flash('succes', "Sortie de {$this->quantite} unité(s) enregistrée pour « {$produit->nom} ».");

        $this->reset(['produit_id', 'quantite', 'motif', 'produitSelectionne']);
        $this->date_mouvement = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $produits = Produit::where('quantite_disponible', '>', 0)->orderBy('nom')->get();
        return view('livewire.stock.sortie', compact('produits'));
    }
}
