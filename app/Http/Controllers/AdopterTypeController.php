<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\AdopterType;

class AdopterTypeController extends Controller
{
    public function index(): View
    {
        return view('catalogue.adoptertype');
    }

    public function listAdopterType(Request $request){

        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = AdopterType::all()->toArray();
            $jsonReturn['success'] = True;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Something went wrong");
        }

        return response()->json(['data'=>$jsonReturn['data']]);
    }

    public function createType(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $user = AdopterType::create([

            'name' => $request->name,

        ]);

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function editType(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = AdopterType::findOrFail($request->id);
 
        $record->name = $request->name;
        
        $record->save();

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function deleteType(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = AdopterType::findOrFail($request->id);
 
        $record->delete();

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }
}
