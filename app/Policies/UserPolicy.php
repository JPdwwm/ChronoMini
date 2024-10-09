<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(?User $user)
    {
        if ($user->isAdmin()){
            return true;
        }
        return false;
    }

    public function viewAll(?User $user)
    {
        if ($user->isAdmin()){
            return true;
        }
        return false;
    }

    public function viewOne(?User $user)
    {
        if ($user->isAdmin()){
            return true;
        }
        return false;
    }

    public function delete(User $authUser, User $user)
    {
        // Permet la suppression si l'utilisateur authentifié est admin ou s'il s'agit de son propre compte
        return $authUser->isAdmin() || $authUser->id === $user->id;
    }

    /**
     * Autorisation pour un utilisateur de mettre à jour son propre profil.
     */
    public function updateMe(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id; // Un utilisateur peut mettre à jour son propre profil
    }


}
