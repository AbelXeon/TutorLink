<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GradeLevels;

class GradeLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $levels = [
            'Primary / Elementary (Grades 1-6)',
            'Middle School (Grades 7-8)',
            'High School (Grades 9-12)',
            'Preparatory / University Level'
        ];

        foreach ($levels as $level) {
            GradeLevels::firstOrCreate(['name' => $level]);
        }
    }
}
