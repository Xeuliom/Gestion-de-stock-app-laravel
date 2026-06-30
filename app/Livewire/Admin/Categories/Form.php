<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Categorie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Catégorie')]
class Form extends Component
{
    public ?Categorie $categorie = null;

    public string $nom = '';

    public string $description = '';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->categorie = Categorie::findOrFail($id);
            $this->nom = $this->categorie->nom;
            $this->description = $this->categorie->description ?? '';
        }
    }

    public function sauvegarder(): void
    {
        $this->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
        ]);

        $data = [
            'nom' => $this->nom,
            'description' => $this->description ?: null,
        ];

        if ($this->categorie) {
            $this->categorie->update($data);
            session()->flash('succes', 'Catégorie modifiée avec succès.');
        } else {
            Categorie::create($data);
            session()->flash('succes', 'Catégorie créée avec succès.');
        }

        $this->redirect(route('admin.categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.categories.form');
    }
}
