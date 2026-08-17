<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Customer;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Traits\OrderStatisticsTrait;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;
use App\Http\Controllers\Traits\OrderNumberGeneratorTrait;
use App\Http\Controllers\Traits\StockDeductionTrait;

class OrderController extends Controller
{
    use AdminViewSharedDataTrait;
    use OrderStatisticsTrait;
    use OrderNumberGeneratorTrait;
    use StockDeductionTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
        $this->shareOrderStatistics();
        
    }


    public function index(Request $request, $filter = null)
    {
        // Define allowed filters
        $allowedFilters = ['pending', 'delivery', 'instore', 'completed'];

        if ($filter && !in_array($filter, $allowedFilters)) {
            return redirect()->route('admin.orders.index')->with('error', 'Invalid filter value.');
        }

        if ($request->ajax()) {
            $orders = Order::with(['restaurantTable', 'waiter', 'customer', 'orderItems'])
                ->select(['id', 'order_no', 'created_at', 'total_price', 'status', 'status_online_pay', 'order_type', 'payment_method', 'restaurant_table_id', 'waiter_id', 'user_id'])
                ->orderBy('id', 'desc');

            // Apply filters based on the user's selection
            if ($filter) {
                if ($filter == 'pending') {
                    $orders = $orders->where('status', 'pending');
                } elseif ($filter == 'delivery') {
                    $orders = $orders->where('order_type', 'delivery');
                } elseif ($filter == 'instore') {
                    $orders = $orders->where('order_type', 'instore');
                } elseif ($filter == 'completed') {
                    $orders = $orders->where('status', 'completed');
                }
            }

            return Datatables::of($orders)
                    ->addIndexColumn()
                    ->addColumn('action', function ($order) {
                        $viewButton = '<a href="'.route('admin.order.show', $order->id).'" class="btn btn-sm btn-dark font-weight-bold" title="View Details"><i class="fas fa-eye me-1"></i> View</a>';
                        
                        $printButton = '<button type="button" onclick="window.open(\''.route('admin.orders.receipt', $order->id).'\', \'_blank\', \'width=400,height=600\')" class="btn btn-sm btn-outline-secondary" title="Print Receipt"><i class="fas fa-print"></i></button>';

                        $completeButton = '';
                        if ($order->status === 'pending') {
                            $completeButton = '<button type="button" class="btn btn-sm btn-success complete-order-btn" data-id="'.$order->id.'" data-order-no="'.$order->order_no.'" data-total="'.$order->total_price.'" data-payment="'.e($order->payment_method ?? 'Cash').'" data-bs-toggle="modal" data-bs-target="#completeOrderModal" title="Complete Order & Payment"><i class="fas fa-check me-1"></i> Complete</button>';
                        }

                        $deleteButton = Auth::user()->role == "global_admin" ? '<button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="'.$order->id.'" title="Delete Order"><i class="fas fa-trash"></i></button>' : '';
                                            
                        return '<div class="d-flex align-items-center gap-1">' . $viewButton . $printButton . $completeButton . $deleteButton . '</div>';
                    })
                    ->editColumn('order_no', function ($order) {
                        $diff = $order->created_at->diffForHumans();
                        return '<div class="fw-bold text-dark">#' . $order->order_no . '</div><small class="text-muted" style="font-size:11px;">' . $diff . '</small>';
                    })
                    ->addColumn('details', function ($order) {
                        if ($order->order_type === 'instore') {
                            $tableName = $order->restaurantTable ? $order->restaurantTable->name : 'Walk-in';
                            $waiterName = $order->waiter ? $order->waiter->name : '';
                            return '<div class="fw-semibold text-dark"><i class="fas fa-utensils text-muted me-1" style="font-size:11px;"></i> ' . e($tableName) . '</div>' . ($waiterName ? '<small class="text-muted" style="font-size:11px;">Waiter: ' . e($waiterName) . '</small>' : '');
                        } else {
                            $custName = $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Customer';
                            return '<div class="fw-semibold text-dark"><i class="fas fa-user text-muted me-1" style="font-size:11px;"></i> ' . e($custName) . '</div><small class="text-muted" style="font-size:11px;">' . ucfirst($order->order_type) . '</small>';
                        }
                    })
                    ->addColumn('items_preview', function ($order) {
                        $count = $order->orderItems->sum('quantity');
                        return '<span class="badge bg-light text-dark border fw-normal" style="font-size:11.5px; padding: 4px 8px;">' . $count . ' ' . ($count === 1 ? 'item' : 'items') . '</span>';
                    })
                    ->editColumn('created_at', function ($order) {
                        return $order->created_at->format('g:i A - j M, Y');
                    })          
                    ->editColumn('total_price', function ($order) {
                        $site_settings = SiteSetting::latest()->first();
                        $currency_symbol = $site_settings->currency_symbol ?? config('site.currency_symbol');
                        return '<span class="fw-bold text-dark">' . html_entity_decode($currency_symbol) . number_format($order->total_price, 2) . '</span>';
                    })
                    ->addColumn('payment', function ($order) {
                        $method = $order->payment_method ? e($order->payment_method) : 'Pending';
                        return '<span class="text-secondary" style="font-size: 12.5px;">' . $method . '</span>';
                    })
                    ->editColumn('status', function ($order) {
                        switch ($order->status) {
                            case 'pending':
                                return '<span class="badge bg-warning text-dark fw-semibold px-2 py-1" style="font-size: 11px;">Pending</span>';
                            case 'completed':
                                return '<span class="badge bg-success text-white fw-semibold px-2 py-1" style="font-size: 11px;">Completed</span>';
                            case 'cancelled':
                                return '<span class="badge bg-danger text-white fw-semibold px-2 py-1" style="font-size: 11px;">Cancelled</span>';
                            default:
                                return '<span class="badge bg-secondary text-white fw-semibold px-2 py-1" style="font-size: 11px;">' . ucfirst($order->status) . '</span>';
                        }
                    })
                    ->editColumn('order_type', function ($order) {
                        return '<span class="badge bg-light text-dark border">' . ucfirst($order->order_type) . '</span>';
                    })                   
                    ->rawColumns(['order_no', 'details', 'items_preview', 'total_price', 'payment', 'status', 'order_type', 'action'])
                    ->make(true);
        }
          
        return view('admin.orders-index', compact('filter'));
    }
    
    public function show($id)
    {
        $order = Order::with(['orderItems', 'createdByUser', 'updatedByUser', 'customer', 'pickupAddress', 'deliveryAddressWithTrashed', 'restaurantTable', 'waiter'])->findOrFail($id);
        
        return view('admin.orders-show', compact('order'));
    }
    


    public function createOrder(Request $request)
    {
        $cart = session()->get($request->cartkey, []);
        if (empty($cart)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty!'], 400);
            }
            return back()->with('error', 'Cart is empty!');
        }

        $subtotalPrice = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Validate request data
        $validatedData = $request->validate([
            'payment_method' => 'nullable|string|max:255',  
            'additional_info' => 'nullable|string|max:255',           
            'waiter_id' => 'required|exists:waiters,id',
            'restaurant_table_id' => 'required|exists:restaurant_tables,id',
            'discount_amount' => 'nullable|numeric|min:0',
            'amount_tendered' => 'nullable|numeric|min:0',
            'change_due' => 'nullable|numeric',
        ]);

        $discountAmount = (float) ($validatedData['discount_amount'] ?? 0);
        $finalTotalPrice = max(0, $subtotalPrice - $discountAmount);

        $order = null;

        // Check if there is an existing pending order for this table
        if (!empty($validatedData['restaurant_table_id'])) {
            $order = Order::where('restaurant_table_id', $validatedData['restaurant_table_id'])
                ->where('status', 'pending')
                ->where('order_type', 'instore')
                ->first();
        }

        if ($order) {
            // Append to existing order
            $order->update([
                'total_price' => $order->total_price + $finalTotalPrice,
                'discount_amount' => ($order->discount_amount ?? 0) + $discountAmount,
                'payment_method' => $validatedData['payment_method'] ?? $order->payment_method,
                'amount_tendered' => $validatedData['amount_tendered'] ?? $order->amount_tendered,
                'change_due' => $validatedData['change_due'] ?? $order->change_due,
                'is_printed' => false, // Trigger reprint for kitchen
                'updated_by_user_id' => Auth::id(),
                'additional_info' => $validatedData['additional_info'] ? trim(($order->additional_info ?? '') . "\n" . $validatedData['additional_info']) : $order->additional_info,
            ]);
        } else {
            // Generate a unique 7-digit order number
            $order_no = $this->generateOrderNumber();

            // Create a new order (status pending to allow adding more)
            $order = Order::create([
                'customer_id' => null,
                'order_no' => $order_no,
                'order_type' => 'instore',
                'created_by_user_id' => Auth::id(),
                'updated_by_user_id' => Auth::id(),
                'total_price' => $finalTotalPrice,
                'discount_amount' => $discountAmount,
                'status' => 'pending', 
                'payment_method' => $validatedData['payment_method'] ?? 'Pending',
                'amount_tendered' => $validatedData['amount_tendered'] ?? null,
                'change_due' => $validatedData['change_due'] ?? null,
                'additional_info' => $validatedData['additional_info'],
                'delivery_fee' => null,
                'delivery_distance' => null,
                'price_per_mile' => null,
                'waiter_id' => $validatedData['waiter_id'],
                'restaurant_table_id' => $validatedData['restaurant_table_id'] ?? null,
            ]);
        }

        if ($order) {
            // Create order items using the relationship
            foreach ($cart as $item) {
                $order->orderItems()->create([
                    'menu_id' => $item['id'] ?? null,
                    'menu_name' => $item['name'],  
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'item_note' => $item['item_note'] ?? null,
                ]);
            }

            // Deduct stock for the items
            $this->deductStockForOrder($order, 'POS');

            // Deduct raw material recipe ingredients from kitchen stock
            \App\Services\KitchenStockService::deductRecipeIngredientsForOrder($order);
        }

        // Clear the cart
        session()->forget($request->cartkey);

        // Determine whether order has kitchen vs bar items for ticket generation
        $hasKitchenItems = false;
        $hasBarItems = false;
        foreach ($order->orderItems as $item) {
            if ($this->isBarItem($item)) {
                $hasBarItems = true;
            } else {
                $hasKitchenItems = true;
            }
        }

        $kitchenTicketUrl = $hasKitchenItems ? route('admin.orders.receipt', $order->id) . '?type=kitchen' : null;
        $barTicketUrl = $hasBarItems ? route('admin.orders.receipt', $order->id) . '?type=bar' : null;
        $customerReceiptUrl = route('admin.orders.receipt', $order->id) . '?type=receipt';
        $checkTicketUrl = route('admin.orders.receipt', $order->id) . '?type=check';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'has_kitchen_items' => $hasKitchenItems,
                'has_bar_items' => $hasBarItems,
                'kitchen_ticket_url' => $kitchenTicketUrl,
                'bar_ticket_url' => $barTicketUrl,
                'check_ticket_url' => $checkTicketUrl,
                'customer_receipt_url' => $customerReceiptUrl,
                'message' => 'Order #' . $order->order_no . ' sent to Kitchen & Bar successfully.'
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order created/updated successfully.');
    }

    
    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'status' => 'required|in:completed,cancelled',
            'payment_method' => 'nullable|string|max:255',
            'split_cash' => 'nullable|numeric|min:0',
            'split_momo' => 'nullable|numeric|min:0',
            'split_bank' => 'nullable|numeric|min:0',
        ]);
        $order = Order::findOrFail($id);

        $updateData = [
            'status' => $request->status,
            'updated_by_user_id' => Auth::id()
        ];

        $paymentMethod = $request->payment_method;
        if ($paymentMethod === 'Split' || $paymentMethod === 'Partial') {
            $cashAmt = (float) $request->input('split_cash', 0);
            $momoAmt = (float) $request->input('split_momo', 0);
            $bankAmt = (float) $request->input('split_bank', 0);
            $totalPaid = $cashAmt + $momoAmt + $bankAmt;

            $parts = [];
            if ($cashAmt > 0) $parts[] = 'Cash: ' . number_format($cashAmt, 2);
            if ($momoAmt > 0) $parts[] = 'MoMo: ' . number_format($momoAmt, 2);
            if ($bankAmt > 0) $parts[] = 'Bank/Card: ' . number_format($bankAmt, 2);

            $paymentMethod = !empty($parts) ? 'Split (' . implode(', ', $parts) . ')' : 'Split Payment';
            $updateData['amount_tendered'] = $totalPaid;
            $updateData['change_due'] = max(0, $totalPaid - $order->total_price);
        } elseif ($request->filled('payment_method')) {
            $paymentMethod = $request->payment_method;
        }

        if ($paymentMethod) {
            $updateData['payment_method'] = $paymentMethod;
        }

        $order->update($updateData);
        
        if ($request->status === 'completed') {
            session()->flash('auto_print_receipt_url', route('admin.orders.receipt', $order->id));
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully.',
                'auto_print_receipt_url' => route('admin.orders.receipt', $order->id)
            ]);
        }
    
        return back()->with('success', 'Order status updated successfully');
    }

 
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->deleteWithRelations();

        return redirect()->route('admin.orders.index')->with('success', 'Order have been deleted successfully.');
    }

    public function unprinted()
    {
        // Get the oldest unprinted order
        // For online orders, they should be paid. For instore orders, they can be printed immediately.
        $order = Order::where('is_printed', false)
            ->where(function($query) {
                $query->where('status_online_pay', 'paid')
                      ->orWhere('order_type', 'instore')
                      ->orWhereNull('status_online_pay'); // fallback for instore
            })
            ->orderBy('id', 'asc')
            ->first();

        if ($order) {
            return response()->json(['success' => true, 'order_id' => $order->id]);
        }

        return response()->json(['success' => false]);
    }

    public function receipt(Request $request, $id)
    {
        $order = Order::with(['orderItems.menu.category', 'orderItems.menu.recipes.stockItem.category', 'customer', 'restaurantTable', 'waiter'])->findOrFail($id);
        $site_settings = SiteSetting::first();
        
        $type = strtolower($request->get('type', ''));
        if (!$type) {
            if ($request->has('kitchen')) {
                $type = 'kitchen';
            } elseif ($request->has('bar')) {
                $type = 'bar';
            } elseif ($request->has('check') || $request->has('ticket')) {
                $type = 'check';
            }
        }

        if (in_array($type, ['kitchen', 'bar', 'kot', 'bot', 'check', 'ticket'])) {
            $unprintedOnly = $request->boolean('unprinted', false);
            
            $itemsQuery = $order->orderItems();
            if ($unprintedOnly) {
                $itemsQuery->whereNull('print_batch');
            }
            $items = $itemsQuery->get();

            // Categorize into Kitchen vs Bar items
            $kitchenItems = collect();
            $barItems = collect();

            foreach ($items as $item) {
                if ($this->isBarItem($item)) {
                    $barItems->push($item);
                } else {
                    $kitchenItems->push($item);
                }
            }

            if ($type === 'bar' || $type === 'bot') {
                $ticketTitle = 'BAR DISPENSE TICKET (BOT)';
                $displayItems = $barItems->isNotEmpty() ? $barItems : $items;
                return view('admin.orders-kitchen-ticket', compact('order', 'displayItems', 'ticketTitle', 'type'));
            } elseif ($type === 'kitchen' || $type === 'kot') {
                $ticketTitle = 'KITCHEN ORDER TICKET (KOT)';
                $displayItems = $kitchenItems->isNotEmpty() ? $kitchenItems : $items;
                return view('admin.orders-kitchen-ticket', compact('order', 'displayItems', 'ticketTitle', 'type'));
            } elseif ($type === 'check' || $type === 'ticket') {
                $ticketTitle = 'PRE-BILL ORDER TICKET';
                return view('admin.orders-check-ticket', compact('order', 'items', 'ticketTitle', 'site_settings'));
            }
        }
        
        return view('admin.orders-receipt', compact('order', 'site_settings'));
    }

    private function isBarItem($item) {
        if ($item->menu) {
            if ($item->menu->type === 'bar') {
                return true;
            } elseif ($item->menu->type === 'kitchen') {
                return false;
            }

            if ($item->menu->category) {
                $catName = strtolower($item->menu->category->name);
                $barKeywords = ['drink', 'beverage', 'bar', 'wine', 'beer', 'cocktail', 'juice', 'alcohol', 'soda', 'liquor', 'whiskey', 'rum', 'vodka', 'gin', 'champagne', 'cider', 'spirit', 'water'];
                foreach ($barKeywords as $kw) {
                    if (str_contains($catName, $kw)) return true;
                }
            }

            if ($item->menu->recipes) {
                foreach ($item->menu->recipes as $recipe) {
                    $stockCat = $recipe->stockItem ? ($recipe->stockItem->category ?? $recipe->stockItem->stockCategory) : null;
                    if ($stockCat && str_contains(strtolower($stockCat->name), 'bar')) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function markPrinted($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['is_printed' => true]);
        
        $maxBatch = $order->orderItems()->max('print_batch') ?? 0;
        $order->orderItems()->whereNull('print_batch')->update(['print_batch' => $maxBatch + 1]);
        
        return response()->json(['success' => true]);
    }

    public function getOpenOrder($tableId)
    {
        $order = Order::with('orderItems')
            ->where('restaurant_table_id', $tableId)
            ->where('status', 'pending')
            ->where('order_type', 'instore')
            ->first();

        if ($order) {
            return response()->json(['success' => true, 'order' => $order]);
        }
        
        return response()->json(['success' => false]);
    }
}
