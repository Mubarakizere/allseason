<?php

namespace App\Http\Controllers\Traits;

use App\Models\Order;
use App\Models\Menu;
use App\Models\StockItem;
use App\Models\StockHistory;
use Illuminate\Support\Facades\Auth;

trait StockDeductionTrait
{
    public function deductStockForOrder(Order $order, $reference_prefix = 'Order')
    {
        // Prevent double deduction by checking a flag or just doing it once per order.
        // We assume this is only called once when the order is completed (instore) or paid (online).

        foreach ($order->orderItems as $orderItem) {
            if ($orderItem->menu_id) {
                $menu = Menu::with('recipes.stockItem')->find($orderItem->menu_id);
                if ($menu && $menu->recipes) {
                    foreach ($menu->recipes as $recipe) {
                        $stockItem = $recipe->stockItem;
                        if ($stockItem) {
                            $deductQty = $recipe->quantity * $orderItem->quantity;
                            
                            // Deduct from stock
                            $stockItem->quantity -= $deductQty;
                            $stockItem->save();

                            // Record in history
                            StockHistory::create([
                                'stock_item_id' => $stockItem->id,
                                'type' => 'sale',
                                'quantity' => $deductQty,
                                'balance' => $stockItem->quantity,
                                'reference' => $reference_prefix . ' #' . $order->order_no,
                                'date' => now(),
                                'note' => 'Sold via ' . $reference_prefix,
                                'user_id' => Auth::id() // Will be null for online webhook
                            ]);
                        }
                    }
                }
            }
        }
    }
}
