<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
Use Exception;

class SimpleCatalogueController extends Controller
{
    public function index(Request $request, string $catalogue): View
    {
        return view('catalogue.indexSimpleCatalogue', [
            'catalogue' => $catalogue
        ]);
    }

    public function create(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = DB::table($request->catalogue)->insert([
            'name' => $request->name,
            'created_at' => now(),
        ]);

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function read(Request $request){

        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = DB::table($request->catalogue)
                                        ->select('*')
                                        ->whereNull('deleted_at')
                                        ->get()
                                        ->toArray();

            $jsonReturn['success'] = True;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Error reading stored data");
            return response()->json($jsonReturn, 404);
        }

        return response()->json($jsonReturn);
    }

    public function update(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = DB::table($request->catalogue)
                    ->where('id', $request->id)
                    ->update(['name' => $request->name, 'updated_at' => now()]);

        $jsonReturn['success'] = true;
        
        return response()->json($jsonReturn);
    }

    public function delete(Request $request){
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        $record = DB::table($request->catalogue)->where('id', $request->id)->update(['deleted_at' => now()]);

        $jsonReturn['success'] = true;

        return response()->json($jsonReturn);
    }
}
