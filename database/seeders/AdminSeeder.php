<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure both Admin roles exist in database
        $superRole = Role::firstOrCreate(['role_type' => 'Super_Admin']);
        Role::firstOrCreate(['role_type' => 'Admin']);

              User::firstOrCreate([
            'email' => env('SUPER_ADMIN_EMAIL')
        ], 
        [
             'role_id'        => $superRole->id,
            'first_name'     => env('SUPER_ADMIN_FIRST_NAME'),
            'middle_name'    => null,
            'last_name'      => env('SUPER_ADMIN_LAST_NAME'),
            'username'       => env('SUPER_ADMIN_USERNAME'),
            'password'       => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            'phone_number'   => env('SUPER_ADMIN_PHONE'),
            'address'        => env('SUPER_ADMIN_ADDRESS'),
            'location_id'    => 1,
            'account_status' => 'active',
        ]);
    }
}