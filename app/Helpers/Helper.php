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
            1 => 'REQUEST ADOPTION',
            2 => 'ADOPTED',
            3 => 'REQUESTED RETURN',
            4 => 'REMOVED',
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
        ];

        return $adoption_status;
    }

    public static function getAdoptionColor(){

        $color = [
            0 => 'bg-blue',
            1 => 'bg-green',
            2 => 'bg-red',
            3 => 'bg-yellow',
            4 => 'bg-orange',
            5 => 'bg-red',
            6 => 'bg-red',
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
    public static function rootUrl(){
        return Request::root();
    }
}