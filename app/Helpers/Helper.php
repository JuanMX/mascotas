<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function getPetType(){

        $pet_type = [
            0 => 'DOG',
            1 => 'BIRD',
            2 => 'CAT',
            3 => 'FISH',
            4 => 'PLANT',
        ];

        return $pet_type;
    }

    public static function getAdopterType(){

        $adopter_type = [
            0 => 'PERSON',
            1 => 'BODEGA',
            2 => 'NEGOCIO',
            3 => 'BOMBERO',
            4 => 'EJERCITO',
            5 => 'FARM',
            6 => 'CRIADERO',
        ];
        
        return $adopter_type;
    }

    public static function getPetStatus(){

        $pet_status = [
            0 => 'NOT ADOPTED',
            1 => 'ADOPTED',
            2 => 'REMOVED',
        ];

        return $pet_status;
    }

    public static function getAdoptionStatus(){

        $adoption_status = [
            0 => 'REQUEST ADOPTION',
            1 => 'ACCEPTED ADOPTION',
            2 => 'REJECTED ADOPTION',
            3 => 'RETURN PET',
            4 => 'REJECT REGISTER',
        ];

        return $adoption_status;
    }

    public static function getAdopterStatus(){

        $adopter_status = [
            0 => 'CUSTOMER',
            1 => 'REJECTED',
        ];

        return $adopter_status;
    }
}