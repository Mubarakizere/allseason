<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyAddress;

class CompanyAddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            [
                'street'       => 'Runda',
                'city'         => 'Ruyenzi',
                'state'        => 'Kamonyi',
                'postal_code'  => '0000',
                'country'      => 'Rwanda',
                'latitude'     => 53.448990,   // You can update these later
                'longitude'    => -2.229520,
            ]
        ];

        foreach ($addresses as $address) {
            CompanyAddress::updateOrCreate(
                [
                    'street'      => $address['street'],
                    'postal_code' => $address['postal_code'],
                ],
                $address
            );
        }
    }
}


// php artisan db:seed --class=CompanyAddressSeeder
