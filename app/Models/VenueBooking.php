<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'venue_package_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'booking_date',
        'total_price',
        'deposit_amount',
        'payment_status',
        'status',
        'stripe_session_id',
        'weflexfy_request_token',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function package()
    {
        return $this->belongsTo(VenuePackage::class, 'venue_package_id');
    }
}
