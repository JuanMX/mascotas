<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pet;
use Illuminate\View\View;

class PetController extends Controller
{
    public function index(): View
    {
        return view('pet.indexpet');
    }

    public function listPetsWithStatus(Request $request){
        //dd($request->status);
        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = DB::table('pets')
                                    ->select('*')
                                    ->where('status', $request->status)
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
}
