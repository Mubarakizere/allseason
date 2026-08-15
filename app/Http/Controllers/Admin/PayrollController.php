<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\SiteSetting;
use App\Models\Waiter;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::query();

        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payrolls = $query->orderBy('id', 'desc')->get();
        $site_settings = SiteSetting::first();
        $waiters = Waiter::all();

        // Calculate Summary Statistics
        $totalPaid = $payrolls->where('status', 'paid')->sum('net_salary');
        $totalPending = $payrolls->where('status', 'pending')->sum('net_salary');
        $totalStaff = $payrolls->count();

        return view('admin.payroll.index', compact(
            'payrolls',
            'site_settings',
            'waiters',
            'totalPaid',
            'totalPending',
            'totalStaff'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_type' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,pending',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $base = $validated['base_salary'] ?? 0;
        $bonuses = $validated['bonuses'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $validated['net_salary'] = ($base + $bonuses) - $deductions;

        Payroll::create($validated);

        return back()->with('success', 'Payroll record created successfully!');
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_type' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'base_salary' => 'required|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:paid,pending',
            'payment_method' => 'required|string',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $base = $validated['base_salary'] ?? 0;
        $bonuses = $validated['bonuses'] ?? 0;
        $deductions = $validated['deductions'] ?? 0;
        $validated['net_salary'] = ($base + $bonuses) - $deductions;

        $payroll->update($validated);

        return back()->with('success', 'Payroll record updated successfully!');
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        return back()->with('success', 'Payroll record deleted successfully!');
    }

    public function payslip($id)
    {
        $payroll = Payroll::findOrFail($id);
        $site_settings = SiteSetting::first();

        return view('admin.payroll.payslip', compact('payroll', 'site_settings'));
    }
}
