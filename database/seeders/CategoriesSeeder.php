<?php

namespace Database\Seeders;

use App\Models\Categories;
use App\Models\Subjects;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 1. Define Categories and their corresponding Subjects
        $data = [
            'Language' => [
                'English', 
                'Amharic', 
                'French', 
                'Oromiffa'
            ],
            'Programming' => [
                'Python', 
                'JavaScript', 
                'PHP', 
                'Java', 
                'C++'
            ],
            'Academic Subjects' => [
                'Mathematics', 
                'Physics', 
                'Chemistry', 
                'Biology', 
                'History'
            ],
            'Skills' => [
                'Graphic Design', 
                'Photography', 
                'Video Editing', 
                'Public Speaking'
            ]
        ];

        foreach ($data as $categoryName => $subjectList) {
            // Create Category
            $category = Categories::firstOrCreate([
                'name' => $categoryName
            ]);

            // Create and link Subjects
            foreach ($subjectList as $subjectName) {
                Subjects::firstOrCreate([
                    'category_id' => $category->id,
                    'name'        => $subjectName
                ]);
            }
        }
    }
}
