<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $nom
 * @property string|null $contact
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $adresse
 */
class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    protected $fillable = [
        'nom',
        'contact',
        'telephone',
        'email',
        'adresse',
    ];

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class, 'fournisseur_id');
    }
}
