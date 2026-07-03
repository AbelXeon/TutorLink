<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        // 1. Addis Ababa
        Location::firstOrCreate([
            'id' => 1
        ], [
            'name' => 'Addis Ababa'
        ]);

        // 2. Hawassa
        Location::firstOrCreate([
            'id' => 2
        ], [
            'name' => 'Hawassa'
        ]);

        // 3. Gondar
        Location::firstOrCreate([
            'id' => 3
        ], [
            'name' => 'Gondar'
        ]);

        // 4. Bahir Dar
        Location::firstOrCreate([
            'id' => 4
        ], [
            'name' => 'Bahir Dar'
        ]);

        // 5. Wolayita
        Location::firstOrCreate([
            'id' => 5
        ], [
            'name' => 'Wolayita'
        ]);

        // 6. Dilla
        Location::firstOrCreate([
            'id' => 6
        ], [
            'name' => 'Dilla'
        ]);

    }
    
}
