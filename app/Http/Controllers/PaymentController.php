<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Order;
use App\Mail\OrderEmail;
use App\Mail\AdminOrderNotificationEmail;
use App\Models\Customer;
use App\Models\OrderSettings;
use Stripe\PaymentIntent;
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
    protected $stripeSecret;
    protected $paystackSecret;

    public function __construct()
    {
        $this->shareMainSiteViewData();

        $this->provider      = config('payments.provider');
        $this->stripeSecret  = config('payments.stripe.secret');
        $this->paystackSecret= config('payments.paystack.secret');  

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
        // still run your “wizard” checks if you want
        $this->runAllChecks();

        return match ($this->provider) {
            'stripe'   => $this->handleStripeSuccess($request),
            'paystack' => $this->handlePaystackSuccess($request),
            'weflexfy' => $this->handleWeFlexfySuccess($request),
            default    => redirect()->route('menu')->withErrors('Unsupported payment provider.'),
        };
    }
    






    private function handleStripeSuccess(Request $request)
    {
        Stripe::setApiKey($this->stripeSecret);

        $session_id = $request->query('session_id');

        if (!$session_id) {
            return redirect()->route('menu')->withErrors('Session ID not found!');
        }

        try {
            if (str_starts_with($session_id, 'mock_session_')) {
                $order_no = $request->query('order_no');
            } else {
                $checkout_session = \Stripe\Checkout\Session::retrieve($session_id);
                $order_no = $checkout_session->metadata->order_no ?? null;
            }

            if (!$order_no) {
                return redirect()->route('menu')->withErrors('No order reference found.');
            }

            $order = Order::with(['orderItems', 'customer'])
                ->where('order_no', $order_no)
                ->first();

            if (!$order) {
                throw new NotFoundHttpException();
            }

            // Save Stripe session id on the order
            $order->session_id = $session_id;

            if ($order->status_online_pay === 'unpaid') {
                $order->status_online_pay = 'paid';
            }
            $order->save();

            // Send email
            try {
                Mail::to($order->customer->email)->send(new OrderEmail(
                    $order->orderItems,
                    $order->customer->first_name,
                    $order->customer->email,
                    $order->order_no,
                    $order->delivery_fee,
                    $order->total_price,
                    config('site.email'),
                    RestaurantPhoneNumber::first()?->phone_number
                ));
            } catch (\Exception $e) {
                Log::error('Order email failed to send: '.$e->getMessage());
            }

            // Send admin notification
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
                Log::error('Admin order notification email failed to send: '.$e->getMessage());
            }

            // WhatsApp
            $this->sendWhatsAppNotification($order);

            // Deduct Stock
            $this->deductStockForOrder($order, 'Online Order');

            // Clear session
            $this->clearOrderSession();

            return view('main-site.payment-success', compact('order'));

        } catch (\Exception $e) {
            $error_msg = $e->getMessage();
            return redirect()->route('menu')->withErrors($error_msg);
        }
    }



    private function handlePaystackSuccess(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('menu')
                ->withErrors('Missing Paystack reference.');
        }

 
        $response = Http::withToken($this->paystackSecret)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        if (! $response->successful()) {
            return redirect()->route('menu')
                ->withErrors('Unable to verify payment with Paystack. Please try again.');
        }

        $data = $response->json();

        // Paystack returns: status => true/false, data.status => 'success'|'failed' etc.
        if (empty($data['status']) || empty($data['data'])) {
            return redirect()->route('menu')
                ->withErrors($data['message'] ?? 'Payment verification failed.');
        }

        $tx = $data['data'];

        if ($tx['status'] !== 'success') {
            return redirect()->route('menu')
                ->withErrors('Payment was not successful. Current status: '.$tx['status']);
        }

        // Get order_no from metadata (we set it when initializing)
        $order_no = $tx['metadata']['order_no'] ?? null;
        if (!$order_no) {
            return redirect()->route('menu')
                ->withErrors('Order reference missing from Paystack metadata.');
        }

        $order = Order::with(['orderItems', 'customer'])
            ->where('order_no', $order_no)
            ->first();

        if (!$order) {
            return redirect()->route('menu')
                ->withErrors('Order not found for this transaction.');
        }

        // Mark order as paid if still unpaid
        if ($order->status_online_pay === 'unpaid') {
            $order->status_online_pay = 'paid';
            $order->session_id        = $reference; // store Paystack ref in session_id if you like
            $order->payment_method    = 'PAYSTACK';
            $order->save();

            // Send email
            try {
                Mail::to($order->customer->email)->send(new OrderEmail(
                    $order->orderItems,
                    $order->customer->first_name,
                    $order->customer->email,
                    $order->order_no,
                    $order->delivery_fee,
                    $order->total_price,
                    config('site.email'),
                    RestaurantPhoneNumber::first()?->phone_number
                ));
            } catch (\Exception $e) {
                Log::error('Order email failed to send (paystack): '.$e->getMessage());
            }

            // Send admin notification
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
                Log::error('Admin order notification email failed to send (paystack): '.$e->getMessage());
            }

            // WhatsApp
            $this->sendWhatsAppNotification($order);

            // Deduct Stock
            $this->deductStockForOrder($order, 'Online Order');

            // Clear session
            $this->clearOrderSession();
        }

        return view('main-site.payment-success', compact('order'));
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


    public function handleStripeWebhook(Request $request)
    {
        $endpoint_secret =  config('services.stripe.webhookkey');
    
        // Retrieve the raw payload
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;
    
    
        try {
            // Verify the event signature
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
    
            // Handle specific event types
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;  
     
                $order = Order::with(['orderItems', 'customer'])->where('session_id', $session->id)->first();
    
    
                if ($order->status_online_pay === 'unpaid') {
                    $order->status_online_pay = 'paid';
                    $order->save();
    
                    // Send the email
                    try {
                        Mail::to($order->customer->email)->send(new OrderEmail(
                            $order->orderItems,
                            $order->customer->name,
                            $order->customer->email,
                            $order->order_no,
                            $order->delivery_fee,
                            $order->total_price,
                            config('site.email'),
                            RestaurantPhoneNumber::first() ? RestaurantPhoneNumber::first()->phone_number : null
                        ));
                    } catch (Exception $e) {
                        Log::error('Order email failed to send: ' . $e->getMessage());
                    }
                    
                    // Send admin notification
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
                        Log::error('Admin order notification email failed to send (webhook): '.$e->getMessage());
                    }

                    // send whatsapp message
                    $this->sendWhatsAppNotification($order);                       
                    
                    // Deduct Stock
                    $this->deductStockForOrder($order, 'Online Webhook Order');
                }
     
            }
    
            return response('Webhook handled', 200);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (Exception $e) {
            // General error
            Log::error('Webhook error: ' . $e->getMessage());
            return response('Webhook error', 500);
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
            if ($requestToken) $q->where('weflexfy_request_token', $requestToken)->orWhere('stripe_session_id', $requestToken);
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
            if ($requestToken) $q->where('weflexfy_request_token', $requestToken)->orWhere('stripe_session_id', $requestToken);
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
