<?php

namespace App\Http\Controllers;

use App\Models\Message;
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
        ]);

        Log::info('4 - broadcast() exécuté dans ContactController pour: ' . $request->email);
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

        Log::info('4 - broadcast() exécuté dans ContactController (repondre) pour: ' . $message->email_client);
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
            
            Log::info('4 - broadcast() exécuté dans ContactController (replyFromModal) pour: ' . $request->email_client);
            broadcast(new NewMessageReceived($message));
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}