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
        
        Pet::factory()
            ->count(10)
            ->create();

        foreach($pet_type as $type){
            DB::table('pet_types')->insert([
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
