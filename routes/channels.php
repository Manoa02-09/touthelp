<?php

use Illuminate\Support\Facades\Broadcast;

// Canal privé pour le chat
Broadcast::channel('chat.{emailHash}', function ($user, $emailHash) {
    // Pour l'admin (peut voir tous les canaux)
    if ($user && $user->role === 'admin') {
        return true;
    }
    
    // Pour un client normal
    $userEmail = $user ? md5($user->email) : null;
    return $userEmail === $emailHash;
});