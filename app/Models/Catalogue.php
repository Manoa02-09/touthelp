<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
   protected $fillable = [
    'titre', 'description', 'objectifs', 'public_vise',
    'programme', 'fichier_pdf', 'actif', 'ordre'
];
    protected $casts = [
        'actif' => 'boolean',
    ];
}