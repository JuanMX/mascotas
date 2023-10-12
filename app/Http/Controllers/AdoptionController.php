<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pet;
use App\Models\Adoption;
use App\Models\Adopter;
use Illuminate\View\View;

use Illuminate\Support\Carbon;

//omnipresent controller for adoption change status
//get data for timeline

class AdoptionController extends Controller
{
    public function index(): View
    {
        return view('adoption.indexhistorical');
    }

    public function historicalPet(Request $request){

        $jsonReturn = array('success'=>false, 'pet_arrival'=>[], 'data'=>[]);
        
        try {
            $jsonReturn['pet_arrival'] = Pet::find($request->id)->toArray();
            //join para obtner el nombre del adoptante
            $petHistorical = Adoption::select('*')->where('pet_id', $request->id)->orderBy('created_at','asc')->get()->toArray();
            
            $htmlTimeline = '<div class="timeline">';
            
            $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-purple">'.Carbon::parse($jsonReturn['pet_arrival']['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
            
            $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-arrow-alt-circle-down bg-purple"></i><div class="timeline-item"><h3 class="timeline-header">';
            
            $htmlTimeline = $htmlTimeline.'Arrival at the shelter</h3>';

            $htmlTimeline = $htmlTimeline.'<div class="timeline-body"> Recent Note: <br>'.$jsonReturn['pet_arrival']['note'];

            $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
            foreach($petHistorical as $historical){
                //myTODO cambiar de action a status despues
                if($historical['action'] == 0){
                    
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-blue">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-hand-holding-heart bg-blue"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Requested adoption by ???</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['action'] == 1){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-green">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-check-circle bg-green"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Accepted adoption for ???</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['action'] == 2){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-red">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-times-circle bg-red"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Rejected Adoption for ???</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['action'] == 3){
                    $htmlTimeline = $htmlTimeline.'<div class="time-label"><span class="bg-yellow">'.Carbon::parse($historical['created_at'])->format('d/M/Y - g:i:s A').'</span></div>';
                    
                    $htmlTimeline = $htmlTimeline.'<div><i class="fas fa-heart-broken bg-yellow"></i><div class="timeline-item"><h3 class="timeline-header">';
                    
                    $htmlTimeline = $htmlTimeline.'Undo Adoption for ???</h3>';

                    $htmlTimeline = $htmlTimeline.'<div class="timeline-body">'.$historical['note'];

                    $htmlTimeline = $htmlTimeline.'</div><div class="timeline-footer"></div></div></div>';
                }
                else if($historical['action'] == 4){
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
}
