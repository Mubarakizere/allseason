<?php

namespace App\Mail;

use App\Models\RoomBooking;
use App\Models\SiteSetting;
use App\Models\RestaurantPhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RoomBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $site_settings;
    public $companyPhone;

    public function __construct(RoomBooking $booking)
    {
        $this->booking = $booking;
        $this->site_settings = SiteSetting::latest()->first();
        $this->companyPhone = RestaurantPhoneNumber::first()?->phone_number;
    }

    public function build()
    {
        return $this->view('emails.room_booking_confirmation')
                    ->subject('Your Room Booking Confirmation - ' . config('site.name', 'All The Season Garden'))
                    ->with([
                        'booking' => $this->booking,
                        'site_settings' => $this->site_settings,
                        'companyPhone' => $this->companyPhone,
                    ]);
    }
}
