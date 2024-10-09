<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function profile()
    {
        // Récupère l'utilisateur authentifié
        $user = Auth::user();

        // Retourne les informations de l'utilisateur en format JSON
        return response()->json($user);
    }

    public function update(Request $request)
    {
        // Get authenticated user
        $id = Auth::user()->id;
        $user = User::find($id);
    
        // Vérifiez l'autorisation avant de mettre à jour
        $this->authorize('updateMe', [$user, $user]);
    
        // Validation des données fournies dans la requête
        $validatedData = $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->where(function ($query) use ($user) {
                    return $query->where('role_id', $user->role_id)
                                 ->where('id', '!=', $user->id);
                }),
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'city' => 'sometimes|nullable|string|max:255',
            'zip_code' => 'sometimes|nullable|string|max:10',
        ]);
    
        // Mise à jour des champs du modèle utilisateur avec les données validées
        $user->fill($validatedData);
    
        // Hash le mot de passe si un nouveau mdp est fourni dans la requête
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }
    
        // Sauvegardez les changements dans la base de données
        $user->save();
    
        // Retournez une réponse JSON indiquant que la mise à jour a réussi, avec les données mises à jour
        return response()->json([
            'message' => 'Profile successfully updated',
            'user' => $user // Retourne l'utilisateur mis à jour
        ], 200);
    }
    
    public function destroy()
    {
        // Get authenticated user
        $id = Auth::user()->id;
        $user = User::find($id);

        // Vérifiez l'autorisation avant de supprimer
        $this->authorize('delete', $user);

        // Supprime l'utilisateur
        $user->delete();

        return response()->json([
            'message' => 'Profile successfully deleted',
        ], 200);
    }
}
