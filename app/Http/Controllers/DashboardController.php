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

class DashboardController extends Controller
{
    public function index(): View
    {
        $widget_table_data = DB::table('adoptions')
            ->join('pets', 'pets.id', '=', 'adoptions.pet_id')
            ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
            ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS name'), 'pets.name AS pet_name', 'pets.type AS pet_type', 'adoptions.status AS status', 'adoptions.note AS note')
        ->latest('adoptions.created_at')->limit(10)->get();

        return view('welcome', ['widget_table_data'=>$widget_table_data]);
    }

    public function dashboardTotal(Request $request) 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>'');
        
        try{

            $jsonReturn['data']['total']       = DB::table('pets')->count();
            $jsonReturn['data']['adopted']     = DB::table('pets')->where('status', 2)->count();
            $jsonReturn['data']['not_adopted'] = DB::table('pets')->where('status', 0)->whereNull('deleted_at')->count();
            $jsonReturn['data']['deleted']     = DB::table('pets')->whereNotNull('deleted_at')->count();
            $jsonReturn['success'] = True;

        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = "Something went wrong while obtaining the data for the Total Widget";
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);

        }

        return response()->json($jsonReturn);
    }

    public function dashboardPetsPending(Request $request) 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>'');

        try{

            $jsonReturn['data']['current_not_adopted'] = Pet::where('status', 0)->count();
            $jsonReturn['data']['pending_picked_up']   = Pet::where('status', 6)->count();
            $jsonReturn['data']['pending_return']      = Pet::where('status', 5)->count();

            $jsonReturn['success'] = True;
        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = "Something went wrong while obtaining the data for the Widget of Pets pending to be picked up and return";
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }

    public function dashboardPetsRequests(Request $request) 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>'');
        
        try{

            $jsonReturn['data']['pets_requested_adoption'] = Pet::where('status', 1)->count();
            $jsonReturn['data']['pets_requested_return']   = Pet::where('status', 3)->count();
            $jsonReturn['success'] = True;

        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = "Something went wrong while obtaining the data for the Widget of Pets requetsed adoption and return";
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }

    public function dashboardBarChart(Request $request) 
    {
        $jsonReturn = array('success'=>false, 'data_arrivals'=>[], 'data_adoptions'=>[],'error'=>'');

        try{
            $months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
            $year = date("Y");

            foreach ($months as $month) {

                $month_count = Pet::where('status', 0)
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                ->count();
                array_push($jsonReturn['data_arrivals'], $month_count);
                
                $month_count = Pet::where('status', 2)
                    ->whereMonth('updated_at', $month)
                    ->whereYear('updated_at', $year)
                ->count();

                array_push($jsonReturn['data_adoptions'], $month_count);
            }

            $jsonReturn['success'] = True;
        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = "Something went wrong while obtaining the data for the bar chart";
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }

    public function dashboardLatestAdoptionsActions(Request $request) 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>'');

        try{

            $jsonReturn['data'] = DB::table('adoptions')
                ->join('pets', 'pets.id', '=', 'adoptions.pet_id')
                ->join('adopters', 'adopters.id', '=', 'adoptions.adopter_id')
                ->select(DB::Raw('CONCAT(adopters.forename, " ", adopters.surname) AS name'), 'pets.name AS pet_name', 'pets.type AS pet_type', 'adoptions.status AS status', 'adoptions.note AS note')
            ->latest('adoptions.created_at')->limit(10)->get()->toArray();

            $jsonReturn['success'] = True;

        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = "Something went wrong while obtaining the data for the table with the latest adoptions actions";
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }
}
