<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['role_type' => 'Teacher']);
        Role::firstOrCreate(['role_type' => 'Student']);
        Role::firstOrCreate(['role_type' => 'Admin']);
        Role::firstOrCreate(['role_type' => 'Super_Admin']);
    }
}
