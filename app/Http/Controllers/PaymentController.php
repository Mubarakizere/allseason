<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\OrderEmail;
use App\Mail\AdminOrderNotificationEmail;
use App\Models\Customer;
use App\Models\OrderSettings;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Helpers\TwilioHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\RestaurantPhoneNumber;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Traits\CartTrait;
use App\Http\Controllers\Traits\MainSiteViewSharedDataTrait;
use App\Http\Controllers\Traits\StockDeductionTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\RoomBooking;
use App\Models\VenueBooking;
use App\Services\WeFlexfyService;
use App\Mail\RoomBookingConfirmation;
use App\Mail\AdminRoomBookingNotificationEmail;
use App\Mail\VenueBookingEmail;
use App\Mail\AdminVenueBookingNotificationEmail;

class PaymentController extends Controller
{
    use CartTrait;
    use MainSiteViewSharedDataTrait;
    use StockDeductionTrait;


    protected $provider;

    public function __construct()
    {
        $this->shareMainSiteViewData();

        $this->provider = config('payments.provider', 'weflexfy');
    }

    
 

    public function paymentCancel()
    {
        return view('main-site.payment-cancel');
    }

 
    // public function paymentSuccess(Request $request)
    // {
    //     //run all required session checks
    //     $this->runAllChecks();

    //     // Set Stripe secret key
    //     Stripe::setApiKey(config('services.stripe.secret'));
    
    //     // Retrieve the session ID from the request
    //     $session_id = $request->query('session_id');

 

    //     if ($session_id) {
    //         try {

    //                 // Retrieve the checkout session
    //                 $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);

    //                 $order_no = $checkout_session->metadata->order_no;

    //                 $order = Order::with(['orderItems', 'customer'])->where('order_no', $order_no)->first();
    //                 $order->session_id = $session_id;
    //                 $order->save();
                    
    //                 if (!$order) {
    //                     throw new NotFoundHttpException();
    //                     // return redirect()->route('menu')->withErrors('Order verification failed');

    //                 }
                   

    //                 if ($order->status_online_pay === 'unpaid') {
    //                     $order->status_online_pay = 'paid';
    //                     $order->save();

    //                     // Send the email
    //                     try {
    //                         Mail::to($order->customer->email)->send(new OrderEmail(
    //                             $order->orderItems,
    //                             $order->customer->first_name,
    //                             $order->customer->email,
    //                             $order->order_no,
    //                             $order->delivery_fee,
    //                             $order->total_price,
    //                             config('site.email'),
    //                             RestaurantPhoneNumber::first() ? RestaurantPhoneNumber::first()->phone_number : null
    //                         ));
    //                     } catch (Exception $e) {
    //                         Log::error('Order email failed to send: ' . $e->getMessage());
    //                     }
                        
    //                     // send whatsapp message
    //                     $this->sendWhatsAppNotification($order);    

    //                     // Clear the session
    //                     $this->clearOrderSession();
                        
    //                     return view('main-site.payment-success', compact('order'));                       
    //                 }
    //                 elseif ($order->status_online_pay === 'paid') { 

    //                     // Clear the session
    //                     $this->clearOrderSession();
    //                     return view('main-site.payment-success', compact('order'));                       

    //                 }
 
                    
    //                 return redirect()->route('menu')->withErrors("There was an issue processing your payment. Please try again.");



    //         } catch (Exception $e) {
    //             $error_msg  =  $e->getMessage();
    //             return redirect()->route('menu')->withErrors($error_msg);
    //         }
    //     } else {
    //         return redirect()->route('menu')->withErrors('Session ID not found!');
    //     }
    // }
    

    public function paymentSuccess(Request $request)
    {
        $this->runAllChecks();
        return $this->handleWeFlexfySuccess($request);
    }




    // Check if a session key exists and the cart is not empty, otherwise redirect with an error message
    // protected function checkCart()
    // {
 
    //     if (!session()->has($this->cartkey) || empty(session()->get($this->cartkey))) {
    //         return redirect()->route('menu')->withErrors('Your cart is empty. Please add items to your cart before checking out.')->send();
    //     }
    // }

    // // Check if a session customer_details exists, otherwise redirect with an error message
    // protected function checkCustomerDetails()
    // {
    //     if (!session()->has('customer_details')) {
    //         return redirect()->route('menu')->withErrors('We could not retrieve your customer details. Please try again or contact support if the issue persists.')->send();
    //     }
    // }

