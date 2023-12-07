<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Pet;
use App\Models\Adoption;
use App\Models\Adopter;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; //myTODO put this in all controllers

use Illuminate\Support\Facades\Mail;
use App\Mail\AdoptionDeliberation;

use Helper;
Use Exception; //myTODO put this in all controllers

//omnipresent controller for adoption change status
// and get data for timeline

/*
myTODO
¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?
for each adoption request delete the previous adoptions in adoptions table
in order to prevent duplicates in return requests
¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?¿?
*/

class AdoptionController extends Controller
{
    public function indexAdoption(): View
    {
        return view('adoption.indexAdoption');
    }

    public function indexReturn(): View
    {
        return view('adoption.indexReturn');
    }

    /*
    public function adopterInfoForPet(Request $request){

        $adopter_id = DB::table('adoptions')
            ->select('adopter_id')
            ->where('pet_id', $request->id)
            ->where('status', 1)
            ->latest()
            ->first();

        $adopter_data = Adopter::findOrFail($adopter_id->adopter_id);

        $controller_html = $adopter_data['forename']." ".$adopter_data['surname'].'<br>';
        $controller_html = $controller_html.$adopter_data['address'].'<br>';
        $controller_html = $controller_html.$adopter_data['phone'].'<br>';
        $controller_html = $controller_html.$adopter_data['email'].'<br>';
        $controller_html = $controller_html.'Adopter type: '.Helper::getAdopterType()[$adopter_data['type']].'<br><br>';
        
        $controller_html = $controller_html.'<textarea class="form-control" id="returNote" rows="3" placeholder="Optional note for the requested return"></textarea>';

        return response()->json($controller_html);
    }
    */
    public function indexDeliberate(): View
    {
        return view('adoption.indexDeliberate');
    }

    public function listAdoptRequests(Request $request){

        $jsonReturn['data'] = DB::table('pets')
            ->join('adoptions', 'pets.id', '=', 'adoptions.pet_id')
            ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
            ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS name'), 'adopters.address', 'adopters.email', 'adopters.phone', 'adopters.type', 'adopters.age', 'pets.name AS petname', 'pets.type AS pettype', 'pets.note AS petnote', 'adoptions.note AS note', 'adopters.id AS adopter_id', 'pets.id AS pet_id')
            ->where('pets.status', 1)
            ->where('adopters.status', 0)
            ->whereNull('adopters.deleted_at')
            ->whereNull('pets.deleted_at')
        ->get()->toArray();
        
        return response()->json($jsonReturn);
    }

    public function listPetAndItsAdopter(Request $request){

        $jsonReturn = array('success'=>false, 'pet_arrival'=>[], 'data'=>[]);

        $jsonReturn['data'] = DB::table('pets')
            ->join('adoptions', 'pets.id', '=', 'adoptions.pet_id')
            ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
            ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS name'), 'adopters.address', 'adopters.email', 'adopters.phone', 'adopters.type', 'adopters.age', 'pets.name AS petname', 'pets.type AS pettype', 'pets.note AS petnote', 'adoptions.note AS note', 'adopters.id AS adopter_id', 'pets.id AS pet_id')
            ->where('pets.status', 2)
            ->where('adopters.status', 0)
            ->where('adoptions.status', 0)
            ->whereNull('adopters.deleted_at')
            ->whereNull('pets.deleted_at')
        ->get()->toArray();
        
        $jsonReturn['success'] = true;

        return response()->json($jsonReturn);
    }

    public function listReturnRequests(Request $request){

        $jsonReturn['data'] = DB::table('pets')
            ->join('adoptions', 'pets.id', '=', 'adoptions.pet_id')
            ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
            ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS name'), 'adopters.address', 'adopters.email', 'adopters.phone', 'adopters.type', 'adopters.age', 'pets.name AS petname', 'pets.type AS pettype', 'pets.note AS petnote', 'adoptions.note AS note', 'adopters.id AS adopter_id', 'pets.id AS pet_id')
            ->where('adopters.status', 0)
            ->where('pets.status', 3)
            ->where('adoptions.status', 3)
        ->get()->toArray();
        return response()->json($jsonReturn);
    }
    

    public function indexTimeline(): View
    {
        return view('adoption.indextimeline');
    }

