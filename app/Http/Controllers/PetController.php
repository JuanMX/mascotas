<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pet;
use App\Models\Adoption;
use Illuminate\View\View;

use Illuminate\Support\Facades\Log;
use Helper;
Use Exception;

class PetController extends Controller
{
    public function index(): View
    {
        return view('pet.indexPet');
    }

    public function indexPetReturnToTheShelter(): View
    {
        return view('pet.indexPetReturnToTheShelter');
    }

    public function listPetsWithStatus(Request $request){

        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = DB::table('pets')
                                    ->select('*')
                                    ->where('status', $request->status)
                                    ->whereNull('deleted_at')
                                    ->get()
                                    ->toArray();
            $jsonReturn['success'] = True;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Something went wrong");
        }

        return response()->json(['data'=>$jsonReturn['data']]);
    }

    public function listAllPets(Request $request){

        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = Pet::all()->toArray();
            $jsonReturn['success'] = True;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Something went wrong");
        }

        return response()->json(['data'=>$jsonReturn['data']]);
    }

    public function create(Request $request){
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $user = Pet::create([
            'name'   => $request->name,
            'type'   => $request->type,
            'age'    => $request->age,
            'status' => 0,
            'note'   => $request->note,
        ]);

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function edit(Request $request){

        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = Pet::findOrFail($request->id);
 
        $record->name   = $request->name;
        $record->type   = $request->type;
        $record->age    = $request->age;
        $record->note   = $request->note;
        
        $record->save();

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function delete(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = Pet::findOrFail($request->id);
 
        $record->delete();
        
        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function listPetAdopterAdoptionWithStatuses(Request $request){

        $jsonReturn = array('success'=>false, 'pet_arrival'=>[], 'data'=>[]);

        $jsonReturn['data'] = DB::table('pets')
            ->join('adoptions', 'pets.id', '=', 'adoptions.pet_id')
            ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
            ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS name'), 'adopters.address', 'adopters.email', 'adopters.phone', 'adopters.type', 'adopters.age', 'adopters.status', 'pets.name AS petname', 'pets.type AS pettype', 'pets.note AS petnote', 'pets.status AS petstatus', 'adoptions.note AS note', 'adopters.id AS adopter_id', 'pets.id AS pet_id', 'adoptions.updated_at AS updated_at')
            ->where('pets.status', $request->pet_status)
            ->where('adoptions.status', $request->adoption_status)
        ->get()->toArray();
        
        $jsonReturn['success'] = true;

        return response()->json($jsonReturn);
    }

    public function petReturnedToTheShelter(Request $request){

        $request->arr_idAdopter_idPet = json_decode($request->arr_idAdopter_idPet);

        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        try{
            DB::transaction(function() use ($request){
                    
                $adoption = Adoption::create([
                    'adopter_id' => $request->arr_idAdopter_idPet[0],
                    'pet_id'     => $request->arr_idAdopter_idPet[1],
                    'status'     => 4, // see Helper getAdoptionStatus
                    'note'       => $request->note,
                ]);

                $pet = Pet::findOrFail($request->arr_idAdopter_idPet[1]);
                $pet->status = 0; // see Helper getPetStatus;
                if($request->append) $pet->note = $pet->note . "\n" . $request->note;
                $pet->save();
            });

            $jsonReturn['success'] = true;
        }catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error']=array('Error while save the data');
            $jsonReturn['data'] = $request;
            return response()->json($jsonReturn, 500);
        }
        
        return response()->json($jsonReturn);
    }
}
