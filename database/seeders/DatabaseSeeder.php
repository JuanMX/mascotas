<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $pet_type = Helper::getPetType();
        $adopter_type = Helper::getAdopterType();
        
        Pet::factory()->count(10)->create();
        DB::table('pets')->insert([
            'name' => 'Queso',
            'age' => rand(0,3),
            'type' => 'DOG',
            'status' => 0,
            'note' => 'Mascota muy juguetona',
            'created_at' => now(),
        ]);
        DB::table('pets')->insert([
            'name' => 'Chester',
            'age' => rand(0,3),
            'type' => 'DOG',
            'status' => 0,
            'note' => 'Es un poco agresivo, sería ideal como perro cuidador',
            'created_at' => now(),
        ]);
        

        foreach($pet_type as $type){
            DB::table('pet_types')->insert([
                'name' => $type,
                'created_at' => now(),
            ]);
        }

        foreach($adopter_type as $type){
            DB::table('adopter_types')->insert([
                'name' => $type,
                'created_at' => now(),
            ]);
        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