    public function timelinePet(Request $request){

        $jsonReturn = array('success'=>false, 'pet_arrival'=>[], 'data'=>[]);
        
        try {
            $jsonReturn['pet_arrival'] = Pet::find($request->id)->toArray();
            
            $petHistorical_noArray = DB::table('adoptions')
                ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
                ->select('adoptions.*', 'adopters.forename', 'adopters.surname', 'adopters.type')
                ->where('adoptions.pet_id', $request->id)
                ->orderBy('adoptions.created_at','asc')
            ->get();

            $petHistorical_noArray->transform(function($i) {
                return (array)$i;
            });
            $petHistorical = $petHistorical_noArray->toArray();

            $htmlTimeline = '<div class="timeline">';
            
            $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="'.Helper::getColorArrivalShelter().'">'.Carbon::parse($jsonReturn['pet_arrival']['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
            
            $htmlTimeline = $htmlTimeline.'<div><i class="'.Helper::getIconArrivalShelter().' '.Helper::getColorArrivalShelter().'"></i><div class="timeline-item"><h3 class="timeline-header">';
            
            $htmlTimeline = $htmlTimeline.'Arrival at the shelter</h3>';

            $htmlTimeline = $htmlTimeline.'<div class="timeline-body"> Recent Note: <br>'.$jsonReturn['pet_arrival']['note'];

            $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
            foreach($petHistorical as $historical){
                    
                $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="'.Helper::getAdoptionColor()[$historical['status']].'">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                
                $htmlTimeline = $htmlTimeline.'<div><i class="'.Helper::getAdoptionIcon()[$historical['status']]. ' '.Helper::getAdoptionColor()[$historical['status']].'"></i><div class="timeline-item"><h3 class="timeline-header">';
                
                $htmlTimeline = $htmlTimeline.Helper::getAdoptionStatus()[$historical['status']].' (by): '.$historical['forename']." ".$historical['surname'].' Type '.Helper::getAdopterType()[$historical['type']].'</h3>';

                $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                
            }
            $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-flag-checkered"></i></div></div>';
            $jsonReturn['success'] = True;
            $jsonReturn['data'] = $htmlTimeline;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Something went wrong");
        }

        return response()->json($jsonReturn);
    }



    public function timelineAdopter(Request $request){

        $jsonReturn = array('success'=>false, 'adopter_data'=>[],'data'=>[]);
        
        try {
            $jsonReturn['adopter_data'] = Adopter::find($request->id)->toArray();

            $adopterHistorical_noArray = DB::table('adoptions')
                ->join('pets', 'pets.id', '=', 'adoptions.pet_id')
                ->select('adoptions.*', 'pets.name', 'pets.type')
                ->where('adoptions.adopter_id', $request->id)
                ->orderBy('adoptions.created_at','asc')
            ->get();

            $adopterHistorical_noArray->transform(function($i) {
                return (array)$i;
            });
            $adopterHistorical = $adopterHistorical_noArray->toArray();

            $htmlTimeline = '<div class="timeline">';
            foreach($adopterHistorical as $historical){
    
                $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="'.Helper::getAdoptionColor()[$historical['status']].'">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                
                $htmlTimeline = $htmlTimeline.'<div><i class="'.Helper::getAdoptionIcon()[$historical['status']]." ".Helper::getAdoptionColor()[$historical['status']].'"></i><div class="timeline-item"><h3 class="timeline-header">';
                
                $htmlTimeline = $htmlTimeline.Helper::getAdoptionStatus()[$historical['status']].' (for): '.$historical['type']." ".$historical['name'].'</h3>';

                $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
            }
            $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-flag-checkered"></i></div></div>';
            $jsonReturn['success'] = True;
            $jsonReturn['data'] = $htmlTimeline;
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error'] = array("Something went wrong");
        }

        return response()->json($jsonReturn);
    }


