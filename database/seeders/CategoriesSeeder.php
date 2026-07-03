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
        // Local Ethiopian Languages
        'Amharic', 
        'Oromiffa', 
        'Tigrinya', 
        'Somali', 
        'Sidama', 
        'Wolaytta', 
        'Afar',
        
        // Global Languages
        'English', 
        'French', 
        'Arabic',
        'Spanish', 
        'German', 
        'Italian',
        'Mandarin Chinese', 
        'Turkish',
        'Japanese'
    ],
    'Programming' => [
        'Python', 
        'JavaScript', 
        'PHP', 
        'Java', 
        'C++', 
        'C#', 
        'TypeScript',
        'Ruby', 
        'Swift', 
        'Kotlin', 
        'Go', 
        'Rust',
        'HTML & CSS',
        
        
        'SQL & Databases',
        'Laravel (PHP Framework)',
        'React / Vue (JS Libraries)',
        'Node.js (Backend)',
        'Flutter / React Native (Mobile)',
        'Git & GitHub'
    ],
    'Academic Subjects' => [
        // Mathematics
        'General Mathematics', 
        'Calculus & Advanced Math',
        'Algebra',
        'Geometry',
        
        // Natural Sciences
        'Physics', 
        'Chemistry', 
        'Biology', 
        'Environmental Science',
        
        // Social Sciences & Humanities
        'History', 
        'Geography',
        'Civics & Ethical Education',
        'Economics',
        'Basic Accounting',
        'Business Studies',
        'English Literature'
    ],
    'Skills' => [
        // Creative & Media
        'Graphic Design', 
        'Photography', 
        'Video Editing', 
        'Web Design & UI/UX',
        '3D Modeling & Animation',
        
        // Professional Skills
        'Public Speaking', 
        'Content Writing & Copywriting',
        'Academic & Research Writing',
        'Project Management',
        'Data Analysis',
        'Digital Marketing & SEO',
        'Microsoft Office (Word, Excel, PPT)'
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
