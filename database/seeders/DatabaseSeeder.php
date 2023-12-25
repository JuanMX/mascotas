<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\PetType;
use App\Models\Pet;

use Helper;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        Pet::factory()->count(10)->create();
        
        $currentDate = Carbon::now();
        $agoDate = $currentDate->subDays($currentDate->dayOfWeek)->subWeek();
        
        DB::table('pets')->insert([
            'name' => 'Queso',
            'age' => rand(0,3),
            'type' => 1,
            'status' => 0,
            'note' => 'Mascota muy juguetona',
            'created_at' => $agoDate,
            'updated_at' => $agoDate,
        ]);
        DB::table('pets')->insert([
            'name' => 'Chester',
            'age' => rand(0,3),
            'type' => 1,
            'status' => 2,
            'note' => 'Es un poco agresivo, sería ideal como perro cuidador',
            'created_at' => $agoDate,
            'updated_at' => $agoDate,
        ]);

        DB::table('adopters')->insert([
            'forename' => 'Juan',
            'surname' => 'MX',
            'address' => 'Calle falsa 1234',
            'phone' => '0123456789',
            'email' => 'juan@mail.com',
            'age' => 30,
            'status' => 0,
            'type' => 1,
            'created_at' => Carbon::yesterday(),
            'updated_at' => Carbon::yesterday(),
        ]);

        DB::table('adoptions')->insert([
            'adopter_id' => 1,
            'pet_id' => 12,
            'status' => 0,
            'note' => 'Quiero a una mascota para regalar',
            'created_at' => Carbon::yesterday(),
            'updated_at' => Carbon::yesterday(),
        ]);

        DB::table('adoptions')->insert([
            'adopter_id' => 1,
            'pet_id' => 12,
            'status' => 1,
            'note' => 'Aceptada la adopcion de Chester',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        

        $pet_type = [
            1 => 'DOG',
            2 => 'BIRD',
            3 => 'CAT',
            4 => 'FISH',
            5 => 'PLANT',
        ];

        $adopter_type = [
            1 => 'PERSON',
            2 => 'STORE',
            3 => 'BUSINESS',
            4 => 'FIREFIGHTER',
            5 => 'ARMY',
            6 => 'FARM',
            7 => 'HATCHERY',
        ];

        foreach($pet_type as $type){
            DB::table('pet_types')->insert([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach($adopter_type as $type){
            DB::table('adopter_types')->insert([
                'name' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
