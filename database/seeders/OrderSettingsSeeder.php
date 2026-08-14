<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderSettings;

class OrderSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (OrderSettings::count() === 0) {
            $settings = new OrderSettings();
            $settings->price_per_mile = 1.50;
            $settings->distance_limit_in_miles = 10;
            $settings->save();
        }
    }
}
