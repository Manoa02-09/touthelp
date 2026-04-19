<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'titre', 'image', 'description', 'description_courte',
        'date_debut', 'date_fin', 'heure', 'lieu', 'prix',
        'places_max', 'lien_inscription', 'actif'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];
}