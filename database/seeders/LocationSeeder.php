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
         Location::firstOrCreate([
            'id' => 1
        ], [
            'name' => 'Addis Ababa'
        ]);

        Location::firstOrCreate([
            'id' => 2
        ], [
            'name' => 'Hawassa'
        ]);
    }
}
