<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\NewMessageReceived;
use Illuminate\Support\Facades\Log;

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

        try {
            broadcast(new NewMessageReceived($message));
        } catch (\Exception $e) {
            Log::warning('Pusher broadcast error: ' . $e->getMessage());
        }

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
        try {
            Log::info('=== REPLY FUNCTION CALLED ===');
            Log::info('Email: ' . $request->email_client);
            Log::info('Message: ' . $request->reponse_admin);
            
            $request->validate([
                'email_client' => 'required|email',
                'reponse_admin' => 'required|string|min:1|max:2000'
            ]);
            
            // 1. Chercher un message SANS réponse de ce client
            $message = Message::where('email_client', $request->email_client)
                ->where(function($q) {
                    $q->whereNull('reponse_admin')->orWhere('reponse_admin', '');
                })
                ->latest()
                ->first();
            
            if ($message) {
                Log::info('Cas 1: Message trouvé sans réponse, ID: ' . $message->id);
                $message->reponse_admin = $request->reponse_admin;
                $message->repondu = true;
                $message->save();
                
                try {
                    event(new NewMessageReceived($message));
                } catch (\Exception $e) {
                    Log::warning('Pusher event error (ignorée): ' . $e->getMessage());
                }
                
                return response()->json(['success' => true, 'message' => 'Réponse ajoutée']);
            }
            
            // 2. Créer une nouvelle entrée de réponse
            $lastMessage = Message::where('email_client', $request->email_client)->latest()->first();
            
            if (!$lastMessage) {
                Log::error('Cas 2: Aucun message trouvé pour ce client');
                return response()->json(['success' => false, 'message' => 'Aucun message trouvé'], 404);
            }
            
            Log::info('Cas 2: Création d\'une nouvelle réponse pour: ' . $lastMessage->nom_complet);
            
            $newMessage = Message::create([
                'nom_complet' => $lastMessage->nom_complet,
                'email_client' => $request->email_client,
                'telephone' => $lastMessage->telephone ?? null,
                'message' => '',
                'reponse_admin' => $request->reponse_admin,
                'sujet' => 'Réponse de notre service client',
                'date_envoi' => now(),
                'lu' => false,
                'repondu' => true,
                'closed' => false,
            ]);
            
            try {
                event(new NewMessageReceived($newMessage));
            } catch (\Exception $e) {
                Log::warning('Pusher event error (ignorée): ' . $e->getMessage());
            }
            
            return response()->json(['success' => true, 'message' => 'Nouvelle réponse créée']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ' . json_encode($e->errors()));
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('CRITICAL ERROR: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' line ' . $e->getLine());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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