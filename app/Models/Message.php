<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    
    protected $fillable = [
        'nom_complet',
        'email_client',
        'telephone',
        'sujet',
        'message',
        'lu',
        'repondu',
        'reponse_admin',
        'date_envoi',
    ];
    
    public $timestamps = true;
}