<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    protected $fillable = [
        'stock_category_id', 'name', 'sku', 'unit', 'quantity', 'alert_quantity', 'cost_price', 'description'
    ];

    public function category()
    {
        return $this->belongsTo(StockCategory::class, 'stock_category_id');
    }

    public function stockCategory()
    {
        return $this->belongsTo(StockCategory::class, 'stock_category_id');
    }

    public function histories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
