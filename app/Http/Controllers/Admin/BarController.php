<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuRecipe;
use App\Models\Order;
use App\Models\StockCategory;
use App\Models\StockItem;
use App\Models\StockHistory;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class BarController extends Controller
{
    /**
     * Bar Drink Stock & Inventory Management
     */
    public function inventory(Request $request)
    {
        $barCat = StockCategory::firstOrCreate(
            ['name' => 'Bar & Beverages'],
            ['description' => 'Wines, Beers, Spirits, Soft Drinks, Cocktails, Juices, and Bar Supplies']
        );

        $drinks = StockItem::where('stock_category_id', $barCat->id)
            ->orderBy('name', 'asc')
            ->get();

        $barMenuItems = Menu::with('category')
            ->where('type', 'bar')
            ->orWhereHas('category', function($q) {
                $q->where('name', 'LIKE', '%drink%')
                  ->orWhere('name', 'LIKE', '%beverage%')
                  ->orWhere('name', 'LIKE', '%bar%')
                  ->orWhere('name', 'LIKE', '%wine%')
                  ->orWhere('name', 'LIKE', '%beer%')
                  ->orWhere('name', 'LIKE', '%cocktail%')
                  ->orWhere('name', 'LIKE', '%alcohol%')
                  ->orWhere('name', 'LIKE', '%soda%')
                  ->orWhere('name', 'LIKE', '%juice%');
            })
            ->orderBy('name', 'asc')
            ->get();

        $units = [
            'Bottles' => 'Bottles (Btl)',
            'Cans' => 'Cans',
            'Crates' => 'Crates',
            'Cl' => 'Centiliters (cl) - Shot Pours',
            'Liters' => 'Liters (L)',
            'Glasses' => 'Glasses',
            'Packs' => 'Packs / Cartons',
            'Boxes' => 'Boxes',
        ];

        return view('admin.bar.inventory', compact('drinks', 'barCat', 'barMenuItems', 'units'));
    }

    public function storeDrink(Request $request)
    {
        $barCat = StockCategory::firstOrCreate(
            ['name' => 'Bar & Beverages'],
            ['description' => 'Wines, Beers, Spirits, Soft Drinks, Cocktails, Juices, and Bar Supplies']
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'menu_id' => 'nullable|exists:menus,id',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'alert_quantity' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $menuId = $request->input('menu_id');
        unset($validated['menu_id']);

        $validated['stock_category_id'] = $barCat->id;
        $validated['sku'] = 'BAR-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $validated['name']), 0, 4)) . '-' . rand(100, 999);

        $stockItem = StockItem::create($validated);

        if ($menuId) {
            MenuRecipe::updateOrCreate(
                ['menu_id' => $menuId, 'stock_item_id' => $stockItem->id],
                ['quantity' => 1]
            );
        }

        return back()->with('success', 'Bar drink stock added successfully!');
    }

    public function updateDrink(Request $request, $id)
    {
        $drink = StockItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'required|numeric|min:0',
            'alert_quantity' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $drink->update($validated);

        return back()->with('success', 'Bar drink stock updated successfully!');
    }

    public function destroyDrink($id)
    {
        $drink = StockItem::findOrFail($id);
        $drink->delete();

        return back()->with('success', 'Bar drink item deleted successfully!');
    }

    /**
     * Cocktail & Beverage Recipe Builder (Drink Items Only)
     */
    public function recipes(Request $request)
    {
        $menus = Menu::with(['recipes.stockItem', 'category'])
            ->whereHas('category', function($q) {
                $q->where('name', 'LIKE', '%drink%')
                  ->orWhere('name', 'LIKE', '%beverage%')
                  ->orWhere('name', 'LIKE', '%cocktail%')
                  ->orWhere('name', 'LIKE', '%bar%')
                  ->orWhere('name', 'LIKE', '%wine%')
                  ->orWhere('name', 'LIKE', '%beer%')
                  ->orWhere('name', 'LIKE', '%alcohol%')
                  ->orWhere('name', 'LIKE', '%juice%');
            })
            ->orderBy('name', 'asc')
            ->get();

        // If no categories match, fallback to all menus
        if ($menus->isEmpty()) {
            $menus = Menu::with(['recipes.stockItem', 'category'])->orderBy('name', 'asc')->get();
        }

        $barCat = StockCategory::where('name', 'Bar & Beverages')->first();
        $stockItemQuery = StockItem::query();
        if ($barCat) {
            $stockItemQuery->where('stock_category_id', $barCat->id);
        }
        $ingredients = $stockItemQuery->orderBy('name', 'asc')->get();

        return view('admin.bar.recipes', compact('menus', 'ingredients'));
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

        return back()->with('success', 'Cocktail recipe ingredient saved successfully!');
    }

    public function destroyRecipe($id)
    {
        $recipe = MenuRecipe::findOrFail($id);
        $recipe->delete();

        return back()->with('success', 'Cocktail recipe ingredient removed!');
    }

    /**
     * Live Bar Dispense Tickets Screen for Bartenders
     */
    public function tickets(Request $request)
    {
        $orders = Order::with(['orderItems', 'restaurantTable', 'waiter'])
            ->whereIn('status', ['pending', 'in_kitchen', 'in_bar'])
            ->orderBy('id', 'asc')
            ->get();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'orders' => $orders]);
        }

        return view('admin.bar.tickets', compact('orders'));
    }

    /**
     * Bar Consumption Reports & Low Stock Warning Alerts
     */
    public function reports(Request $request)
    {
        $site_settings = SiteSetting::first();

        $barCat = StockCategory::where('name', 'Bar & Beverages')->first();
        $barItemsQuery = StockItem::query();
        if ($barCat) {
            $barItemsQuery->where('stock_category_id', $barCat->id);
        }
        $barItems = $barItemsQuery->get();

        $lowStockItems = $barItems->filter(function($item) {
            return $item->quantity <= $item->alert_quantity;
        });

        $consumptionLogs = StockHistory::with('stockItem')
            ->where('type', 'out')
            ->orderBy('id', 'desc')
            ->take(30)
            ->get();

        return view('admin.bar.reports', compact('barItems', 'lowStockItems', 'consumptionLogs', 'site_settings'));
    }
}
