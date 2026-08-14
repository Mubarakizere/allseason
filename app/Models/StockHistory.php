<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'stock_item_id', 'type', 'quantity', 'balance', 'reference', 'date', 'note', 'user_id'
    ];

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
