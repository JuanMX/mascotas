<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pet;
use App\Models\Adoption;
use App\Models\Adopter;
use Illuminate\View\View;

use Helper;
use Illuminate\Support\Carbon;

//omnipresent controller for adoption change status
//get data for timeline

class AdoptionController extends Controller
{
    public function indexAdoption(): View
    {
        return view('adoption.indexadoption');
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
            
            $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-purple">'.Carbon::parse($jsonReturn['pet_arrival']['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
            
            $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-arrow-alt-circle-down bg-purple"></i><div class="timeline-item"><h3 class="timeline-header">';
            
            $htmlTimeline = $htmlTimeline.'Arrival at the shelter</h3>';

            $htmlTimeline = $htmlTimeline.'<div class="timeline-body"> Recent Note: <br>'.$jsonReturn['pet_arrival']['note'];

            $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
            foreach($petHistorical as $historical){
                
                if($historical['status'] == 0){
                    
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-blue">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-hand-holding-heart bg-blue"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Requested adoption by '.$historical['forename']." ".$historical['surname'].' Type '.Helper::getAdopterType()[$historical['type']].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 1){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-green">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-check-circle bg-green"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Accepted adoption requested by '.$historical['forename']." ".$historical['surname'].' Type '.Helper::getAdopterType()[$historical['type']].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 2){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-red">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-times-circle bg-red"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Rejected adoption requested by '.$historical['forename']." ".$historical['surname'].' Type '.Helper::getAdopterType()[$historical['type']].' </h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 3){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-yellow">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-heart-broken bg-yellow"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Undo Adoption for '.$historical['forename']." ".$historical['surname'].' Type '.Helper::getAdopterType()[$historical['type']].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 5){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-red">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-ban bg-red"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Removed pet</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
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

                if($historical['status'] == 0){
                    
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-blue">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-hand-holding-heart bg-blue"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Requested adoption for '.$historical['type']." ".$historical['name'].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 1){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-green">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-check-circle bg-green"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Accepted adoption for '.$historical['type']." ".$historical['name'].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 2){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-red">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-times-circle bg-red"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Rejected Adoption for '.$historical['type']." ".$historical['name'].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 3){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-yellow">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-heart-broken bg-yellow"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Undo Adoption for '.$historical['type']." ".$historical['name'].'</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['status'] == 4){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-red">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-ban bg-red"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Removed Adopter</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
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
}
