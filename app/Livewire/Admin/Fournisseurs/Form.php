<?php

namespace App\Livewire\Admin\Fournisseurs;

use App\Models\Fournisseur;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Fournisseur')]
class Form extends Component
{
    public ?Fournisseur $fournisseur = null;
    public string $nom = '';
    public string $contact = '';
    public string $telephone = '';
    public string $email = '';
    public string $adresse = '';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->fournisseur = Fournisseur::findOrFail($id);
            $this->nom = $this->fournisseur->nom;
            $this->contact = $this->fournisseur->contact ?? '';
            $this->telephone = $this->fournisseur->telephone ?? '';
            $this->email = $this->fournisseur->email ?? '';
            $this->adresse = $this->fournisseur->adresse ?? '';
        }
    }

    public function sauvegarder(): void
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:1000',
        ], [
            'nom.required' => 'Le nom du fournisseur est obligatoire.',
            'email.email' => 'Veuillez saisir un email valide.',
        ]);

        $data = [
            'nom' => $this->nom,
            'contact' => $this->contact ?: null,
            'telephone' => $this->telephone ?: null,
            'email' => $this->email ?: null,
            'adresse' => $this->adresse ?: null,
        ];

        if ($this->fournisseur) {
            $this->fournisseur->update($data);
            session()->flash('succes', 'Fournisseur modifié avec succès.');
        } else {
            Fournisseur::create($data);
            session()->flash('succes', 'Fournisseur créé avec succès.');
        }

        $this->redirect(route('admin.fournisseurs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.fournisseurs.form');
    }
}
