<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'capacity',
        'deposit_percentage'
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (!empty($this->image)) {
            if (str_starts_with($this->image, '/') || str_starts_with($this->image, 'http')) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }

        if ($this->relationLoaded('images') && $this->images->count() > 0) {
            $firstImg = $this->images->first()->image;
            if (!empty($firstImg)) {
                return asset('storage/' . $firstImg);
            }
        }

        return asset('assets/images/placeholder.jpg');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function features()
    {
        return $this->hasMany(RoomFeature::class);
    }
}
