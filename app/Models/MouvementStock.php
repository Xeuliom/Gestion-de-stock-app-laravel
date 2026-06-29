<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $produit_id
 * @property string $type
 * @property int $quantite
 * @property string|null $motif
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon $date_mouvement
 */
class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'produit_id',
        'type',
        'quantite',
        'motif',
        'user_id',
        'date_mouvement',
    ];

    protected function casts(): array
    {
        return [
            'date_mouvement' => 'datetime',
            'quantite' => 'integer',
        ];
    }

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'produit_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
