<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Mail\PasswordChangedNotification;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Controllers\Traits\OrderStatisticsTrait;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class AdminController extends Controller
{
    use OrderStatisticsTrait;
    use AdminViewSharedDataTrait;


    public function __construct()
    {
        $this->shareAdminViewData();
        $this->shareOrderStatistics();
        
    }
    
    public function index()
    {
        if (Auth::user() && Auth::user()->role === 'sales') {
            return redirect()->route('admin.pos.index')->with('error', 'You do not have permission to access the main dashboard.');
        }

        $currentYear = now()->year;
    
        $isSqlite = \DB::connection()->getDriverName() === 'sqlite';
        $monthQuery = $isSqlite ? "strftime('%m', created_at)" : "MONTH(created_at)";
        
        $salesData = \DB::table('orders')
            ->selectRaw("{$monthQuery} as month_num, SUM(total_price) as total_sales")
            ->whereYear('created_at', $currentYear)
            ->where('status', 'completed')
            ->groupBy('month_num')
            ->pluck('total_sales', 'month_num');
    
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 
            7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        $formattedSalesData = collect($months)->mapWithKeys(function ($monthName, $monthNum) use ($salesData) {
            // Match int (MySQL) or padded string (SQLite)
            $sales = $salesData->get($monthNum, 0) ?: $salesData->get(sprintf('%02d', $monthNum), 0);
            return [$monthName => $sales];
        });

        // Sales Today
        $salesToday = Order::whereDate('created_at', now()->today())
            ->where('status', 'completed')
            ->sum('total_price');

        $cashSalesToday = Order::whereDate('created_at', now()->today())
            ->where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_method', 'Cash')->orWhere('payment_method', 'cash');
            })->sum('total_price');

        $momoSalesToday = Order::whereDate('created_at', now()->today())
            ->where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_method', 'LIKE', '%momo%')->orWhere('payment_method', 'LIKE', '%mobile%');
            })->sum('total_price');

        $bankCardSalesToday = Order::whereDate('created_at', now()->today())
            ->where('status', 'completed')
            ->where(function($q) {
                $q->where('payment_method', 'LIKE', '%bank%')->orWhere('payment_method', 'LIKE', '%card%')->orWhere('payment_method', 'WEFLEXFY');
            })->sum('total_price');

        // Active Room Bookings (pending or confirmed)
        $activeRoomBookings = \App\Models\RoomBooking::whereIn('status', ['pending', 'confirmed'])->count();

        // Active Venue Bookings (pending or confirmed)
        $activeVenueBookings = \App\Models\VenueBooking::whereIn('status', ['pending', 'confirmed'])->count();

        // Total Customers
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
    
        return view('admin.dashboard', compact('formattedSalesData', 'salesToday', 'cashSalesToday', 'momoSalesToday', 'bankCardSalesToday', 'activeRoomBookings', 'activeVenueBookings', 'totalCustomers'));
    }
    

    public function viewMyProfile()
    {
        $user = Auth::User();  
        return view('admin.view-my-profile', compact('user'));
    }


    public function editMyProfile()
    {
        $user = Auth::User();  
        return view('admin.edit-my-profile', compact('user'));
    }

    public function updateMyProfile(UpdateProfileRequest $request)
    {
        $user = Auth::User();
        $validated = $request->validated();
    
        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name']; // Optional, so it can be null
        $user->last_name = $validated['last_name'];        
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'];
        $user->address = $validated['address'];
    
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_picture) {
                Storage::delete('profile-picture/' . $user->profile_picture);
            }
    
            // Store new profile photo
            $photoPath = $request->file('profile_photo')->store('profile-picture', 'public');
            $user->profile_picture = basename($photoPath);
        }
    
        // Save the updated user data
        $user->save();
    
        // Return success message
        return back()->with('success', 'Profile updated successfully.');
    }
    

    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::User();

        // Check if the current password matches the user's password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Send password changed notification email
        Mail::to($user->email)->send(new PasswordChangedNotification($user));

        return redirect()->route('admin.dashboard')->with('success', 'Your password has been successfully updated.');
    }    


    
}
