<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomBooking;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class RoomBookingController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $bookings = RoomBooking::with('room')->latest()->get();
        $site_settings = SiteSetting::first();
        return view('admin.room-bookings', compact('bookings', 'site_settings'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'payment_status' => 'required|in:unpaid,deposit_paid,fully_paid',
        ]);

        $booking = RoomBooking::findOrFail($id);
        $booking->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Booking updated successfully.');
    }

    public function destroy($id)
    {
        $booking = RoomBooking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Booking deleted successfully.');
    }
}
