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
        return view('welcome');
    }

    public function dashboardTotal() 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>[]);

        try{

            $jsonReturn['data']['total']       = DB::table('pets')->count();
            $jsonReturn['data']['adopted']     = DB::table('pets')->where('status', 2)->count();
            $jsonReturn['data']['not_adopted'] = DB::table('pets')->where('status', 0)->count();
            $jsonReturn['data']['deleted']     = DB::table('pets')->whereNotNull('deleted_at')->count();
            $jsonReturn['success'] = True;
        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = array("Something went wrong while obtaining the data for the Total Widget");
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);

        }

        return response()->json($jsonReturn);
    }

    public function dashboardPendingsAndRequests() 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>[]);

        try{

            //Query

            $jsonReturn['success'] = True;
        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = array("Something went wrong while obtaining the data for the Widget requests and pets pending to...");
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }

    public function dashboardBarChart() 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>[]);

        try{

            //Query

            $jsonReturn['success'] = True;
        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = array("Something went wrong while obtaining the data for the bar chart");
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }

    public function dashboardLatestAdoptionsActions() 
    {
        $jsonReturn = array('success'=>false, 'data'=>[], 'error'=>[]);

        try{

            //Query

            $jsonReturn['success'] = True;
        }catch(Exception $e){

            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            $jsonReturn['success'] = false;
            $jsonReturn['error']   = array("Something went wrong while obtaining the data for the table with the latest adoptions actions");
            $jsonReturn['data']    = $request;
            return response()->json($jsonReturn, 404);
            
        }

        return response()->json($jsonReturn);
    }
}
