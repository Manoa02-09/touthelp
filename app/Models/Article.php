<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'titre', 
        'slug', 
        'auteur',           // ← AJOUTE CETTE LIGNE
        'contenu', 
        'extrait', 
        'image_une',
        'date_publication', 
        'type', 
        'publie', 
        'vu_compteur'
    ];

    protected $casts = [
        'date_publication' => 'date',
        'publie' => 'boolean',
    ];
}