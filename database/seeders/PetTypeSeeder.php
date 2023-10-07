<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PetType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Helper;

class PetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    $pet_type = Helper::getPetType();

    public function run(): void
    {
        //
        //foreach($pet_type as $type){
            DB::table('pet_types')->insert([
                'name' => 'perro',
            ]);
        //}
    }
}
