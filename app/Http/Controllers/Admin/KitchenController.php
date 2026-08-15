<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuRecipe;
use App\Models\Order;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockHistory;
use App\Models\KitchenPreparation;
use App\Models\SiteSetting;
use App\Services\KitchenStockService;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Kitchen Order Tickets (KOT) Live Display Screen
     */
    public function kot(Request $request)
    {
        $orders = Order::with(['orderItems', 'restaurantTable', 'waiter'])
            ->whereIn('status', ['pending', 'in_kitchen'])
            ->where(function($query) {
                $query->where('order_type', 'instore')
                      ->orWhere('status_online_pay', 'paid')
                      ->orWhereNull('status_online_pay');
            })
            ->orderBy('id', 'asc')
            ->get();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'orders' => $orders]);
        }

        return view('admin.kitchen.kot', compact('orders'));
    }

    /**
     * Raw Material Ingredients Inventory Management
     */
    public function ingredients(Request $request)
    {
        // Get or create 'Kitchen Ingredients' Category
        $kitchenCat = StockCategory::firstOrCreate(
            ['name' => 'Kitchen Raw Materials'],
            ['description' => 'Food ingredients, raw materials, and kitchen consumables']
        );

        $ingredients = StockItem::where('stock_category_id', $kitchenCat->id)
            ->orderBy('name', 'asc')
            ->get();

        $units = [
            'Kg' => 'Kilograms (Kg)',
            'Grams' => 'Grams (g)',
            'Liters' => 'Liters (L)',
            'ml' => 'Milliliters (ml)',
            'Pieces' => 'Pieces (Pcs)',
            'Bags' => 'Bags / Packs',
            'Bottles' => 'Bottles',
            'Boxes' => 'Boxes',
            'Pinch' => 'Pinch / Spoon',
        ];

        return view('admin.kitchen.ingredients', compact('ingredients', 'kitchenCat', 'units'));
    }

    public function storeIngredient(Request $request)
    {
        $kitchenCat = StockCategory::firstOrCreate(
            ['name' => 'Kitchen Raw Materials'],
            ['description' => 'Food ingredients, raw materials, and kitchen consumables']
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'alert_quantity' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['stock_category_id'] = $kitchenCat->id;
        $validated['sku'] = 'KIT-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $validated['name']), 0, 4)) . '-' . rand(100, 999);

        StockItem::create($validated);

        return back()->with('success', 'Raw material ingredient added successfully!');
    }

    public function updateIngredient(Request $request, $id)
    {
        $ingredient = StockItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'alert_quantity' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $ingredient->update($validated);

        return back()->with('success', 'Raw material ingredient updated successfully!');
    }

    public function destroyIngredient($id)
    {
        $ingredient = StockItem::findOrFail($id);
        $ingredient->delete();

        return back()->with('success', 'Ingredient deleted successfully!');
    }

    /**
     * Recipe Management linking Menus to Ingredients (Food Items Only)
     */
    public function recipes(Request $request)
    {
        $menus = Menu::with(['recipes.stockItem', 'category'])
            ->whereHas('category', function($q) {
                $q->where('name', 'NOT LIKE', '%drink%')
                  ->where('name', 'NOT LIKE', '%beverage%')
                  ->where('name', 'NOT LIKE', '%cocktail%')
                  ->where('name', 'NOT LIKE', '%bar%')
                  ->where('name', 'NOT LIKE', '%wine%')
                  ->where('name', 'NOT LIKE', '%beer%')
                  ->where('name', 'NOT LIKE', '%alcohol%');
            })
            ->orderBy('name', 'asc')
            ->get();

        $kitchenCat = StockCategory::where('name', 'Kitchen Raw Materials')->first();
        $stockItemQuery = StockItem::query();
        if ($kitchenCat) {
            $stockItemQuery->where('stock_category_id', $kitchenCat->id);
        }
        $ingredients = $stockItemQuery->orderBy('name', 'asc')->get();

        return view('admin.kitchen.recipes', compact('menus', 'ingredients'));
    }

    public function storeRecipe(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'stock_item_id' => 'required|exists:stock_items,id',
            'quantity' => 'required|numeric|gt:0',
        ]);

        MenuRecipe::updateOrCreate(
            [
                'menu_id' => $validated['menu_id'],
                'stock_item_id' => $validated['stock_item_id'],
            ],
            [
                'quantity' => $validated['quantity'],
            ]
        );

        return back()->with('success', 'Recipe ingredient updated successfully!');
    }

    public function destroyRecipe($id)
    {
        $recipe = MenuRecipe::findOrFail($id);
        $recipe->delete();

        return back()->with('success', 'Recipe ingredient removed successfully!');
    }

    /**
     * Food Preparation & Production Tracking Log (Food Items Only)
     */
    public function production(Request $request)
    {
        $preparations = KitchenPreparation::with('menu')->orderBy('id', 'desc')->paginate(20);
        $menus = Menu::whereHas('category', function($q) {
            $q->where('name', 'NOT LIKE', '%drink%')
              ->where('name', 'NOT LIKE', '%beverage%')
              ->where('name', 'NOT LIKE', '%cocktail%')
              ->where('name', 'NOT LIKE', '%bar%')
              ->where('name', 'NOT LIKE', '%wine%')
              ->where('name', 'NOT LIKE', '%beer%')
              ->where('name', 'NOT LIKE', '%alcohol%');
        })->orderBy('name', 'asc')->get();

        return view('admin.kitchen.production', compact('preparations', 'menus'));
    }

    public function storeProduction(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity_prepared' => 'required|numeric|gt:0',
            'prepared_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $preparedBy = $validated['prepared_by'] ?? 'Kitchen Chef';

        // Log preparation record
        $prep = KitchenPreparation::create([
            'menu_id' => $menu->id,
            'item_name' => $menu->name,
            'quantity_prepared' => $validated['quantity_prepared'],
            'prepared_by' => $preparedBy,
            'status' => 'completed',
            'notes' => $validated['notes'] ?? null,
        ]);

        // Auto deduct ingredients
        KitchenStockService::deductIngredientsForBatchPrep($menu->id, $validated['quantity_prepared'], $preparedBy);

        return back()->with('success', 'Batch preparation logged and raw material ingredients auto-deducted!');
    }

    /**
     * Production & Consumption Reports
     */
    public function reports(Request $request)
    {
        $site_settings = SiteSetting::first();
        
        $kitchenCat = StockCategory::where('name', 'Kitchen Raw Materials')->first();

        $rawMaterialsQuery = StockItem::query();
        if ($kitchenCat) {
            $rawMaterialsQuery->where('stock_category_id', $kitchenCat->id);
        }
        $rawMaterials = $rawMaterialsQuery->get();

        // Low stock alerts
        $lowStockItems = $rawMaterials->filter(function($item) {
            return $item->quantity <= $item->alert_quantity;
        });

        // Consumption history
        $consumptionLogs = StockHistory::with('stockItem')
            ->where('type', 'out')
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        $totalPreparations = KitchenPreparation::count();

        return view('admin.kitchen.reports', compact(
            'rawMaterials',
            'lowStockItems',
            'consumptionLogs',
            'totalPreparations',
            'site_settings'
        ));
    }
}
