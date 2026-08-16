<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\VenuePackage;
use App\Models\VenueBooking;
use App\Models\SiteSetting;
use App\Models\OrderSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VenueBookingEmail;
use App\Mail\AdminVenueBookingNotificationEmail;
use App\Services\WeFlexfyService;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\MainSiteViewSharedDataTrait;

class VenueController extends Controller
{
    use CartTrait;
    use MainSiteViewSharedDataTrait;

    public function __construct()
    {
        $this->shareMainSiteViewData();
    }

    public function index()
    {
        $venues = Venue::with('packages')->get();
        return view('main-site.venues.index', compact('venues'));
    }

    public function show($id)
    {
        $venue = Venue::with('packages.features')->findOrFail($id);
        return view('main-site.venues.show', compact('venue'));
    }

    public function checkAvailability(Request $request)
    {
        $venue_id = $request->venue_id;
        $date = Carbon::parse($request->date)->format('Y-m-d');

        $exists = VenueBooking::where('venue_id', $venue_id)
            ->where('booking_date', $date)
            ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        return response()->json(['available' => !$exists]);
    }

    public function checkout(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('auth.login')->withErrors('You must be logged in to book a venue.');
        }

        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'package_id' => 'required|exists:venue_packages,id',
            'booking_date' => 'required|date',
            'customer_phone' => 'required|string',
        ]);

        $venue = Venue::findOrFail($request->venue_id);
        $package = VenuePackage::findOrFail($request->package_id);
        $date = Carbon::parse($request->booking_date)->format('Y-m-d');

        // Check availability again
        $exists = VenueBooking::where('venue_id', $venue->id)
            ->where('booking_date', $date)
            ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withErrors('This venue is already booked on the selected date.');
        }

        $deposit_amount = round(($package->price * $venue->deposit_percentage) / 100, 2);

        $booking = VenueBooking::create([
            'venue_id' => $venue->id,
            'venue_package_id' => $package->id,
            'customer_name' => trim(auth()->user()->first_name . ' ' . auth()->user()->last_name),
            'customer_email' => auth()->user()->email,
            'customer_phone' => $request->customer_phone,
            'booking_date' => $date,
            'total_price' => $package->price,
            'deposit_amount' => $deposit_amount,
            'payment_status' => 'unpaid',
            'status' => 'pending',
        ]);

        $weFlexfyService = app(WeFlexfyService::class);

        $transfers = [
            [
                'percentage' => 100,
                'recipientNumber' => config('payments.weflexfy.recipient_number', '') ?: $request->customer_phone,
                'payload' => [
                    'type' => 'venue',
                    'booking_id' => $booking->id,
                    'internalRef' => 'VENUE-' . $booking->id,
                ]
            ]
        ];

        $response = $weFlexfyService->initiatePayment(
            $deposit_amount,
            $booking->customer_name,
            $booking->customer_email,
            $booking->customer_phone,
            $transfers,
            config('payments.weflexfy.currency', 'RWF')
        );

        if (!$response['success'] || empty($response['iframeUrl'])) {
            return back()->withErrors($response['message'] ?? 'Unable to initialize payment portal. Please try again.');
        }

        $booking->weflexfy_request_token = $response['requestToken'];
        $booking->save();

        $siteSettings = SiteSetting::latest()->first();

        return view('main-site.weflexfy-pay', [
            'iframeUrl' => $response['iframeUrl'],
            'amount' => $deposit_amount,
            'currencySymbol' => $siteSettings?->currency_symbol ?? 'RWF',
            'title' => 'Venue Booking #' . $booking->id . ' Deposit',
            'redirectUrl' => route('venues.success', ['booking_id' => $booking->id, 'session_id' => $response['requestToken']]),
        ]);
    }

    public function success(Request $request)
    {
        $booking_id = $request->query('booking_id');
        $session_id = $request->query('session_id') ?? $request->query('token');

        if (!$booking_id && !$session_id) {
            return redirect()->route('venues.index')->withErrors('Booking reference not found!');
        }

        try {
            $bookingQuery = VenueBooking::query();
            if ($booking_id) {
                $bookingQuery->where('id', $booking_id);
            }
            if ($session_id) {
                $bookingQuery->orWhere('weflexfy_request_token', $session_id);
            }

            $booking = $bookingQuery->firstOrFail();

            if ($booking->payment_status === 'unpaid') {
                $booking->update([
                    'payment_status' => 'deposit_paid',
                    'status' => 'confirmed'
                ]);

                try {
                    Mail::to($booking->customer_email)->send(new VenueBookingEmail($booking));
                } catch (\Exception $e) {
                    Log::error('Venue booking customer email failed to send: ' . $e->getMessage());
                }

                try {
                    $orderSettings = OrderSettings::first();
                    if ($orderSettings && $orderSettings->notification_emails) {
                        $emails = array_map('trim', explode(',', $orderSettings->notification_emails));
                        foreach ($emails as $email) {
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                Mail::to($email)->send(new AdminVenueBookingNotificationEmail($booking));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Venue booking admin notification email failed to send: ' . $e->getMessage());
                }
            }

            return view('main-site.venues.success', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('venues.index')->withErrors($e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        $booking_id = $request->query('booking_id');
        if ($booking_id) {
            VenueBooking::where('id', $booking_id)->where('payment_status', 'unpaid')->delete();
        }

        return redirect()->route('venues.index')->withErrors('Payment was cancelled.');
    }
}
