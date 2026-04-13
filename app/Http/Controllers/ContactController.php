<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validation des données
        $request->validate([
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'telephone' => 'nullable|string|max:30',
            'message' => 'required|string|min:5',
        ]);

        // Sauvegarde en base de données
        Message::create([
            'nom_complet' => $request->nom,
            'email_client' => $request->email,
            'telephone' => $request->telephone,
            'message' => $request->message,
            'sujet' => 'Message depuis le site',
            'date_envoi' => now(),
            'lu' => false,
            'repondu' => false,
        ]);

        // Redirection avec message de succès
        return redirect()->back()->with('message_success', 'Votre message a été envoyé avec succès. Nous vous répondrons rapidement.');
    }
}