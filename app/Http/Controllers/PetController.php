<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pet;
use Illuminate\View\View;
use Helper;

class PetController extends Controller
{
    public function index(): View
    {
        return view('pet.indexPet');
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
}
