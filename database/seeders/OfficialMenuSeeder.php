<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\MenuRecipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OfficialMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Stock Categories exist
        $barStockCat = StockCategory::firstOrCreate(
            ['name' => 'Bar & Beverages'],
            ['description' => 'Wines, Beers, Spirits, Soft Drinks, Cocktails, Juices, and Bar Supplies']
        );

        $meatStockCat = StockCategory::firstOrCreate(
            ['name' => 'Meat & Poultry'],
            ['description' => 'Goat, Beef, Pork, Chicken, Rabbit, and Fish Stock']
        );

        $kitchenStockCat = StockCategory::firstOrCreate(
            ['name' => 'Kitchen Stock'],
            ['description' => 'Rice, Flour, Oils, Spices, and General Cooking Ingredients']
        );

        $vegStockCat = StockCategory::firstOrCreate(
            ['name' => 'Vegetables & Produce'],
            ['description' => 'Potatoes, Bananas, Salads, and Fresh Produce']
        );

        // 2. Clear old MenuRecipes, Menus, and Categories
        Schema::disableForeignKeyConstraints();
        MenuRecipe::truncate();
        Menu::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        // 3. Define Official Categories & Items Dataset
        $dataset = [
            [
                'category_name' => 'Brochettes & Grilled Meats',
                'type' => 'kitchen',
                'stock_category_id' => $meatStockCat->id,
                'items' => [
                    ['name' => 'Brochette (Goat)', 'price' => 1500],
                    ['name' => 'Brochette Zingaro', 'price' => 2000],
                    ['name' => 'Pork (Benzi)', 'price' => 9000],
                    ['name' => 'Full Rabbit', 'price' => 12000],
                    ['name' => 'Full Chicken', 'price' => 17000],
                    ['name' => 'Full Chicken (Large)', 'price' => 20000],
                    ['name' => 'Big Fish', 'price' => 18000],
                    ['name' => 'Medium Fish', 'price' => 15000],
                ]
            ],
            [
                'category_name' => 'Kitchen Meals & Boilo',
                'type' => 'kitchen',
                'stock_category_id' => $kitchenStockCat->id,
                'items' => [
                    ['name' => 'Chicken Rice', 'price' => 25000],
                    ['name' => 'Half Chicken with Rice', 'price' => 12500],
                    ['name' => 'Beef Boilo', 'price' => 4000],
                    ['name' => 'Full Chicken Boilo', 'price' => 25000],
                    ['name' => 'Igisafuriya Cy\'inkoko', 'price' => 25000],
                    ['name' => 'Omelette Special with Beef', 'price' => 5000],
                    ['name' => 'Omelette Special', 'price' => 4000],
                    ['name' => 'Agatogo', 'price' => 4000],
                ]
            ],
            [
                'category_name' => 'Sides & Salads',
                'type' => 'kitchen',
                'stock_category_id' => $vegStockCat->id,
                'items' => [
                    ['name' => 'Chips', 'price' => 2000],
                    ['name' => 'Banana', 'price' => 500],
                    ['name' => 'Potato', 'price' => 1000],
                    ['name' => 'Salad', 'price' => 2000],
                ]
            ],
            [
                'category_name' => 'Beers & Ciders',
                'type' => 'bar',
                'stock_category_id' => $barStockCat->id,
                'items' => [
                    ['name' => 'Big Mitzig', 'price' => 2500],
                    ['name' => 'Small Mitzig', 'price' => 1300],
                    ['name' => 'Big Skol', 'price' => 2000],
                    ['name' => 'Small Skol', 'price' => 1200],
                    ['name' => 'Skol 5', 'price' => 1700],
                    ['name' => 'Skol Lager', 'price' => 1200],
                    ['name' => 'Skol Canette', 'price' => 2500],
                    ['name' => 'Virunga Silver', 'price' => 1000],
                    ['name' => 'Amstel', 'price' => 1500],
                    ['name' => 'Amstel Bock', 'price' => 3500],
                    ['name' => 'Heineken', 'price' => 2000],
                    ['name' => 'Heineken 0% Canette', 'price' => 2000],
                    ['name' => 'Knowles', 'price' => 1200],
                    ['name' => 'Primus', 'price' => 1800],
                    ['name' => 'Smirnoff Bottle', 'price' => 2000],
                    ['name' => 'Smirnoff Canette', 'price' => 3000],
                    ['name' => 'Tusker Malt', 'price' => 2000],
                    ['name' => 'Tusker Lager', 'price' => 2000],
                    ['name' => 'Guinness', 'price' => 2000],
                    ['name' => 'Small Virunga Mist', 'price' => 1200],
                    ['name' => 'Big Panache', 'price' => 1500],
                    ['name' => 'Small Panache', 'price' => 1200],
                    ['name' => 'Turbo', 'price' => 1500],
                    ['name' => 'Savanna', 'price' => 4000],
                    ['name' => 'Goldberg', 'price' => 2000],
                    ['name' => 'Bavaria 0% Alcohol', 'price' => 3000],
                    ['name' => 'Leffe', 'price' => 4000],
                ]
            ],
            [
                'category_name' => 'Soft Drinks & Juices',
                'type' => 'bar',
                'stock_category_id' => $barStockCat->id,
                'items' => [
                    ['name' => 'Big Fanta', 'price' => 1500],
                    ['name' => 'Small Fanta', 'price' => 1000],
                    ['name' => 'Small Fanta Plastic', 'price' => 1300],
                    ['name' => 'Big Fanta Plastic', 'price' => 3000],
                    ['name' => 'Mirinda', 'price' => 1200],
                    ['name' => 'Novida', 'price' => 1200],
                    ['name' => 'Juice Inyange', 'price' => 1200],
                    ['name' => 'Juice Malt', 'price' => 1200],
                    ['name' => 'Big Water', 'price' => 1500],
                    ['name' => 'Small Water', 'price' => 800],
                    ['name' => 'Energy Drink', 'price' => 800],
                    ['name' => 'Cheetah', 'price' => 800],
                    ['name' => 'Tonic Plastic', 'price' => 1300],
                    ['name' => 'Red Bull', 'price' => 3500],
                ]
            ],
            [
                'category_name' => 'Liquors & Spirits',
                'type' => 'bar',
                'stock_category_id' => $barStockCat->id,
                'items' => [
                    ['name' => 'Jameson', 'price' => 65000],
                    ['name' => 'J&B', 'price' => 55000],
                    ['name' => 'Martini', 'price' => 25000],
                    ['name' => 'Casino Bottle (Tequila)', 'price' => 45000],
                    ['name' => 'Vin de Messe', 'price' => 20000],
                ]
            ],
            [
                'category_name' => 'Wines',
                'type' => 'bar',
                'stock_category_id' => $barStockCat->id,
                'items' => [
                    ['name' => 'Drostdy Bottle', 'price' => 20000],
                    ['name' => 'Drostdy (Glass - 5k)', 'price' => 5000],
                    ['name' => 'Drostdy (Large Glass - 8k)', 'price' => 8000],
                    ['name' => 'Pinta Negra', 'price' => 20000],
                    ['name' => 'Isabelle de France', 'price' => 20000],
                    ['name' => 'Provetto', 'price' => 25000],
                    ['name' => 'Moscato', 'price' => 25000],
                    ['name' => 'Muscodor', 'price' => 25000],
                    ['name' => 'Ch. Valac', 'price' => 20000],
                ]
            ],
        ];

        // 4. Insert Categories & Menu Items
        foreach ($dataset as $catData) {
            $category = Category::create([
                'name' => $catData['category_name'],
                'type' => $catData['type'],
                'stock_category_id' => $catData['stock_category_id'],
            ]);

            foreach ($catData['items'] as $item) {
                Menu::create([
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'description' => '',
                    'image' => '',
                    'type' => $catData['type'],
                ]);
            }
        }
    }
}
