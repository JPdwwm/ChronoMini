<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kid;

class KidPolicy
{

    public function viewAllKids(User $user)
    {
        //seuls les administrateurs peuvent voir tous les enfants
        return $user->isAdmin();
    }

    public function viewOneKid(User $user, Kid $kid)
    {
        // Un utilisateur peut voir un enfant s'il est lié à cet enfant (via la table pivot)
        return $user->kids()->where('kid_id', $kid->id)->exists() || $user->isAdmin();
    }
    
    public function updateKid(User $user, Kid $kid)
    {
        // Vérifier que l'utilisateur est un parent et qu'il est lié à l'enfant
        return $user->isParent() && $user->kids()->where('kid_id', $kid->id)->exists();
    }

    public function detachKid(User $user, Kid $kid)
    {
        // Un utilisateur peut détacher un enfant s'il est lié à cet enfant
        return $user->kids()->where('kid_id', $kid->id)->exists();
    }

    public function deleteKid(User $user, Kid $kid)
    {
        // Seul un administrateur peut supprimer directement un enfant de la base de données
        return $user->isAdmin();
    }
}
