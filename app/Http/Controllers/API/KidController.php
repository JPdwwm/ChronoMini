<?php

namespace App\Http\Controllers\API;

use App\Models\Kid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KidController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Vérifiez que l'utilisateur est autorisé à voir la liste de tous les utilisateurs
        $this->authorize('viewAllKids', Kid::class);

        // On récupère tous les utilisateurs
        $kids = Kid::all();
                
        // On retourne les informations des utilisateurs en format JSON
        return response()->json($kids);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createKid(Request $request)
    {
        // Get authenticated user
        $id = Auth::user()->id;
        $user = User::find($id);

        // Validation des données d'entrée
        $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'birth_date' => 'required|date',
        ]);

        // Créer un nouvel enfant avec les données validées
        $kid = Kid::create($validatedData);

        // Associer l'enfant à l'utilisateur authentifié
        $user->kids()->attach($kid->id);

        return response()->json($kid, 201); // 201 Created
    }

    public function showOneKid(Kid $kid){

        $this->authorize('showOneKid', $kid, Kid::class);

        $kid = Kid::find($kid);

        if (!$kid) {
            return response()->json(['message' => 'Kid not found'], 404);
        }

        return response()->json($kid);
    }

    /**
     * Display the specified resource.
     */
    public function showMyKids()
    {
        // Get authenticated user
        $id = Auth::user()->id;
        $user = User::find($id);

        if ($user->kids->isEmpty()) {
            return response()->json(['message' => 'No kids found.'], 200); // OK avec message
        }
        return response()->json($user->kids); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateKid(Request $request, Kid $kid)
    {
        // policy vérifiant que l'utilisateur est authentifié (avec role parent) et qu'il est bien lié a l'enfant 
        $this->authorize('updateKid', $kid, Kid::class);

        $validatedData = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
        ]);

        $kid->update($validatedData);

        return response()->json([
            'message' => 'Kid successfully updated',
            'kid' => $kid
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteKid(Kid $kid)
    {

        $this->authorize('deleteKid', $kid);

        $id = Auth::user()->id;
        $user = User::find($id);
    
        // Détacher l'enfant de l'utilisateur
        $user->kids()->detach($kid->id);
    
        // Vérifie si l'enfant est toujours rattaché à d'autres utilisateurs
        $remainingAssociations = $kid->users()->count();
    
        // S'il n'y a plus d'associations, on peut supprimer l'enfant de la base de données
        if ($remainingAssociations === 0) {
            $kid->delete();
            return response()->json(['message' => 'Kid successfully deleted.'], 200);
        }else{
            return response()->json(['message' => 'Kid successfully detached from user.'], 200);
        }
    }
}
