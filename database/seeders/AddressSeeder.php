<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use Carbon\Carbon;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'user_id'     => 3,
            'label'       => 'delivery',
            'street'      => 'kn 187 st',
            'city'        => 'kigali',
            'state'       => 'rwanda',
            'postal_code' => '100001',
            'country'     => 'rwanda',
            'is_default'  => true,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);

        Address::create([
            'user_id'     => 3,
            'label'       => 'delivery',
            'street'      => 'kn 188 st',
            'city'        => 'nyarugenge',
            'state'       => 'kigali',
            'postal_code' => '100242',
            'country'     => 'rwanda',
            'is_default'  => false,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ]);
    }
}


//php artisan db:seed --class=AddressSeeder
