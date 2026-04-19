<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $fillable = [
        'nom_entreprise', 'logo', 'site_web', 'description',
        'ordre_affichage', 'actif'
    ];
}