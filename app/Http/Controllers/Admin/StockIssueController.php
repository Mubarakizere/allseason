<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockItem;
use App\Models\StockHistory;
use App\Models\StockIssue;
use App\Models\StockIssueItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class StockIssueController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $issues = StockIssue::with('createdBy')->orderBy('id', 'desc')->get();
        $items = StockItem::all();
        return view('admin.stock.issues', compact('issues', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'department' => 'required|string',
            'note' => 'nullable|string',
            'items' => 'required|array',
            'items.*.stock_item_id' => 'required|exists:stock_items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $issue = StockIssue::create([
            'date' => $request->date,
            'department' => $request->department,
            'note' => $request->note,
            'created_by' => Auth::id()
        ]);

        foreach ($request->items as $item) {
            StockIssueItem::create([
                'stock_issue_id' => $issue->id,
                'stock_item_id' => $item['stock_item_id'],
                'quantity' => $item['quantity']
            ]);

            $stockItem = StockItem::findOrFail($item['stock_item_id']);
            $stockItem->quantity -= $item['quantity'];
            $stockItem->save();

            StockHistory::create([
                'stock_item_id' => $stockItem->id,
                'type' => 'issue',
                'quantity' => $item['quantity'],
                'balance' => $stockItem->quantity,
                'reference' => 'Issue-' . $issue->id . ' to ' . $request->department,
                'date' => $request->date,
                'note' => $request->note ?? 'Stock Issued to ' . $request->department,
                'user_id' => Auth::id()
            ]);
        }

        return redirect()->back()->with('success', 'Stock Issue recorded successfully.');
    }

    public function destroy($id)
    {
        $issue = StockIssue::with('items')->findOrFail($id);
        
        foreach ($issue->items as $item) {
            $stockItem = StockItem::find($item->stock_item_id);
            if ($stockItem) {
                $stockItem->quantity += $item->quantity;
                $stockItem->save();

                StockHistory::create([
                    'stock_item_id' => $stockItem->id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'balance' => $stockItem->quantity,
                    'reference' => 'Issue-' . $issue->id . ' DELETED',
                    'date' => now(),
                    'note' => 'Issue Deleted Reversal',
                    'user_id' => Auth::id()
                ]);
            }
        }
        $issue->delete();

        return redirect()->back()->with('success', 'Stock Issue deleted and stock reverted.');
    }
}
