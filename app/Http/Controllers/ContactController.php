<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\NewMessageReceived;

class ContactController extends Controller
{
    /**
     * Envoi d'un nouveau message depuis le formulaire de contact
     */
    public function send(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'telephone' => 'nullable|string|max:30',
            'message' => 'required|string|min:2|max:2000',
        ]);

        $message = Message::create([
            'nom_complet' => $request->nom,
            'email_client' => $request->email,
            'telephone' => $request->telephone,
            'message' => $request->message,
            'sujet' => 'Message depuis le site',
            'date_envoi' => now(),
            'lu' => false,
            'repondu' => false,
            'closed' => false,
        ]);

        broadcast(new NewMessageReceived($message));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès !',
                'data' => $message
            ]);
        }

        return redirect()->back()->with('message_success', 'Votre message a été envoyé avec succès.');
    }

    /**
     * Récupération des messages pour un client
     */
    public function getMessages(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json([]);
        }

        $messages = Message::where('email_client', $email)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Page admin - liste des conversations
     */
    public function adminIndex()
    {
        $conversations = Message::select('email_client', 'nom_complet')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->groupBy('email_client', 'nom_complet')
            ->orderBy('last_message_at', 'desc')
            ->get();
        
        foreach ($conversations as $conv) {
            $lastMessage = Message::where('email_client', $conv->email_client)->latest()->first();
            $conv->closed = $lastMessage->closed ?? false;
            $conv->unread_count = Message::where('email_client', $conv->email_client)
                ->where('lu', false)
                ->count();
        }
        
        return view('admin.discu', compact('conversations'));
    }

    /**
     * Récupération de la conversation complète
     */
    public function getConversation($email)
    {
        $messages = Message::where('email_client', $email)
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json($messages);
    }

    /**
     * Réponse admin - Version finale corrigée
     */
    public function replyFromModal(Request $request)
    {
        $request->validate([
            'email_client' => 'required|email',
            'reponse_admin' => 'required|string|min:1|max:2000'
        ]);

        // Chercher le dernier message SANS réponse de ce client
        $message = Message::where('email_client', $request->email_client)
            ->whereNull('reponse_admin')
            ->latest()
            ->first();
        
        if ($message) {
            // Cas 1: Un message existe sans réponse → on ajoute la réponse
            $message->reponse_admin = $request->reponse_admin;
            $message->repondu = true;
            $message->save();
            
            broadcast(new NewMessageReceived($message));
            
            return response()->json(['success' => true]);
        }
        
        // Cas 2: Tous les messages ont déjà une réponse
        // On récupère les infos du client
        $lastMessage = Message::where('email_client', $request->email_client)->latest()->first();
        
        if (!$lastMessage) {
            return response()->json([
                'success' => false, 
                'message' => 'Aucun message trouvé'
            ], 404);
        }
        
        // Créer un NOUVEAU message de type "réponse admin"
        // IMPORTANT: le champ 'message' reste vide, la réponse va dans 'reponse_admin'
        $newMessage = Message::create([
            'nom_complet' => $lastMessage->nom_complet,
            'email_client' => $request->email_client,
            'telephone' => $lastMessage->telephone,
            'message' => '', // Vide car c'est une réponse admin
            'reponse_admin' => $request->reponse_admin,
            'sujet' => 'Réponse de notre service client',
            'date_envoi' => now(),
            'lu' => false,
            'repondu' => true,
            'closed' => false,
        ]);
        
        broadcast(new NewMessageReceived($newMessage));
        
        return response()->json(['success' => true]);
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->lu = true;
        $message->save();
        
        return response()->json(['success' => true]);
    }

    /**
     * Archiver / Désarchiver une conversation
     */
    public function toggleCloseConversation(Request $request)
    {
        $request->validate([
            'email_client' => 'required|email',
            'closed' => 'required|boolean'
        ]);
        
        Message::where('email_client', $request->email_client)
            ->update(['closed' => $request->closed]);
        
        return response()->json(['success' => true, 'closed' => $request->closed]);
    }
}