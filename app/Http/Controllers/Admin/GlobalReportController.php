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
        $startDateInput = $request->input('start_date', $request->input('date'));
        $endDateInput = $request->input('end_date', $request->input('date'));

        try {
            $startDate = $startDateInput ? Carbon::parse($startDateInput)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        } catch (\Exception $e) {
            $startDate = Carbon::today()->format('Y-m-d');
        }

        try {
            $endDate = $endDateInput ? Carbon::parse($endDateInput)->format('Y-m-d') : $startDate;
        } catch (\Exception $e) {
            $endDate = $startDate;
        }

        if ($endDate < $startDate) {
            $endDate = $startDate;
        }

        $selectedDate = $startDate; // For backwards compatibility

        // ==========================================
        // 1. FOOD & RESTAURANT SALES (POS/ORDERS)
        // ==========================================
        $ordersQuery = Order::with(['customer', 'waiter', 'restaurantTable', 'orderItems.menu'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

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
        $cashSales = (clone $ordersQuery)->where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_method', 'Cash')
                  ->orWhere('payment_method', 'cash');
            })->sum('total_price');

        $momoPaySales = (clone $ordersQuery)->where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_method', 'LIKE', '%momo%')
                  ->orWhere('payment_method', 'LIKE', '%mobile%');
            })->sum('total_price');

        $bankCardSales = (clone $ordersQuery)->where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_method', 'LIKE', '%bank%')
                  ->orWhere('payment_method', 'LIKE', '%card%')
                  ->orWhere('payment_method', 'WEFLEXFY');
            })->sum('total_price');

        $otherSales = max(0, $salesTotal - ($cashSales + $momoPaySales + $bankCardSales));

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
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($q2) use ($startDate, $endDate) {
                    $q2->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
                })->orWhere(function ($q2) use ($startDate, $endDate) {
                    $q2->whereDate('check_in_date', '>=', $startDate)->whereDate('check_in_date', '<=', $endDate);
                })->orWhere(function ($q2) use ($startDate, $endDate) {
                    $q2->whereDate('check_out_date', '>=', $startDate)->whereDate('check_out_date', '<=', $endDate);
                });
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
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where(function ($q2) use ($startDate, $endDate) {
                    $q2->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
                })->orWhere(function ($q2) use ($startDate, $endDate) {
                    $q2->whereDate('booking_date', '>=', $startDate)->whereDate('booking_date', '<=', $endDate);
                });
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
            'startDate',
            'endDate',
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
            'cashSales',
            'momoPaySales',
            'bankCardSales',
            'otherSales',
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
