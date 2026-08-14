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
                        $viewButton = '<a href="'.route('admin.order.show', $order->id).'" class="btn btn-outline-primary btn-sm font-weight-bold mr-1 mb-1" title="View Order"><i class="fa fa-eye mr-1"></i> View</a>';
                        
                        $printButton = '<button type="button" onclick="window.open(\''.route('admin.orders.receipt', $order->id).'\', \'_blank\', \'width=400,height=600\')" class="btn btn-outline-secondary btn-sm font-weight-bold mr-1 mb-1" title="Print Receipt"><i class="fa fa-print mr-1"></i> Print</button>';

                        $completeButton = '';
                        if ($order->status === 'pending') {
                            $completeButton = '<form action="'.route('admin.orders.update', $order->id).'" method="POST" style="display:inline;" class="mr-1 mb-1">'.csrf_field().'<input type="hidden" name="status" value="completed"><button type="submit" class="btn btn-success btn-sm font-weight-bold" title="Mark as Completed"><i class="fa fa-check mr-1"></i> Complete</button></form>';
                        }

                        $deleteButton = Auth::user()->role == "global_admin" ? '<button type="button" class="btn btn-outline-danger btn-sm font-weight-bold mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="'.$order->id.'" title="Delete Order"><i class="fa fa-trash mr-1"></i> Delete</button>' : '';
                                            
                        return '<div class="d-flex align-items-center flex-wrap" style="gap: 4px;">' . $viewButton . $printButton . $completeButton . $deleteButton . '</div>';
                    })
                    ->editColumn('order_no', function ($order) {
                        $diff = $order->created_at->diffForHumans();
                        return '<div class="font-weight-bold text-dark" style="font-size: 0.95rem;">#' . $order->order_no . '</div><small class="text-muted"><i class="fa fa-clock-o mr-1"></i> ' . $diff . '</small>';
                    })
                    ->addColumn('details', function ($order) {
                        if ($order->order_type === 'instore') {
                            $tableName = $order->restaurantTable ? $order->restaurantTable->name : 'N/A';
                            $waiterName = $order->waiter ? $order->waiter->name : 'N/A';
                            return '<div class="font-weight-bold text-dark"><i class="fa fa-table text-primary mr-1"></i> ' . e($tableName) . '</div><small class="text-muted"><i class="fa fa-user mr-1"></i> Waiter: ' . e($waiterName) . '</small>';
                        } else {
                            $custName = $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest';
                            $phone = $order->customer ? $order->customer->phone : '';
                            return '<div class="font-weight-bold text-dark"><i class="fa fa-user text-info mr-1"></i> ' . e($custName) . '</div>' . ($phone ? '<small class="text-muted"><i class="fa fa-phone mr-1"></i> ' . e($phone) . '</small>' : '');
                        }
                    })
                    ->addColumn('items_preview', function ($order) {
                        $count = $order->orderItems->sum('quantity');
                        $itemNames = $order->orderItems->take(2)->pluck('menu_name')->toArray();
                        $previewStr = implode(', ', $itemNames);
                        if ($order->orderItems->count() > 2) {
                            $previewStr .= '...';
                        }
                        return '<div><span class="badge badge-light border font-weight-bold text-dark">' . $count . ' item' . ($count === 1 ? '' : 's') . '</span></div><small class="text-muted d-block text-truncate" style="max-width: 180px;" title="' . e($previewStr) . '">' . e($previewStr) . '</small>';
                    })
                    ->editColumn('created_at', function ($order) {
                        return $order->created_at->format('g:i A - j M, Y');
                    })          
                    ->editColumn('total_price', function ($order) {
                        $site_settings = SiteSetting::latest()->first();
                        $currency_symbol = $site_settings->currency_symbol ?? config('site.currency_symbol');
                        return '<span class="font-weight-bold text-dark" style="font-size: 1rem;">' . html_entity_decode($currency_symbol) . number_format($order->total_price, 2) . '</span>';
                    })
                    ->addColumn('payment', function ($order) {
                        $method = $order->payment_method ? e($order->payment_method) : 'Pending';
                        $html = '<div class="font-weight-bold text-dark" style="font-size: 0.88rem;"><i class="fa fa-credit-card text-muted mr-1"></i> ' . $method . '</div>';
                        if ($order->order_type == 'online' || $order->order_type == 'delivery' || $order->order_type == 'pickup') {
                            if ($order->status_online_pay == 'paid') {
                                $html .= '<span class="badge badge-success" style="font-size:0.72rem;">Paid</span>';
                            } else {
                                $html .= '<span class="badge badge-danger" style="font-size:0.72rem;">Unpaid</span>';
                            }
                        }
                        return $html;
                    })
                    ->editColumn('status', function ($order) {
                        switch ($order->status) {
                            case 'pending':
                                return '<span class="badge badge-warning text-dark font-weight-bold px-3 py-2" style="border-radius: 20px;"><i class="fa fa-clock-o mr-1"></i> Pending</span>';
                            case 'completed':
                                return '<span class="badge badge-success text-white font-weight-bold px-3 py-2" style="border-radius: 20px;"><i class="fa fa-check mr-1"></i> Completed</span>';
                            case 'cancelled':
                                return '<span class="badge badge-danger text-white font-weight-bold px-3 py-2" style="border-radius: 20px;"><i class="fa fa-times mr-1"></i> Cancelled</span>';
                            default:
                                return '<span class="badge badge-secondary font-weight-bold px-3 py-2" style="border-radius: 20px;">' . ucfirst($order->status) . '</span>';
                        }
                    })
                    ->editColumn('order_type', function ($order) {
                        return '<span class="badge badge-light border font-weight-bold">' . ucfirst($order->order_type) . '</span>';
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
        }

        // Clear the cart
        session()->forget($request->cartkey);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'customer_receipt_url' => route('admin.orders.receipt', $order->id),
                'kitchen_ticket_url' => route('admin.orders.receipt', $order->id) . '?kitchen=1',
                'message' => 'Order #' . $order->order_no . ' created/updated successfully.'
            ]);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order created/updated successfully.');
    }

    
    public function update(Request $request, $id)
    {
        // Validate the input data
        $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);
        $order = Order::findOrFail($id);

        $order->update(['status' => $request->status , 'updated_by_user_id' => Auth::id()]);
        
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
        $order = Order::with(['orderItems', 'customer', 'restaurantTable', 'waiter'])->findOrFail($id);
        
        if ($order->order_type == 'instore' && $request->has('kitchen')) {
            $unprintedItems = $order->orderItems()->whereNull('print_batch')->get();
            if ($unprintedItems->isNotEmpty()) {
                return view('admin.orders-kitchen-ticket', compact('order', 'unprintedItems'));
            }
        }
        
        return view('admin.orders-receipt', compact('order'));
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
