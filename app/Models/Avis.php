<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'entreprise_nom', 'contact_nom', 'contact_fonction',
        'logo_entreprise', 'note', 'contenu', 'statut',
        'mis_en_avant', 'date_soumission', 'date_publication'
    ];

    protected $casts = [
        'note' => 'integer',
        'mis_en_avant' => 'boolean',
        'date_soumission' => 'datetime',
        'date_publication' => 'date',
    ];
}