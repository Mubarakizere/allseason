<?php

namespace App\Http\Controllers\Admin;

use App\Models\RestaurantTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class RestaurantTableController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }
    
    public function index()
    {
        $tables = RestaurantTable::all();
        return view('admin.restaurant-tables', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        RestaurantTable::create([
            'name' => $request->name,
            'is_active' => true,
        ]);
        return redirect()->back()->with('success', 'Table created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $table = RestaurantTable::findOrFail($id);
        $table->update([
            'name' => $request->name,
            'is_active' => true,
        ]);
        return redirect()->back()->with('success', 'Table updated successfully.');
    }
    
    public function destroy($id)
    {
        $table = RestaurantTable::findOrFail($id);
        $table->delete();
        return redirect()->back()->with('success', 'Table deleted successfully.');
    }
}
