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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function myKids()
    {
    
        $userId = Auth::id();
        $user = User::find($userId);

        return response()->json($user->kids); // Retourne les enfants au format JSON
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kid $kid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kid $kid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kid $kid)
    {
        //
    }
}
