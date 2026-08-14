<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockPurchase extends Model
{
    protected $fillable = [
        'supplier_id', 'reference_no', 'date', 'status', 'total_amount', 'note', 'created_by'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(StockPurchaseItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
