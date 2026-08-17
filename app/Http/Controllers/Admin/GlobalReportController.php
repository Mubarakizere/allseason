<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;
use App\Http\Controllers\Traits\OrderStatisticsTrait;
use App\Models\Order;
use App\Models\RoomBooking;
use App\Models\VenueBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GlobalReportController extends Controller
{
    use AdminViewSharedDataTrait;
    use OrderStatisticsTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
        $this->shareOrderStatistics();
    }

    public function index(Request $request)
    {
        $selectedDateInput = $request->input('date');

        try {
            $selectedDate = $selectedDateInput ? Carbon::parse($selectedDateInput)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        } catch (\Exception $e) {
            $selectedDate = Carbon::today()->format('Y-m-d');
        }

        // ==========================================
        // 1. FOOD & RESTAURANT SALES (POS/ORDERS)
        // ==========================================
        $ordersQuery = Order::with(['customer', 'waiter', 'restaurantTable', 'orderItems.menu'])
            ->whereDate('created_at', $selectedDate);

        $orders = (clone $ordersQuery)->orderBy('created_at', 'desc')->get();
        $ordersCount = $orders->count();
        $salesTotal = (clone $ordersQuery)->where('status', 'completed')->sum('total_price');
        $allOrdersTotal = (clone $ordersQuery)->sum('total_price');

        $completedOrdersCount = $orders->where('status', 'completed')->count();
        $pendingOrdersCount = $orders->where('status', 'pending')->count();
        $cancelledOrdersCount = $orders->whereIn('status', ['cancelled', 'failed'])->count();

        $instoreOrdersCount = $orders->where('order_type', 'instore')->count();
        $deliveryOrdersCount = $orders->where('order_type', 'delivery')->count();

        // Payment method breakdown for orders
        $paymentMethodsBreakdown = $orders->groupBy('payment_method')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total' => $group->where('status', 'completed')->sum('total_price'),
            ];
        });

        // ==========================================
        // 2. ROOM BOOKINGS
        // ==========================================
        $roomBookings = RoomBooking::with('room')
            ->where(function ($q) use ($selectedDate) {
                $q->whereDate('created_at', $selectedDate)
                    ->orWhereDate('check_in_date', $selectedDate)
                    ->orWhereDate('check_out_date', $selectedDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $roomsCount = $roomBookings->count();
        $roomsRevenue = $roomBookings->whereIn('status', ['confirmed', 'completed', 'checked_in', 'checked_out'])->sum('total_price');
        $roomsDepositTotal = $roomBookings->sum('deposit_amount');

        $roomStatusBreakdown = [
            'confirmed' => $roomBookings->where('status', 'confirmed')->count(),
            'pending'   => $roomBookings->where('status', 'pending')->count(),
            'cancelled' => $roomBookings->where('status', 'cancelled')->count(),
            'completed' => $roomBookings->whereIn('status', ['completed', 'checked_out'])->count(),
        ];

        // ==========================================
        // 3. VENUE BOOKINGS
        // ==========================================
        $venueBookings = VenueBooking::with(['venue', 'package'])
            ->where(function ($q) use ($selectedDate) {
                $q->whereDate('created_at', $selectedDate)
                    ->orWhereDate('booking_date', $selectedDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $venuesCount = $venueBookings->count();
        $venuesRevenue = $venueBookings->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $venuesDepositTotal = $venueBookings->sum('deposit_amount');

        $venueStatusBreakdown = [
            'confirmed' => $venueBookings->where('status', 'confirmed')->count(),
            'pending'   => $venueBookings->where('status', 'pending')->count(),
            'cancelled' => $venueBookings->where('status', 'cancelled')->count(),
        ];

        // ==========================================
        // 4. CONSOLIDATED SUMMARY METRICS
        // ==========================================
        $combinedTotalRevenue = $salesTotal + $roomsRevenue + $venuesRevenue;
        $combinedTotalTransactions = $ordersCount + $roomsCount + $venuesCount;

        return view('admin.reports.global', compact(
            'selectedDate',
            'orders',
            'ordersCount',
            'salesTotal',
            'allOrdersTotal',
            'completedOrdersCount',
            'pendingOrdersCount',
            'cancelledOrdersCount',
            'instoreOrdersCount',
            'deliveryOrdersCount',
            'paymentMethodsBreakdown',
            'roomBookings',
            'roomsCount',
            'roomsRevenue',
            'roomsDepositTotal',
            'roomStatusBreakdown',
            'venueBookings',
            'venuesCount',
            'venuesRevenue',
            'venuesDepositTotal',
            'venueStatusBreakdown',
            'combinedTotalRevenue',
            'combinedTotalTransactions'
        ));
    }
}
