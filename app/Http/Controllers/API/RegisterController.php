<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validation des champs du formulaire
        $formFields = $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::default()],
            'role_id' => 'required|integer|in:2,3', // in 2:3 signifie que seulement les rôles parent et asmat peuvent être envoyé dans la requête de création 
            'city' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10'
        ]);

        // Vérification si l'utilisateur a déjà un compte avec le même rôle
        $userWithSameRole = User::where('email', $formFields['email'])
            ->where('role_id', $formFields['role_id'])
            ->exists();

        if ($userWithSameRole) {
            return response()->json([
                'message' => 'You already have an account with this role.'
            ], 409); // Code 409 pour Conflit
        }

        $user = new User();
        $user->fill($formFields);
        $user->password = Hash::make($formFields['password']);
        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'User' => $user,
            'Message' => 'User successfully created'
        ], 201); 
    }
}
