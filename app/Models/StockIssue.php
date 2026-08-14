<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockIssue extends Model
{
    protected $fillable = [
        'date', 'department', 'note', 'created_by'
    ];

    public function items()
    {
        return $this->hasMany(StockIssueItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
