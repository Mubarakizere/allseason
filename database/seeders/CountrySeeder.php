<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\SiteSetting;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        Country::truncate();

        Country::create([
            'name'            => 'Rwanda',
            'iso_code'        => 'RW',
            'currency_code'   => 'RWF',
            'currency_symbol' => 'RWF',
        ]);

        $setting = SiteSetting::firstOrNew();
        $setting->country         = 'Rwanda';
        $setting->currency_code   = 'RWF';
        $setting->currency_symbol = 'RWF';
        $setting->save();
    }
}
