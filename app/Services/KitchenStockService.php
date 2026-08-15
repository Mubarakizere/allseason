<?php

namespace App\Services;

use App\Models\Order;
use App\Models\MenuRecipe;
use App\Models\StockItem;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Log;

class KitchenStockService
{
    /**
     * Automatically deduct raw material ingredients from inventory based on order items and recipes.
     */
    public static function deductRecipeIngredientsForOrder(Order $order): void
    {
        try {
            foreach ($order->items as $item) {
                $menuId = $item->menu_id;
                if (!$menuId) continue;

                $recipes = MenuRecipe::where('menu_id', $menuId)->get();
                if ($recipes->isEmpty()) continue;

                foreach ($recipes as $recipe) {
                    $stockItem = StockItem::find($recipe->stock_item_id);
                    if (!$stockItem) continue;

                    $requiredQty = $recipe->quantity * $item->quantity;
                    $previousQty = $stockItem->quantity;
                    
                    // Deduct stock
                    $stockItem->quantity = max(0, $stockItem->quantity - $requiredQty);
                    $stockItem->save();

                    // Log history
                    StockHistory::create([
                        'stock_item_id' => $stockItem->id,
                        'user_id' => auth()->id() ?? 1,
                        'type' => 'out',
                        'quantity' => $requiredQty,
                        'previous_quantity' => $previousQty,
                        'new_quantity' => $stockItem->quantity,
                        'notes' => 'Auto-Deducted for Food Order #' . ($order->order_no ?? $order->id) . ' (' . $item->name . ' x' . $item->quantity . ')',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Kitchen Stock Auto Deduction Error: ' . $e->getMessage());
        }
    }

    /**
     * Deduct raw material ingredients manually for batch preparation/cooking in kitchen.
     */
    public static function deductIngredientsForBatchPrep(int $menuId, float $prepQuantity, string $preparedBy = 'Kitchen Staff'): bool
    {
        $recipes = MenuRecipe::where('menu_id', $menuId)->get();
        if ($recipes->isEmpty()) {
            return false;
        }

        foreach ($recipes as $recipe) {
            $stockItem = StockItem::find($recipe->stock_item_id);
            if (!$stockItem) continue;

            $requiredQty = $recipe->quantity * $prepQuantity;
            $previousQty = $stockItem->quantity;

            $stockItem->quantity = max(0, $stockItem->quantity - $requiredQty);
            $stockItem->save();

            StockHistory::create([
                'stock_item_id' => $stockItem->id,
                'user_id' => auth()->id() ?? 1,
                'type' => 'out',
                'quantity' => $requiredQty,
                'previous_quantity' => $previousQty,
                'new_quantity' => $stockItem->quantity,
                'notes' => 'Kitchen Batch Prep Deduction (' . $preparedBy . ')',
            ]);
        }

        return true;
    }
}
