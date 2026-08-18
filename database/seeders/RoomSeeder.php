<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomFeature;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Room 1',
                'description' => 'Large room with a very large bed, wardrobe, two-seat sofa/couch, table, private bathroom with toilet, mirror and shower, plus a relaxing/seating area.',
                'price' => 20000.00,
                'capacity' => 2,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Very Large Bed',
                    'Wardrobe',
                    'Two-Seat Sofa / Couch',
                    'Table',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                    'Relaxing / Seating Area',
                ],
            ],
            [
                'name' => 'Room 2',
                'description' => 'Spacious room with bed, wardrobe, two-seat sofa/couch, table, and private bathroom with toilet, mirror and shower.',
                'price' => 15000.00,
                'capacity' => 2,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'Wardrobe',
                    'Two-Seat Sofa / Couch',
                    'Table',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
            [
                'name' => 'Room 3',
                'description' => 'Spacious room with bed, wardrobe, two-seat sofa/couch, table, and private bathroom with toilet, mirror and shower.',
                'price' => 15000.00,
                'capacity' => 2,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'Wardrobe',
                    'Two-Seat Sofa / Couch',
                    'Table',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
            [
                'name' => 'Room 4',
                'description' => 'Spacious room with bed, wardrobe, two-seat sofa/couch, table, and private bathroom with toilet, mirror and shower.',
                'price' => 15000.00,
                'capacity' => 2,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'Wardrobe',
                    'Two-Seat Sofa / Couch',
                    'Table',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
            [
                'name' => 'Room 5',
                'description' => 'Spacious room with bed, wardrobe, two-seat sofa/couch, table, and private bathroom with toilet, mirror and shower.',
                'price' => 15000.00,
                'capacity' => 2,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'Wardrobe',
                    'Two-Seat Sofa / Couch',
                    'Table',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
            [
                'name' => 'Room 6',
                'description' => 'Smaller room with a bed, one-seat chair, and private bathroom with toilet, mirror and shower. No wardrobe or large sofa.',
                'price' => 10000.00,
                'capacity' => 1,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'One-Seat Chair',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
            [
                'name' => 'Room 7',
                'description' => 'Smaller room with a bed, one-seat chair, and private bathroom with toilet, mirror and shower. No wardrobe or large sofa.',
                'price' => 10000.00,
                'capacity' => 1,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'One-Seat Chair',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
            [
                'name' => 'Room 8',
                'description' => 'Smaller room with a bed, one-seat chair, and private bathroom with toilet, mirror and shower. No wardrobe or large sofa.',
                'price' => 10000.00,
                'capacity' => 1,
                'deposit_percentage' => 20.00,
                'features' => [
                    'Bed',
                    'One-Seat Chair',
                    'Private Bathroom (Toilet, Mirror, Shower)',
                ],
            ],
        ];

        foreach ($rooms as $roomData) {
            $features = $roomData['features'];
            unset($roomData['features']);

            $room = Room::updateOrCreate(
                ['name' => $roomData['name']],
                $roomData
            );

            // Sync features
            $room->features()->delete();
            foreach ($features as $featureName) {
                $room->features()->create([
                    'name' => $featureName,
                ]);
            }
        }
    }
}
