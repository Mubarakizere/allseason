<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPurchaseItem extends Model
{
    protected $fillable = [
        'stock_purchase_id', 'stock_item_id', 'quantity', 'unit_cost', 'subtotal'
    ];

    public function purchase()
    {
        return $this->belongsTo(StockPurchase::class, 'stock_purchase_id');
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
