<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $reference
 * @property string $nom
 * @property string|null $description
 * @property int|null $categorie_id
 * @property int|null $fournisseur_id
 * @property float $prix_achat
 * @property float $prix_vente
 * @property int $quantite_disponible
 * @property int $seuil_alerte
 */
class Produit extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'reference',
        'nom',
        'description',
        'categorie_id',
        'fournisseur_id',
        'prix_achat',
        'prix_vente',
        'quantite_disponible',
        'seuil_alerte',
    ];

    protected function casts(): array
    {
        return [
            'prix_achat' => 'decimal:2',
            'prix_vente' => 'decimal:2',
            'quantite_disponible' => 'integer',
            'seuil_alerte' => 'integer',
        ];
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    public function mouvementsStock(): HasMany
    {
        return $this->hasMany(MouvementStock::class, 'produit_id');
    }

    public function isEnRupture(): bool
    {
        return $this->quantite_disponible <= $this->seuil_alerte;
    }

    public function scopeEnRuptureDeStock(Builder $query): Builder
    {
        return $query->whereColumn('quantite_disponible', '<=', 'seuil_alerte');
    }

    public function scopeRecherche(Builder $query, string $terme): Builder
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('nom', 'like', "%{$terme}%")
              ->orWhere('reference', 'like', "%{$terme}%")
              ->orWhereHas('categorie', fn($c) => $c->where('nom', 'like', "%{$terme}%"));
        });
    }
}