    // // Check if a session delivery_details exists, otherwise redirect with an error message
    // protected function checkDeliveryDetails()
    // {
    //     if (!session()->has('delivery_details')) {
    //         return redirect()->route('menu')->withErrors('We could not retrieve your delivery details. Please try again or contact support if the issue persists.')->send();
    //     }
    // }

    // Check if a session order_no exists, otherwise redirect with an error message
    protected function checkOrderNo()
    {
        if (!session()->has('order_no')) {
            //return redirect()->route('menu')->withErrors('We could not retrieve your order number. Please try again or contact support if the issue persists.')->send();
            return redirect()->route('menu')->send();
        }
    }




    // Call all checks at once
    protected function runAllChecks()
    {
        // $this->checkCart();
        // $this->checkCustomerDetails();
        // $this->checkDeliveryDetails();
        // $this->checkOrderNo();
    }

    protected function clearOrderSession()
    {
        session()->forget([
            'customer',
            'customer_details',
            'delivery_details',
            'order_no'
        ]);
    }

    protected function sendWhatsAppNotification(Order $order)
    {
        try {
            TwilioHelper::sendWhatsAppMessage($order->customer->phone_number, $order->order_no, $order->customer->name);
        } catch (Exception $e) {
            Log::error('Failed to send WhatsApp message: ' . $e->getMessage());
        }
    }

    private function handleWeFlexfySuccess(Request $request)
    {
        $token = $request->query('token') ?? $request->query('session_id') ?? $request->query('requestToken');
        $order_no = $request->query('order_no');

        if (!$token && !$order_no) {
            return redirect()->route('menu')->withErrors('Payment reference not found.');
        }

        $orderQuery = Order::with(['orderItems', 'customer']);
        if ($token) {
            $orderQuery->where(function ($q) use ($token) {
                $q->where('weflexfy_request_token', $token)
                  ->orWhere('session_id', $token);
            });
        }
        if ($order_no) {
            $orderQuery->orWhere('order_no', $order_no);
        }

        $order = $orderQuery->first();

        if (!$order) {
            return redirect()->route('menu')->withErrors('Order not found for this transaction.');
        }

        if ($order->status_online_pay === 'unpaid') {
            $order->status_online_pay = 'paid';
            $order->payment_method = 'WEFLEXFY';
            if ($token) {
                $order->weflexfy_request_token = $token;
            }
            $order->save();

            try {
                Mail::to($order->customer->email)->send(new OrderEmail(
                    $order->orderItems,
                    $order->customer->first_name ?? $order->customer->name,
                    $order->customer->email,
                    $order->order_no,
                    $order->delivery_fee,
                    $order->total_price,
                    config('site.email'),
                    RestaurantPhoneNumber::first()?->phone_number
                ));
            } catch (\Exception $e) {
                Log::error('Order email failed to send (weflexfy): '.$e->getMessage());
            }

            try {
                $orderSettings = OrderSettings::first();
                if ($orderSettings && $orderSettings->notification_emails) {
                    $emails = array_map('trim', explode(',', $orderSettings->notification_emails));
                    foreach ($emails as $email) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            Mail::to($email)->send(new AdminOrderNotificationEmail($order));
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Admin order notification email failed to send (weflexfy): '.$e->getMessage());
            }

            $this->sendWhatsAppNotification($order);
            $this->deductStockForOrder($order, 'Online WeFlexfy Order');
            $this->clearOrderSession();
        }

        return view('main-site.payment-success', compact('order'));
    }

    public function handleWeFlexfyWebhook(Request $request, WeFlexfyService $weFlexfyService)
    {
        Log::info('WeFlexfy Webhook Received:', $request->all());

        $token = $request->input('token');
        $requestType = $request->input('requestType', 'payment');

        if (!$token) {
            Log::warning('WeFlexfy Webhook Error: Token missing in payload.');
            return response()->json(['message' => 'Token missing'], 400);
        }

        $payload = $weFlexfyService->verifyWebhookJwt($token);

        if (!$payload) {
            Log::error('WeFlexfy Webhook Error: Invalid JWT signature or payload verification failed.');
            return response()->json(['message' => 'Invalid webhook token signature'], 401);
        }

        Log::info('WeFlexfy Webhook Verified Payload:', ['requestType' => $requestType, 'payload' => $payload]);

        try {
            if ($requestType === 'transfer') {
                $this->processWeFlexfyTransferWebhook($payload);
            } else {
                $this->processWeFlexfyPaymentWebhook($payload);
            }

            return response()->json(['message' => 'Webhook processed successfully'], 200);

        } catch (Exception $e) {
            Log::error('WeFlexfy Webhook Processing Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Server error processing webhook'], 500);
        }
    }

