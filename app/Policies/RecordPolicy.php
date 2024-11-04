<?php

namespace App\Policies;

use App\Models\Kid;
use App\Models\User;
use App\Models\Record;

class RecordPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAll(User $user)
    {
        return $user->isAdmin();
    }

    public function showOneRecord(User $user, Record $record)
    {
        // Vérifie si l'utilisateur est associé à l'enfant du record
        return $user->kids()->where('kid_id', $record->kid_id)->exists();
    }

    public function startRecording(User $user, Kid $kid)
    {
        // Vérifier si l'utilisateur est un parent ou un assistant maternel
        $isAuthorizedRole = $user->isParent() || $user->isAsmat();
    
        // Vérifier si l'utilisateur est lié à l'enfant via la table pivot kid_user
        $isLinkedToKid = $kid->users->contains($user);
    
        // Retourner vrai si l'utilisateur a un rôle autorisé et est lié à l'enfant
        return $isAuthorizedRole && $isLinkedToKid;
    }

    public function stopRecording(User $user, Kid $kid)
    {
        // Autoriser seulement si l'utilisateur est parent ou assistant maternel associé à l'enfant
        return ($user->isParent() || $user->isAsmat()) && $user->kids->contains($kid);
    }

    public function update(User $user, Record $record)
    {
        // Autorise seulement l'admin ou l'utilisateur associé à l'enregistrement
        return $user->id === $record->user_id;
    }

    public function delete(User $user, Record $record)
    {
        // Autorise seulement l'admin ou l'utilisateur associé à l'enregistrement
        return $user->id === $record->user_id;
    }

}
