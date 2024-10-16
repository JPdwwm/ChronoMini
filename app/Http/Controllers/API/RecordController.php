<?php

namespace App\Http\Controllers\API;

use App\Models\Kid;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $this->authorize('viewAll', Record::class);

        // On récupère tous les utilisateurs
        $record = Record::all();
                
        // On retourne les informations des utilisateurs en format JSON
        return response()->json($record);
    }

    public function showMyRecords()
    {   
        // Get authenticated user
        $userId = Auth::user()->id;
    
        // On récupère tous les enregistrements de l'utilisateur authentifié
        $records = Record::with('kid') // Charger la relation avec Kid
                         ->where('user_id', $userId)
                         ->paginate(31); // 31 enregistrements par page maximum
    
        // On retourne les enregistrements en format JSON
        return response()->json($records);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function startRecording(Kid $kid)
    {
        // Récupère l'utilisateur authentifié
        $user = Auth::user();
    
        // Vérifier que l'utilisateur est autorisé à enregistrer la dépose pour cet enfant
        // $this->authorize('create', Record::class);
    
        // Vérifier s'il y a déjà un enregistrement en cours pour cet enfant
        $activeRecord = Record::where('kid_id', $kid->id)
                              ->whereNull('pick_up_hour')
                              ->first();
    
        if ($activeRecord) {
            return response()->json([
                'message' => 'An active record already exists for this kid. Please complete it before starting a new one.'
            ], 400);
        }
    
        // Créer un nouvel enregistrement pour la dépose
        $record = Record::create([
            'user_id' => $user->id,
            'kid_id' => $kid->id,
            'drop_hour' => now()->toTimeString(),
            'date' => now()->format('Y-m-d'),
        ]);
    
        return response()->json([
            'message' => 'Drop-off successfully recorded',
            'record' => $record
        ], 201);
    }

    public function stopRecording(Kid $kid)
    {
        // Récupère l'utilisateur authentifié
        $user = Auth::user();
        
        // Vérifier qu'il existe un enregistrement actif pour cet enfant
        $activeRecord = Record::where('kid_id', $kid->id)
                              ->whereNull('pick_up_hour')
                              ->first();
    
        if (!$activeRecord) {
            return response()->json(['message' => 'No active record found for this child.'], 404);
        }
    
        // Mettre à jour l'heure de reprise
        $activeRecord->pick_up_hour = now()->toTimeString(); // ou une heure spécifique
        $activeRecord->save(); // Enregistrer les modifications
    
        // Calculer la durée de garde
        $dropHour = \Carbon\Carbon::parse($activeRecord->drop_hour);
        $pickUpHour = Carbon::now();
        $amountMinutes = $dropHour->diffInMinutes($pickUpHour);
        $amountHours = $amountMinutes / 60; // Convertir en heures
    
        // Mettre à jour le champ amount_hours
        $activeRecord->amount_hours = $amountHours;
        $activeRecord->save();
    
        // Supposons que $amountHours contient ta valeur en heures décimales
        $amountHours = $activeRecord->amount_hours;

        // Obtenir le nombre d'heures entières
        $hours = floor($amountHours);

        // Obtenir le nombre de minutes (en prenant la partie décimale)
        $minutes = round(($amountHours - $hours) * 60);

        // Affichage des résultats
        return response()->json([
            'message' => 'Drop-off successfully stopped',
            'record' => $activeRecord,
            'duration' => [
            'hours' => $hours,
            'minutes' => $minutes]], 200);
    }

    /**
     * Display the specified resource.
     */
    public function showOneRecord(Record $record)
    {   
        $this->authorize('showOneRecord', $record);
        
        $record = Record::find($record);

        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return response()->json($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateRecord(Request $request, Record $record)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteRecord(Record $record)
    {
        //
    }
}
