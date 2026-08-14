<?php

namespace App\Http\Controllers\Admin;

use App\Models\Waiter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\AdminViewSharedDataTrait;

class WaiterController extends Controller
{
    use AdminViewSharedDataTrait;

    public function __construct()
    {
        $this->shareAdminViewData();
    }
    
    public function index()
    {
        $waiters = Waiter::all();
        return view('admin.waiters', compact('waiters'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Waiter::create([
            'name' => $request->name,
            'is_active' => true,
        ]);
        return redirect()->back()->with('success', 'Waiter created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $waiter = Waiter::findOrFail($id);
        $waiter->update([
            'name' => $request->name,
            'is_active' => true,
        ]);
        return redirect()->back()->with('success', 'Waiter updated successfully.');
    }
    
    public function destroy($id)
    {
        $waiter = Waiter::findOrFail($id);
        $waiter->delete();
        return redirect()->back()->with('success', 'Waiter deleted successfully.');
    }
}
