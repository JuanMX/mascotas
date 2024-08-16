<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Adopter;

use Helper;
Use Exception;

class AdopterController extends Controller
{
    public function listAllAdopters(Request $request){

        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = Adopter::all()->toArray();
            $jsonReturn['success'] = True;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Something went wrong");
        }

        return response()->json(['data'=>$jsonReturn['data']]);
    }

    public function adopterSearchField(Request $request){
        
        $jsonReturn = array('success'=>false, 'data'=>[], 'data_count'=>0,'message'=>'');

        $phone_number = preg_replace("/[^0-9]/", "", $request->inputSearch); //myTODO use the Helper

        $query = Adopter::orWhere('email', $request->inputSearch)->orWhere('phone', $phone_number)->orWhere('surname', 'like', '%'.$request->inputSearch.'%')->get();
        
        $jsonReturn['data'] = $query->toArray();

        $jsonReturn['data_count'] = $query->count();
        
        if($jsonReturn['data'] && $jsonReturn['data_count'] > 0){
            $jsonReturn['success'] = true;
        }
        else{
            $jsonReturn['message'] = $request->inputSearch.' did not produce any result';
        }
        
        return response()->json($jsonReturn);
    }
}
