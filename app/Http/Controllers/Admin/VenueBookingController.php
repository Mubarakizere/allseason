<?php

namespace App\Http\Controllers\Admin;

use App\Models\VenueBooking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class VenueBookingController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $bookings = VenueBooking::with(['venue', 'package'])->orderBy('created_at', 'desc')->get();
        return view('admin.venue-bookings', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $booking = VenueBooking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'payment_status' => 'required|in:unpaid,deposit_paid,fully_paid',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking updated successfully!');
    }

    public function destroy($id)
    {
        $booking = VenueBooking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', 'Booking deleted successfully!');
    }
}
