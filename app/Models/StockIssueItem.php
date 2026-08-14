<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIssueItem extends Model
{
    protected $fillable = [
        'stock_issue_id', 'stock_item_id', 'quantity'
    ];

    public function issue()
    {
        return $this->belongsTo(StockIssue::class, 'stock_issue_id');
    }

    public function stockItem()
    {
        return $this->belongsTo(StockItem::class);
    }
}
