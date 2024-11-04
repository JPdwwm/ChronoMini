<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Envoie un message à l'admin.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'title' => $request->title,
            'message' => $request->message,
            'user_id' => Auth::id(),
            'status' => 'sent',
        ]);

        return response()->json(['message' => 'Message sent successfully', 'data' => $message], 201);
    }

    /**
     * Affiche tous les messages pour l'admin.
     */
    public function getAllMessages()
    {
        $this->authorize('viewAll', Message::class); // Assure que seul l'admin a l'accès

        $messages = Message::with('user')->get();

        return response()->json($messages);
    }

    /**
     * Affiche les messages de l'utilisateur authentifié.
     */
    public function getMyMessages()
    {
        $messages = Message::where('user_id', Auth::id())->get();

        return response()->json($messages);
    }

    /**
     * Marque un message comme lu par l'admin.
     */
    public function markAsRead(Message $message)
    {
        $this->authorize('updateStatus', $message); // Autoriser seulement si c'est l'admin

        $message->status = 'read';
        $message->save();

        return response()->json(['message' => 'Message marked as read']);
    }
}