    private function processWeFlexfyPaymentWebhook(array $payload)
    {
        $requestToken = $payload['requestToken'] ?? null;
        $paymentRef = $payload['paymentRef'] ?? null;
        $status = strtoupper($payload['status'] ?? '');

        if (!$requestToken && !$paymentRef) {
            Log::warning('WeFlexfy Payment Webhook: Neither requestToken nor paymentRef present in payload.');
            return;
        }

        // 1. Check Orders
        $order = Order::where(function ($q) use ($requestToken) {
            if ($requestToken) $q->where('weflexfy_request_token', $requestToken)->orWhere('session_id', $requestToken);
        })->first();

        if ($order) {
            if ($status === 'SUCCESS') {
                if ($order->status_online_pay !== 'paid') {
                    $order->status_online_pay = 'paid';
                    $order->payment_method = 'WEFLEXFY';
                    $order->save();

                    try {
                        Mail::to($order->customer->email)->send(new OrderEmail(
                            $order->orderItems,
                            $order->customer->first_name ?? $order->customer->name,
                            $order->customer->email,
                            $order->order_no,
                            $order->delivery_fee,
                            $order->total_price,
                            config('site.email'),
                            RestaurantPhoneNumber::first()?->phone_number
                        ));
                    } catch (Exception $e) {
                        Log::error('Order email failed (weflexfy webhook): ' . $e->getMessage());
                    }

                    try {
                        $orderSettings = OrderSettings::first();
                        if ($orderSettings && $orderSettings->notification_emails) {
                            $emails = array_map('trim', explode(',', $orderSettings->notification_emails));
                            foreach ($emails as $email) {
                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    Mail::to($email)->send(new AdminOrderNotificationEmail($order));
                                }
                            }
                        }
                    } catch (Exception $e) {
                        Log::error('Admin order email failed (weflexfy webhook): ' . $e->getMessage());
                    }

                    $this->sendWhatsAppNotification($order);
                    $this->deductStockForOrder($order, 'WeFlexfy Webhook Payment');
                }
            } elseif ($status === 'FAILED') {
                $order->status_online_pay = 'failed';
                $order->save();
            }
            return;
        }

        // 2. Check Room Bookings
        $roomBooking = RoomBooking::where(function ($q) use ($requestToken) {
            if ($requestToken) $q->where('weflexfy_request_token', $requestToken);
        })->first();

