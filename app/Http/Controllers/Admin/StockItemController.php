<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockItem;
use App\Models\StockCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class StockItemController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $items = StockItem::with('category')->get();
        $categories = StockCategory::all();
        return view('admin.stock.items', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stock_category_id' => 'nullable|exists:stock_categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'quantity' => 'numeric|min:0',
            'alert_quantity' => 'numeric|min:0',
            'cost_price' => 'numeric|min:0',
            'description' => 'nullable|string'
        ]);

        StockItem::create($request->all());
        return redirect()->back()->with('success', 'Stock Item created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stock_category_id' => 'nullable|exists:stock_categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'alert_quantity' => 'numeric|min:0',
            'cost_price' => 'numeric|min:0',
            'description' => 'nullable|string'
        ]);

        $item = StockItem::findOrFail($id);
        $item->update($request->except('quantity')); // quantity is updated via purchases/issues
        return redirect()->back()->with('success', 'Stock Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = StockItem::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Stock Item deleted successfully.');
    }
}
