<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;  // CHANGE ICI
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewMessageReceived implements ShouldBroadcastNow  // CHANGE ICI
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        Log::info('Event NewMessageReceived créé pour: ' . $message->email_client);
    }

    public function broadcastOn()
    {
        $hash = md5($this->message->email_client);
        Log::info('1 - Préparation broadcast pour: ' . $hash);
        Log::info('2 - Canal privé: chat.' . $hash);
        Log::info('3 - Canal public: new-messages');
        
        return [
            new PrivateChannel('chat.' . $hash),
            new Channel('new-messages')
        ];
    }

    public function broadcastWith()
    {
        Log::info('5 - broadcastWith() exécuté pour: ' . $this->message->email_client);
        
        return [
            'id' => $this->message->id,
            'nom_complet' => $this->message->nom_complet,
            'email_client' => $this->message->email_client,
            'message' => $this->message->message,
            'reponse_admin' => $this->message->reponse_admin,
            'repondu' => $this->message->repondu,
            'created_at' => $this->message->created_at->toISOString()
        ];
    }
}