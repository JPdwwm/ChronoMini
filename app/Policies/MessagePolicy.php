<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function viewAll(User $user)
    {
        // Seul l'administrateur peut voir tous les messages
        return $user->isAdmin();
    }

    public function updateStatus(User $user, Message $message)
    {
        // Seul l'administrateur peut changer le statut d'un message
        return $user->isAdmin();
    }

    public function view(User $user, Message $message)
    {
        // Autorise l'accès si l'utilisateur est l'admin ou l'auteur du message
        return $user->isAdmin() || $user->id === $message->user_id;
    }
}
