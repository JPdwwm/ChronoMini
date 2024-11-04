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
        $this->authorize('startRecording', Record::class);
    
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
        $user = Auth::user();

        $this->authorize('stopRecording', [Record::class, $kid]);
        
        $activeRecord = Record::where('kid_id', $kid->id)
                              ->whereNull('pick_up_hour')
                              ->first();
    
        if (!$activeRecord) {
            return response()->json(['message' => 'No active record found for this child.'], 404);
        }
    
        $activeRecord->pick_up_hour = now()->toTimeString();
        $activeRecord->save();
    
        // Calcul de la durée en heures et minutes
        $dropHour = Carbon::parse($activeRecord->drop_hour);
        $pickUpHour = Carbon::now();
        $durationInMinutes = $dropHour->diffInMinutes($pickUpHour);
    
        // Calcul des heures et minutes
        $hours = floor($durationInMinutes / 60);
        $minutes = $durationInMinutes % 60;
    
        // Mise à jour en heures décimales
        $activeRecord->amount_hours = $durationInMinutes / 60;
        $activeRecord->save();
    
        return response()->json([
            'message' => 'Drop-off successfully stopped',
            'record' => $activeRecord,
            'duration' => [
                'hours' => $hours,
                'minutes' => $minutes
            ]
        ], 200);
    }
    
    /**
     * Display the specified resource.
     */
    public function showOneRecord(Record $record)
    {   
        $this->authorize('showOneRecord', $record);

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
        $this->authorize('update', $record);
    
        $validated = $request->validate([
            'drop_hour' => 'sometimes|date_format:H:i:s',
            'pick_up_hour' => 'sometimes|date_format:H:i:s',
            'amount_hours' => 'sometimes|numeric|min:0',
        ]);
    
        $record->update($validated);
    
        return response()->json(['message' => 'Record updated successfully', 'record' => $record]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteRecord(Record $record)
    {
        $this->authorize('delete', $record);
    
        $record->delete();
    
        return response()->json(['message' => 'Record deleted successfully']);
    }
}