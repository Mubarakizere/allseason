<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockItem;
use App\Models\Supplier;
use App\Models\StockHistory;
use App\Models\StockPurchase;
use App\Models\StockPurchaseItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class StockPurchaseController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $purchases = StockPurchase::with(['supplier', 'createdBy'])->orderBy('id', 'desc')->get();
        $suppliers = Supplier::all();
        $items = StockItem::all();
        return view('admin.stock.purchases', compact('purchases', 'suppliers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'reference_no' => 'nullable|string|max:255',
            'date' => 'required|date',
            'note' => 'nullable|string',
            'items' => 'required|array',
            'items.*.stock_item_id' => 'required|exists:stock_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += ($item['quantity'] * $item['unit_cost']);
        }

        $purchase = StockPurchase::create([
            'supplier_id' => $request->supplier_id,
            'reference_no' => $request->reference_no,
            'date' => $request->date,
            'status' => 'received',
            'total_amount' => $totalAmount,
            'note' => $request->note,
            'created_by' => Auth::id()
        ]);

        foreach ($request->items as $item) {
            StockPurchaseItem::create([
                'stock_purchase_id' => $purchase->id,
                'stock_item_id' => $item['stock_item_id'],
                'quantity' => $item['quantity'],
                'unit_cost' => $item['unit_cost'],
                'subtotal' => $item['quantity'] * $item['unit_cost']
            ]);

            $stockItem = StockItem::findOrFail($item['stock_item_id']);
            $stockItem->quantity += $item['quantity'];
            $stockItem->save();

            StockHistory::create([
                'stock_item_id' => $stockItem->id,
                'type' => 'in',
                'quantity' => $item['quantity'],
                'balance' => $stockItem->quantity,
                'reference' => 'PO-' . $purchase->id . ($request->reference_no ? ' / ' . $request->reference_no : ''),
                'date' => $request->date,
                'note' => 'Purchase Received',
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->back()->with('success', 'Stock Purchase recorded successfully.');
    }

    public function destroy($id)
    {
        // For simplicity, deleting a purchase will just delete the record but ideally we would revert stock.
        // We'll revert stock for safety.
        $purchase = StockPurchase::with('items')->findOrFail($id);
        
        foreach ($purchase->items as $item) {
            $stockItem = StockItem::find($item->stock_item_id);
            if ($stockItem) {
                $stockItem->quantity -= $item->quantity;
                $stockItem->save();

                StockHistory::create([
                    'stock_item_id' => $stockItem->id,
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'balance' => $stockItem->quantity,
                    'reference' => 'PO-' . $purchase->id . ' DELETED',
                    'date' => now(),
                    'note' => 'Purchase Deleted Reversal',
                    'user_id' => Auth::id()
                ]);
            }
        }
        $purchase->delete();

        return redirect()->back()->with('success', 'Stock Purchase deleted and stock reverted.');
    }
}
