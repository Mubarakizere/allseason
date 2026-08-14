<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantTable extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'restaurant_table_id');
    }
}