    public function adoptionRequest(Request $request){

        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        try{
            DB::transaction(function() use ($request){
        
                $adopter = Adopter::firstOrCreate(
    
                    [
                        'forename' => $request->forename, 
                        'surname' => $request->surname, 
                        'phone' => $request->phone, 
                        'type' => $request->type
                    ],
    
                    [
                        'email' => $request->email, 
                        'address' => $request->address,
                        'age' => $request->age,
                        'status' => 0
                    ]
                );
                $adopter->email = $request->email;
                $adopter->address = $request->address;
                $adopter->age = $request->age;
                $adopter->save();
    
                $adoption = Adoption::create([
                    'adopter_id'   => $adopter->id,
                    'pet_id'   => $request->petid,
                    'status' => 0,
                    'note'   => $request->note,
                ]);
    
                $pet = Pet::findOrFail($request->petid);
                $pet->status = 1;
                $pet->save();
            });
    
            $jsonReturn['success'] = true;
        }catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error']=array('Something went wrong');
        }

        return response()->json($jsonReturn);
    }

    public function returnRequest(Request $request){
        sleep(3);
        $request->arr_adopterId_petId = json_decode($request->arr_adopterId_petId);

        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        try{
            DB::transaction(function() use ($request){
    
                $adoption = Adoption::create([
                    'adopter_id' => $request->arr_adopterId_petId[0],
                    'pet_id'     => $request->arr_adopterId_petId[1],
                    'status'     => 3,
                    'note'       => $request->note,
                ]);
    
                $pet = Pet::findOrFail($request->arr_adopterId_petId[1]);
                $pet->status = 3;
                $pet->save();
            });
    
            $jsonReturn['success'] = true;
        }catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error']=array('Something went wrong');
        }
        return response()->json($jsonReturn);
    }

    public function adoptionDeliberated(Request $request){

        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        try{
            DB::transaction(function() use ($request){
        
                $request->arr_idAdopter_idPet_accepted = json_decode($request->arr_idAdopter_idPet_accepted);
                
                $adoption = Adoption::create([
                    'adopter_id'   => $request->arr_idAdopter_idPet_accepted[0],
                    'pet_id'   => $request->arr_idAdopter_idPet_accepted[1],
                    'status' => $request->arr_idAdopter_idPet_accepted[2] ? 1 : 2, // see Helper getAdoptionStatus
                    'note'   => $request->note,
                ]);

                $pet = Pet::findOrFail($request->arr_idAdopter_idPet_accepted[1]);
                $pet->status = $request->arr_idAdopter_idPet_accepted[2] ? 2 : 5; // see Helper getPetStatus;
                $pet->save();
                
                $adopter = Adopter::findOrFail($request->arr_idAdopter_idPet_accepted[0]);
                
                Mail::to($adopter->email)->send(new AdoptionDeliberation( $adoption, $adopter, $pet, 0 ));
            });

            $jsonReturn['success'] = true;
        }catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error']=array('Error at store data or send the email');
            return response()->json($jsonReturn, 500);
        }

        return response()->json($jsonReturn);
    }

    public function returnDeliberated(Request $request){

        $request->arr_idAdopter_idPet_isAccepted = json_decode($request->arr_idAdopter_idPet_isAccepted);
        
        $jsonReturn = array('success'=>false, 'error'=>[], 'data'=>[]);

        try{
            DB::transaction(function() use ($request){
                
                $adoption = Adoption::create([
                    'adopter_id' => $request->arr_idAdopter_idPet_isAccepted[0],
                    'pet_id'     => $request->arr_idAdopter_idPet_isAccepted[1],
                    'status'     => $request->arr_idAdopter_idPet_isAccepted[2] ? 7 : 8, // see Helper getAdoptionStatus
                    'note'       => $request->note,
                ]);

                $pet = Pet::findOrFail($request->arr_idAdopter_idPet_isAccepted[1]);
                $pet->status = $request->arr_idAdopter_idPet_isAccepted[2] ? 0 : 2; // see Helper getPetStatus;
                $pet->save();
                
                $adopter = Adopter::findOrFail($request->arr_idAdopter_idPet_isAccepted[0]);
                
                Mail::to($adopter->email)->send(new AdoptionDeliberation( $adoption, $adopter, $pet, 1 ));
            });

            $jsonReturn['success'] = true;
        }catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success']=false;
            $jsonReturn['error']=array('Error at store data or send the email');
            $jsonReturn['data'] = $request;
            return response()->json($jsonReturn, 500);
        }

        return response()->json($jsonReturn);
    }

    public function indexDeliberateReturn(): View
    {
        return view('adoption.indexDeliberateReturn');
    }
}
