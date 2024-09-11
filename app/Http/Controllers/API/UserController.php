<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère tous les utilisateurs
        $users = User::all();
        
        // On retourne les informations des utilisateurs en format JSON
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    // Vérifiez que l'utilisateur est autorisé à mettre à jour ce modèle
    $this->authorize('update', User::class);

    // Trouvez l'utilisateur par ID
    $user = User::find($id);

    // Retournez une erreur 404 si l'utilisateur n'existe pas
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Validation des données fournies dans la requête
    $validatedData = $request->validate([
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|min:8|confirmed',
        'city' => 'sometimes|nullable|string|max:255',
        'zip_code' => 'sometimes|nullable|string|max:10'
    ]);

    // Vérifiez si des données ont été envoyées pour la mise à jour
    if (empty($validatedData)) {
        return response()->json(['message' => 'No data provided for update'], 400);
    }

    // Mise à jour des champs du modèle utilisateur avec les données validées
    $user->fill($validatedData);

    // Hash le mot de passe si une nouvelle valeur est fournie
    if ($request->has('password')) {
        $user->password = Hash::make($request->input('password'));
    }

    // Sauvegardez les changements dans la base de données
    $user->save();

    // Retournez une réponse JSON indiquant que la mise à jour a réussi
    return response()->json([
        'message' => 'User successfully updated'
    ], 200);
}

    
}
