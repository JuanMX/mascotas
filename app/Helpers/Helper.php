<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function getPetType(){
        $pet_type = [
            0 => 'PERRO',
            1 => 'AVE',
            2 => 'GATO',
            3 => 'PEZ',
            4 => 'PLANTA',
        ];
        return $pet_type;
    }

    public static function getAdopterType(){
        $adopter_type = [
            0 => 'PERSONA',
            1 => 'BODEGA',
            2 => 'NEGOCIO',
            3 => 'BOMBERO',
            4 => 'EJERCITO',
            5 => 'GRANJA',
            6 => 'CRIADERO',
        ];
        return $adopter_type;
    }
}