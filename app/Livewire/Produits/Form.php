<?php

namespace App\Livewire\Produits;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Produit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Produit')]
class Form extends Component
{
    public ?Produit $produit = null;

    public string $reference = '';

    public string $nom = '';

    public string $description = '';

    public string $categorie_id = '';

    public string $fournisseur_id = '';

    public string $prix_achat = '';

    public string $prix_vente = '';

    public string $quantite_disponible = '0';

    public string $seuil_alerte = '5';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->produit = Produit::findOrFail($id);
            $this->reference = $this->produit->reference;
            $this->nom = $this->produit->nom;
            $this->description = $this->produit->description ?? '';
            $this->categorie_id = $this->produit->categorie_id ?? '';
            $this->fournisseur_id = $this->produit->fournisseur_id ?? '';
            $this->prix_achat = $this->produit->prix_achat;
            $this->prix_vente = $this->produit->prix_vente;
            $this->quantite_disponible = $this->produit->quantite_disponible;
            $this->seuil_alerte = $this->produit->seuil_alerte;
        }
    }

    public function sauvegarder(): void
    {
        $this->validate([
            'reference' => 'required|string|max:100|unique:produits,reference'.($this->produit ? ",{$this->produit->id}" : ''),
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie_id' => 'nullable|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_disponible' => 'required|integer|min:0',
            'seuil_alerte' => 'required|integer|min:0',
        ], [
            'reference.required' => 'La référence est obligatoire.',
            'reference.unique' => 'Cette référence est déjà utilisée.',
            'nom.required' => 'Le nom du produit est obligatoire.',
            'prix_achat.required' => "Le prix d'achat est obligatoire.",
            'prix_vente.required' => 'Le prix de vente est obligatoire.',
            'quantite_disponible.required' => 'La quantité est obligatoire.',
        ]);

        $data = [
            'reference' => $this->reference,
            'nom' => $this->nom,
            'description' => $this->description ?: null,
            'categorie_id' => $this->categorie_id ?: null,
            'fournisseur_id' => $this->fournisseur_id ?: null,
            'prix_achat' => $this->prix_achat,
            'prix_vente' => $this->prix_vente,
            'quantite_disponible' => $this->quantite_disponible,
            'seuil_alerte' => $this->seuil_alerte,
        ];

        if ($this->produit) {
            $this->produit->update($data);
            session()->flash('succes', 'Produit modifié avec succès.');
        } else {
            Produit::create($data);
            session()->flash('succes', 'Produit créé avec succès.');
        }

        $this->redirect(route('produits.index'), navigate: true);
    }

    public function render()
    {
        $categories = Categorie::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('livewire.produits.form', compact('categories', 'fournisseurs'));
    }
}
