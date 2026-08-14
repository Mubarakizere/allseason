<?php

namespace App\Http\Controllers\Admin;

use App\Models\StockHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class StockHistoryController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }

    public function index()
    {
        $histories = StockHistory::with(['stockItem', 'user'])->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.stock.history', compact('histories'));
    }
}
