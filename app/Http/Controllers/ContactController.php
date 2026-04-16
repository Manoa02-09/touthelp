<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\NewMessageReceived;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'email' => 'required|email|max:150',
            'telephone' => 'nullable|string|max:30',
            'message' => 'required|string|min:5',
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

        Log::info('Message créé - broadcast pour: ' . $request->email);
        broadcast(new NewMessageReceived($message));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès !'
            ]);
        }

        return redirect()->back()->with('message_success', 'Votre message a été envoyé avec succès. Nous vous répondrons rapidement.');
    }

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
        
        return view('admin.messages', compact('conversations'));
    }

    public function repondre(Request $request, $id)
    {
        $request->validate([
            'reponse_admin' => 'required|string|min:2'
        ]);

        $message = Message::findOrFail($id);
        $message->reponse_admin = $request->reponse_admin;
        $message->repondu = true;
        $message->save();

        Log::info('Réponse envoyée - broadcast pour: ' . $message->email_client);
        broadcast(new NewMessageReceived($message));

        return redirect()->back()->with('success', '✅ Réponse envoyée !');
    }

    public function getConversation($email)
    {
        $messages = Message::where('email_client', $email)
            ->orderBy('created_at', 'asc')
            ->get();
        
        return response()->json($messages);
    }

    public function replyFromModal(Request $request)
    {
        $request->validate([
            'email_client' => 'required|email',
            'reponse_admin' => 'required|string'
        ]);

        $message = Message::where('email_client', $request->email_client)
            ->latest()
            ->first();

        if ($message) {
            $message->reponse_admin = $request->reponse_admin;
            $message->repondu = true;
            $message->save();
            
            Log::info('Réponse modal - broadcast pour: ' . $request->email_client);
            broadcast(new NewMessageReceived($message));
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Aucun message trouvé pour cet email'], 404);
    }

    public function markAsRead($id)
    {
        $message = Message::findOrFail($id);
        $message->lu = true;
        $message->save();
        
        return response()->json(['success' => true]);
    }

    public function toggleCloseConversation(Request $request)
    {
        $request->validate([
            'email_client' => 'required|email',
            'closed' => 'required|boolean'
        ]);
        
        // Mettre à jour tous les messages de cette conversation
        Message::where('email_client', $request->email_client)
            ->update(['closed' => $request->closed]);
        
        // Mettre à jour l'utilisateur si existe
        $user = User::where('email', $request->email_client)->first();
        if ($user) {
            $user->conversation_closed = $request->closed;
            $user->save();
        }
        
        // PAS DE BROADCAST - Pas besoin de notifier pour la clôture
        
        return response()->json(['success' => true, 'closed' => $request->closed]);
    }
}