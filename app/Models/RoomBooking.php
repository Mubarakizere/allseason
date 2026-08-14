<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    protected $fillable = [
        'room_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'check_in_date',
        'check_out_date',
        'total_price',
        'deposit_amount',
        'payment_status',
        'status',
        'stripe_session_id',
        'weflexfy_request_token',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
