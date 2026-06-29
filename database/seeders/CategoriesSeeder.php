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
       // Predefined categories and their matching subjects
        $data = [
            'Programming' => ['Java', 'Python', 'HTML', 'CSS', 'PHP', 'JavaScript', 'C++'],
            'Languages' => ['English', 'Amharic', 'French', 'Arabic', 'Spanish'],
            'School Subjects' => ['Mathematics', 'Physics', 'Chemistry', 'Biology', 'History'],
            'Skills' => ['Guitar', 'Piano', 'Photography', 'Public Speaking', 'Graphic Design']
        ];

        foreach ($data as $catName => $subNames) {
            $category = Categories::firstOrCreate(['name' => $catName]);

            foreach ($subNames as $subName) {
                
            Subjects::firstOrCreate([
                    'category_id' => $category->id,
                    'name' => $subName
                ]);
            }
        }
    }
}