<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'middle_name' => 'ceo',
            'last_name' => 'all the season',
            'email' => 'info@alltheseasongarden.rw',
            'password' => Hash::make('12345678'), // Hashed password
            'role' => 'global_admin',
            'status' => 1,
            'phone_number' => '+250788458102',
            'address' => 'Runda, Ruyenzi',
            'profile_picture' => null, // Default null if no picture
            'activation_token' => null, // Default null if no activation token
            'remember_token' => null,
            'two_factor_auth' => 0,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}


// php artisan db:seed --class=UserSeeder
