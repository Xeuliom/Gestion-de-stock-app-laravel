<?php

namespace App\Livewire\Admin\Utilisateurs;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Utilisateur')]
class Form extends Component
{
    public ?User $utilisateur = null;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'magasinier';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->utilisateur = User::findOrFail($id);
            $this->name = $this->utilisateur->name;
            $this->username = $this->utilisateur->username;
            $this->email = $this->utilisateur->email ?? '';
            $this->role = $this->utilisateur->role;
        }
    }

    public function sauvegarder(): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username'.($this->utilisateur ? ",{$this->utilisateur->id}" : ''),
            'email' => 'nullable|email|max:255|unique:users,email'.($this->utilisateur ? ",{$this->utilisateur->id}" : ''),
            'role' => 'required|in:admin,magasinier',
        ];

        if (! $this->utilisateur) {
            $rules['password'] = 'required|min:6|confirmed';
        } elseif ($this->password) {
            $rules['password'] = 'min:6|confirmed';
        }

        $this->validate($rules, [
            'name.required' => 'Le nom est obligatoire.',
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.unique' => "Ce nom d'utilisateur est déjà pris.",
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email ?: null,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        if ($this->utilisateur) {
            $this->utilisateur->update($data);
            session()->flash('succes', 'Utilisateur modifié avec succès.');
        } else {
            User::create($data);
            session()->flash('succes', 'Utilisateur créé avec succès.');
        }

        $this->redirect(route('admin.utilisateurs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.utilisateurs.form');
    }
}
