<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalogue extends Model
{
    protected $fillable = [
        'titre', 'description', 'fichier_pdf', 'image_couverture', 'ordre', 'actif'
    ];

    // Relation avec les expertises
    public function expertises(): BelongsToMany
    {
        return $this->belongsToMany(Expertise::class, 'catalogue_expertise');
    }

    // Relation avec les formations (calendrier)
    public function formations(): HasMany
    {
        return $this->hasMany(Formation::class);
    }
}