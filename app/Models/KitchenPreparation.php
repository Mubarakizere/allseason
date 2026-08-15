<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenPreparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'item_name',
        'quantity_prepared',
        'prepared_by',
        'status',
        'notes',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
