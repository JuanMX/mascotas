<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use App\Models\PetType;
use App\Models\AdopterType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Request;
Use Exception;

class Helper
{
    public static function getPetType(){
        $pet_type = PetType::pluck('name','id')->toArray();
        return $pet_type;
    }

    public static function getAdopterType(){
        $adopter_type = AdopterType::pluck('name','id')->toArray();
        return $adopter_type;
    }

    public static function getPetStatus(){

        $pet_status = [
            0 => 'NOT ADOPTED',
            1 => 'REQUEST ADOPTION',
            2 => 'ADOPTED',
            3 => 'REQUESTED RETURN',
            4 => 'REMOVED',
            5 => 'ACCEPTED RETURN AND PENDING TO ARRIVE AT THE SHELTER',
            6 => 'ACCEPTED ADOPTION AND PENDING TO BE PICKED UP AT THE SHELTER',
        ];

        return $pet_status;
    }

    public static function getAdoptionStatus(){

        $adoption_status = [
            0 => 'REQUESTED ADOPTION',
            1 => 'ACCEPTED ADOPTION',
            2 => 'REJECTED ADOPTION',
            3 => 'REQUESTED RETURN',
            4 => 'RETURNED PET',
            5 => 'REJECTED REGISTER ADOPTER',
            6 => 'REJECTED REGISTER PET',
            7 => 'ACCEPTED RETURN',
            8 => 'REJECTED RETURN',
            9 => 'PICKED UP PET',
        ];

        return $adoption_status;
    }

    public static function getAdoptionColor(){

        $color = [
            0 => 'bg-blue',
            1 => 'bg-green',
            2 => 'bg-red',
            3 => 'bg-yellow',
            4 => 'bg-purple',
            5 => 'bg-red',
            6 => 'bg-red',
            7 => 'bg-orange',
            8 => 'bg-orange',
            9 => 'bg-blue',
        ];

        return $color;
    }
    public static function getAdoptionIcon(){

        $icon = [
            0 => 'fas fa-hand-holding-heart',
            1 => 'fas fa-check-circle',
            2 => 'fas fa-times-circle',
            3 => 'fas fa-heart-broken',
            4 => 'fas fa-arrow-alt-circle-down',
            5 => 'fas fa-ban',
            6 => 'fas fa-ban',
            7 => 'fas fa-check-circle',
            8 => 'fas fa-times-circle',
            9 => 'fas fa-arrow-alt-circle-up',
        ];

        return $icon;
    }

    public static function getColorArrivalShelter(){
        return 'bg-purple';
    }
    public static function getIconArrivalShelter(){
        return 'fas fa-arrow-alt-circle-down';
    }

    public static function getAdopterStatus(){

        $adopter_status = [
            0 => 'CUSTOMER',
            1 => 'REJECTED',
        ];

        return $adopter_status;
    }

    public static function project_root(){
        return Request::root();
    }

    public static function pluckSimpleCatalogue( string $catalogue ){
        
        $result = array('placeholder'=>'Invalid catalogue. Error getting data', 'data'=>[]);
        
        try {
            if(str_ends_with($catalogue, env('SIMPLE_CATALOGUE_SUFFIX'))){
                $result['data'] = DB::table($catalogue)->whereNull('deleted_at')->pluck('name','id')->toArray();
                $result['placeholder'] = 'Please select';
            }
            else{
                Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . __LINE__ . '): ' . 'Catalogue does not exists or name not ends with: ' . env('SIMPLE_CATALOGUE_SUFFIX') );
            }
        } catch(Exception $e) {
            Log::error(__CLASS__ . '/' . __FUNCTION__ . ' (Line: ' . $e->getLine() . '): ' . $e->getMessage());
            return $result;
        }
        
        return $result;
    }

    public static function cleanPhoneNumber($phone_with_numbers_and_symbols){//only returns the numbers: '+01 (234) 5678' => '012345678'
        $phone_cleaned = preg_replace("/[^0-9]/", "", $phone_with_numbers_and_symbols);
        return $phone_cleaned;
    }
}