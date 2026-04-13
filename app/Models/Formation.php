<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formation extends Model
{
    protected $fillable = [
        'catalogue_id', 'titre', 'description', 'date_debut', 'date_fin',
        'heure', 'lieu', 'prix', 'places_max', 'type', 'actif'
    ];

    // Relation avec le catalogue
    public function catalogue(): BelongsTo
    {
        return $this->belongsTo(Catalogue::class);
    }
}