        if ($roomBooking) {
            if ($status === 'SUCCESS') {
                if ($roomBooking->payment_status !== 'deposit_paid' && $roomBooking->payment_status !== 'fully_paid') {
                    $roomBooking->update([
                        'payment_status' => 'deposit_paid',
                        'status' => 'confirmed'
                    ]);

                    try {
                        Mail::to($roomBooking->customer_email)->send(new RoomBookingConfirmation($roomBooking));
                    } catch (Exception $e) {
                        Log::error('Room booking customer email failed (weflexfy webhook): ' . $e->getMessage());
                    }

                    try {
                        $orderSettings = OrderSettings::first();
                        if ($orderSettings && $orderSettings->notification_emails) {
                            $emails = array_map('trim', explode(',', $orderSettings->notification_emails));
                            foreach ($emails as $email) {
                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    Mail::to($email)->send(new AdminRoomBookingNotificationEmail($roomBooking));
                                }
                            }
                        }
                    } catch (Exception $e) {
                        Log::error('Room booking admin email failed (weflexfy webhook): ' . $e->getMessage());
                    }
                }
            } elseif ($status === 'FAILED') {
                $roomBooking->update(['payment_status' => 'unpaid', 'status' => 'cancelled']);
            }
            return;
        }

        // 3. Check Venue Bookings
        $venueBooking = VenueBooking::where(function ($q) use ($requestToken) {
            if ($requestToken) $q->where('weflexfy_request_token', $requestToken);
        })->first();

        if ($venueBooking) {
            if ($status === 'SUCCESS') {
                if ($venueBooking->payment_status !== 'deposit_paid' && $venueBooking->payment_status !== 'fully_paid') {
                    $venueBooking->update([
                        'payment_status' => 'deposit_paid',
                        'status' => 'confirmed'
                    ]);

                    try {
                        Mail::to($venueBooking->customer_email)->send(new VenueBookingEmail($venueBooking));
                    } catch (Exception $e) {
                        Log::error('Venue booking customer email failed (weflexfy webhook): ' . $e->getMessage());
                    }

                    try {
                        $orderSettings = OrderSettings::first();
                        if ($orderSettings && $orderSettings->notification_emails) {
                            $emails = array_map('trim', explode(',', $orderSettings->notification_emails));
                            foreach ($emails as $email) {
                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    Mail::to($email)->send(new AdminVenueBookingNotificationEmail($venueBooking));
                                }
                            }
                        }
                    } catch (Exception $e) {
                        Log::error('Venue booking admin email failed (weflexfy webhook): ' . $e->getMessage());
                    }
                }
            } elseif ($status === 'FAILED') {
                $venueBooking->update(['payment_status' => 'unpaid', 'status' => 'cancelled']);
            }
            return;
        }
    }

    private function processWeFlexfyTransferWebhook(array $payload)
    {
        $requestToken = $payload['requestToken'] ?? null;
        $status = strtoupper($payload['status'] ?? '');
        $customPayload = $payload['payload'] ?? [];

        $type = $customPayload['type'] ?? null;
        $orderNo = $customPayload['order_no'] ?? null;
        $bookingId = $customPayload['booking_id'] ?? null;

        if ($type === 'food_order' || $orderNo) {
            $order = Order::where('order_no', $orderNo)->first();
            if (!$order && $requestToken) {
                $order = Order::where('weflexfy_request_token', $requestToken)->first();
            }
            if ($order && $status === 'SUCCESS' && $order->status_online_pay !== 'paid') {
                $order->status_online_pay = 'paid';
                $order->payment_method = 'WEFLEXFY';
                $order->save();

                try {
                    Mail::to($order->customer->email)->send(new OrderEmail(
                        $order->orderItems,
                        $order->customer->first_name ?? $order->customer->name,
                        $order->customer->email,
                        $order->order_no,
                        $order->delivery_fee,
                        $order->total_price,
                        config('site.email'),
                        RestaurantPhoneNumber::first()?->phone_number
                    ));
                } catch (Exception $e) {
                    Log::error('Order email failed (weflexfy transfer webhook): ' . $e->getMessage());
                }

                $this->sendWhatsAppNotification($order);
                $this->deductStockForOrder($order, 'WeFlexfy Transfer Webhook');
            }
            return;
        }

        if ($type === 'room' || ($bookingId && $type !== 'venue')) {
            $booking = RoomBooking::find($bookingId);
            if (!$booking && $requestToken) {
                $booking = RoomBooking::where('weflexfy_request_token', $requestToken)->first();
            }
            if ($booking && $status === 'SUCCESS' && $booking->payment_status !== 'deposit_paid') {
                $booking->update(['payment_status' => 'deposit_paid', 'status' => 'confirmed']);
                try {
                    Mail::to($booking->customer_email)->send(new RoomBookingConfirmation($booking));
                } catch (Exception $e) {
                    Log::error('Room email failed (weflexfy transfer webhook): ' . $e->getMessage());
                }
            }
            return;
        }

        if ($type === 'venue' || $bookingId) {
            $booking = VenueBooking::find($bookingId);
            if (!$booking && $requestToken) {
                $booking = VenueBooking::where('weflexfy_request_token', $requestToken)->first();
            }
            if ($booking && $status === 'SUCCESS' && $booking->payment_status !== 'deposit_paid') {
                $booking->update(['payment_status' => 'deposit_paid', 'status' => 'confirmed']);
                try {
                    Mail::to($booking->customer_email)->send(new VenueBookingEmail($booking));
                } catch (Exception $e) {
                    Log::error('Venue email failed (weflexfy transfer webhook): ' . $e->getMessage());
                }
            }
            return;
        }

        $this->processWeFlexfyPaymentWebhook($payload);
    }
}
