<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\SiteSetting;
use App\Models\OrderSettings;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\MainSiteViewSharedDataTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\RoomBookingConfirmation;
use App\Mail\AdminRoomBookingNotificationEmail;
use App\Services\WeFlexfyService;

class FrontRoomController extends Controller
{
    use CartTrait;
    use MainSiteViewSharedDataTrait;

    public function __construct()
    {
        $this->shareMainSiteViewData();
    }

    public function index()
    {
        $rooms = Room::with('images', 'features')->get();
        return view('main-site.rooms.index', compact('rooms'));
    }

    public function show($id)
    {
        $room = Room::with('images', 'features')->findOrFail($id);
        return view('main-site.rooms.show', compact('room'));
    }

    public function checkAvailability(Request $request)
    {
        $room_id = $request->room_id;
        $check_in = Carbon::parse($request->check_in_date)->format('Y-m-d');
        $check_out = Carbon::parse($request->check_out_date)->format('Y-m-d');

        if ($check_in >= $check_out) {
            return response()->json(['available' => false, 'message' => 'Check-out date must be after check-in date.']);
        }

        $exists = RoomBooking::where('room_id', $room_id)
            ->where('check_in_date', '<', $check_out)
            ->where('check_out_date', '>', $check_in)
            ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        return response()->json(['available' => !$exists]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
        ]);

        $room = Room::findOrFail($request->room_id);
        $check_in = Carbon::parse($request->check_in_date)->format('Y-m-d');
        $check_out = Carbon::parse($request->check_out_date)->format('Y-m-d');

        // Calculate nights
        $nights = Carbon::parse($check_in)->diffInDays(Carbon::parse($check_out));
        if ($nights < 1) $nights = 1;

        // Check availability again
        $exists = RoomBooking::where('room_id', $room->id)
            ->where('check_in_date', '<', $check_out)
            ->where('check_out_date', '>', $check_in)
            ->whereIn('payment_status', ['deposit_paid', 'fully_paid'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($exists) {
            return back()->withErrors('This room is already booked for the selected dates.');
        }

        $total_price = $room->price * $nights;
        $deposit_amount = round(($total_price * $room->deposit_percentage) / 100, 2);

        $booking = RoomBooking::create([
            'room_id' => $room->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'check_in_date' => $check_in,
            'check_out_date' => $check_out,
            'total_price' => $total_price,
            'deposit_amount' => $deposit_amount,
            'payment_status' => 'unpaid',
            'status' => 'pending',
            'stripe_session_id' => null,
        ]);

        $weFlexfyService = app(WeFlexfyService::class);

        $transfers = [
            [
                'percentage' => 100,
                'recipientNumber' => config('payments.weflexfy.recipient_number', '') ?: $request->customer_phone,
                'payload' => [
                    'type' => 'room',
                    'booking_id' => $booking->id,
                    'internalRef' => 'ROOM-' . $booking->id,
                ]
            ]
        ];

        $response = $weFlexfyService->initiatePayment(
            $deposit_amount,
            $request->customer_name,
            $request->customer_email,
            $request->customer_phone,
            $transfers,
            config('payments.weflexfy.currency', 'RWF')
        );

        if (!$response['success'] || empty($response['iframeUrl'])) {
            return back()->withErrors($response['message'] ?? 'Unable to initialize payment portal. Please try again.');
        }

        $booking->weflexfy_request_token = $response['requestToken'];
        $booking->stripe_session_id = $response['requestToken'];
        $booking->save();

        $siteSettings = SiteSetting::latest()->first();

        return view('main-site.weflexfy-pay', [
            'iframeUrl' => $response['iframeUrl'],
            'amount' => $deposit_amount,
            'currencySymbol' => $siteSettings->currency_symbol ?? 'RWF',
            'title' => 'Room Booking #' . $booking->id . ' Deposit',
            'redirectUrl' => route('rooms.success', ['session_id' => $response['requestToken']]),
        ]);
    }

    public function success(Request $request)
    {
        $session_id = $request->query('session_id') ?? $request->query('token');

        if (!$session_id) {
            return redirect()->route('rooms.index')->withErrors('Session ID not found!');
        }

        try {
            $booking = RoomBooking::where('weflexfy_request_token', $session_id)
                ->orWhere('stripe_session_id', $session_id)
                ->firstOrFail();

            if ($booking->payment_status === 'unpaid') {
                $booking->update([
                    'payment_status' => 'deposit_paid',
                    'status' => 'confirmed'
                ]);

                try {
                    Mail::to($booking->customer_email)->send(new RoomBookingConfirmation($booking));
                } catch (\Exception $e) {
                    Log::error('Room booking customer email failed to send: ' . $e->getMessage());
                }

                try {
                    $orderSettings = OrderSettings::first();
                    if ($orderSettings && $orderSettings->notification_emails) {
                        $emails = array_map('trim', explode(',', $orderSettings->notification_emails));
                        foreach ($emails as $email) {
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                Mail::to($email)->send(new AdminRoomBookingNotificationEmail($booking));
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Room booking admin notification email failed to send: ' . $e->getMessage());
                }
            }

            return view('main-site.rooms.success', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('rooms.index')->withErrors('Booking not found or payment failed.');
        }
    }

    public function cancel(Request $request)
    {
        $booking_id = $request->query('booking_id');
        if ($booking_id) {
            RoomBooking::where('id', $booking_id)->where('payment_status', 'unpaid')->delete();
        }

        return redirect()->route('rooms.index')->withErrors('Payment was cancelled.');
    }
}
