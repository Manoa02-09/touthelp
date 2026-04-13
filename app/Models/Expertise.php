<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expertise extends Model
{
    protected $fillable = [
        'nom', 'slug', 'description', 'icone', 'ordre_affichage', 'actif'
    ];

    // Relation avec les catalogues (plusieurs catalogues peuvent être liés à une expertise)
    public function catalogues(): BelongsToMany
    {
        return $this->belongsToMany(Catalogue::class, 'catalogue_expertise');
    }
}