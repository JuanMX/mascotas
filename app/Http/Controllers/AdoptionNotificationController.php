<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pet;
use App\Models\Adopter;
use App\Models\Adoption;
use Illuminate\Support\Facades\DB;

use Illuminate\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

use Helper;
Use Exception;

class AdoptionNotificationController extends Controller
{
    public function index(Request $request, ?string $id = null): View
    {
        if(is_numeric($id)){
            $id = intval($id);
        }
        else if(is_null($id)){
            $id = 0;
        }
        else{
            abort(404);
        }
            

        return view('adoption_notification.indexAdoptionNotification', [
            'id' => $id
        ]);
    }

    public function read(Request $request){

        $jsonReturn = array('success'=>false,'data'=>[]);
        
        try {
            $jsonReturn['data'] = DB::table('adoptions')
            ->join('pets', 'pets.id', '=', 'adoptions.pet_id')
            ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
            ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS adopters_name'), 'adopters.type AS adopters_type', 'adopters.phone AS adopters_phone', 'adopters.email AS adopters_email', 'adopters.age AS adopters_age', 'pets.name AS pets_name', 'pets.type AS pets_type', 'pets.age AS pets_age', 'pets.note AS pets_note', 'adoptions.status AS adoptions_status', 'adoptions.note AS adoptions_note', 'adoptions.created_at AS adoptions_date', 'adoptions.id AS adoptions_id')
            ->get();

            $jsonReturn['success'] = True;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Error reading stored data");
            return response()->json($jsonReturn, 500);
        }

        return response()->json($jsonReturn);
    }
}
