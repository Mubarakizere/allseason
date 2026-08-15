@extends('layouts.admin')

@section('title', 'Payroll & Salary Management — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .py-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .py-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .py-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .py-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-payroll {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        background: #dc2626;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-add-payroll:hover {
        background: #b91c1c;
    }

    /* Metric Cards */
    .py-metric-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px 20px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .py-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .icon-paid { background: #f0fdf4; color: #16a34a; }
    .icon-pending { background: #fffbe6; color: #d97706; }
    .icon-staff { background: #eff6ff; color: #2563eb; }

    .py-metric-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 2px;
    }
    .py-metric-val {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        letter-spacing: -0.02em;
    }

    /* Card Container */
    .py-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .py-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .py-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 14px 20px 10px;
        font-size: 13px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 13px;
        outline: none;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #dc2626;
    }
    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
        border: none !important;
        margin: 0 !important;
    }
    table.dataTable<thead>th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb !important;
        border-top: none !important;
        color: #374151;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 18px !important;
    }
    table.dataTable<tbody>td {
        padding: 12px 18px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6 !important;
        border-top: none !important;
        color: #111827;
        font-size: 13px;
    }
    table.dataTable<tbody>tr:hover {
        background-color: #f9fafb !important;
    }
    .dataTables_info,
    .dataTables_paginate {
        padding: 12px 20px !important;
        font-size: 12.5px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #374151 !important;
        font-size: 12px !important;
        padding: 3px 9px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #111827 !important;
        color: #ffffff !important;
        border-color: #111827 !important;
        box-shadow: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#payroll-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search employee or payroll..."
            }
        });

        // Auto-calculate Net Pay in Add Modal
        function calcAddNetPay() {
            var base = parseFloat($('#addBaseSalary').val()) || 0;
            var bonus = parseFloat($('#addBonuses').val()) || 0;
            var ded = parseFloat($('#addDeductions').val()) || 0;
            var net = (base + bonus) - ded;
            $('#addNetPayDisplay').val(net.toFixed(2));
        }
        $('.calc-salary').on('input', calcAddNetPay);

        // Auto-calculate Net Pay in Edit Modal
        function calcEditNetPay() {
            var base = parseFloat($('#editBaseSalary').val()) || 0;
            var bonus = parseFloat($('#editBonuses').val()) || 0;
            var ded = parseFloat($('#editDeductions').val()) || 0;
            var net = (base + bonus) - ded;
            $('#editNetPayDisplay').val(net.toFixed(2));
        }
        $('.edit-calc-salary').on('input', calcEditNetPay);

        // Populate Edit Modal
        $(document).on('click', '.edit-payroll-btn', function() {
            var id = $(this).data('id');
            var actionUrl = "{{ route('admin.payroll.update', ':id') }}".replace(':id', id);
            $('#editPayrollForm').attr('action', actionUrl);

            $('#editEmployeeName').val($(this).data('employee_name'));
            $('#editEmployeeType').val($(this).data('employee_type'));
            $('#editMonth').val($(this).data('month'));
            $('#editBaseSalary').val($(this).data('base_salary'));
            $('#editBonuses').val($(this).data('bonuses'));
            $('#editDeductions').val($(this).data('deductions'));
            $('#editStatus').val($(this).data('status'));
            $('#editPaymentMethod').val($(this).data('payment_method'));
            $('#editPaymentDate').val($(this).data('payment_date'));
            $('#editNotes').val($(this).data('notes'));
            calcEditNetPay();
        });

        // Populate Delete Modal
        $(document).on('click', '.delete-payroll-btn', function() {
            var id = $(this).data('id');
            var actionUrl = "{{ route('admin.payroll.destroy', ':id') }}".replace(':id', id);
            $('#deletePayrollForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper py-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="py-header">
        <div class="py-title-group">
            <h1>Payroll & Salary Management</h1>
            <p>Manage staff salaries, bonuses, deductions, and print payslips.</p>
        </div>
        <button class="btn-add-payroll" data-bs-toggle="modal" data-bs-target="#addPayrollModal">
            <i class="fas fa-plus me-1"></i> Add Payroll Record
        </button>
    </div>

    {{-- 3 Summary Metric Cards --}}
    <div class="row g-3 mb-4">
        <!-- Total Paid -->
        <div class="col-md-4">
            <div class="py-metric-card">
                <div class="py-metric-icon icon-paid">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <div class="py-metric-title">Total Paid</div>
                    <div class="py-metric-val text-success">{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($totalPaid, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Pending Salaries -->
        <div class="col-md-4">
            <div class="py-metric-card">
                <div class="py-metric-icon icon-pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="py-metric-title">Pending Salaries</div>
                    <div class="py-metric-val text-warning">{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($totalPending, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Staff Records -->
        <div class="col-md-4">
            <div class="py-metric-card">
                <div class="py-metric-icon icon-staff">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="py-metric-title">Staff Records</div>
                    <div class="py-metric-val">{{ number_format($totalStaff) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payroll Table Card --}}
    <div class="py-card">
        <div class="py-card-header">
            <h3 class="py-card-title">Payroll Records</h3>

            <form method="GET" action="{{ route('admin.payroll.index') }}" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="font-size: 12.5px;">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="payroll-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Role / Position</th>
                            <th>Month</th>
                            <th>Base Pay</th>
                            <th>Bonus</th>
                            <th>Deduction</th>
                            <th>Net Pay</th>
                            <th>Status</th>
                            <th style="min-width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $payroll->employee_name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11px;">{{ $payroll->employee_type }}</span>
                                </td>
                                <td>{{ $payroll->month }}</td>
                                <td>{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($payroll->base_salary, 2) }}</td>
                                <td class="text-success">+{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($payroll->bonuses, 2) }}</td>
                                <td class="text-danger">-{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($payroll->deductions, 2) }}</td>
                                <td>
                                    <strong class="text-dark">{!! $site_settings->currency_symbol ?? 'RWF ' !!}{{ number_format($payroll->net_salary, 2) }}</strong>
                                </td>
                                <td>
                                    @if($payroll->status == 'paid')
                                        <span class="badge bg-success text-white fw-semibold" style="font-size: 11px;">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 11px;">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <!-- Print Payslip -->
                                        <a href="{{ route('admin.payroll.payslip', $payroll->id) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-dark" 
                                           title="Print Payslip">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-outline-secondary edit-payroll-btn"
                                            data-id="{{ $payroll->id }}"
                                            data-employee_name="{{ $payroll->employee_name }}"
                                            data-employee_type="{{ $payroll->employee_type }}"
                                            data-month="{{ $payroll->month }}"
                                            data-base_salary="{{ $payroll->base_salary }}"
                                            data-bonuses="{{ $payroll->bonuses }}"
                                            data-deductions="{{ $payroll->deductions }}"
                                            data-status="{{ $payroll->status }}"
                                            data-payment_method="{{ $payroll->payment_method }}"
                                            data-payment_date="{{ $payroll->payment_date ? $payroll->payment_date->format('Y-m-d') : '' }}"
                                            data-notes="{{ $payroll->notes }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPayrollModal"
                                            title="Edit Record">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-outline-danger delete-payroll-btn"
                                            data-id="{{ $payroll->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deletePayrollModal"
                                            title="Delete Record">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No payroll records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Payroll Modal --}}
    <div class="modal fade" id="addPayrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.payroll.store') }}" method="POST" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add Payroll Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Employee Name *</label>
                                <input type="text" name="employee_name" class="form-control" required placeholder="e.g. John Doe" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Position / Role *</label>
                                <select name="employee_type" class="form-select" required style="font-size: 13px;">
                                    <option value="Waiter">Waiter</option>
                                    <option value="Chef">Chef / Kitchen Staff</option>
                                    <option value="Bar Tender">Bar Tender</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Driver">Driver / Delivery</option>
                                    <option value="Cleaner">Cleaner / Housekeeping</option>
                                    <option value="Security">Security Guard</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payroll Month *</label>
                                <input type="text" name="month" class="form-control" required value="{{ date('F Y') }}" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Method *</label>
                                <select name="payment_method" class="form-select" required style="font-size: 13px;">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Mobile Money">Mobile Money (MOMO)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Base Salary *</label>
                                <input type="number" step="0.01" name="base_salary" id="addBaseSalary" class="form-control calc-salary" required placeholder="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Bonuses / Allowances</label>
                                <input type="number" step="0.01" name="bonuses" id="addBonuses" class="form-control calc-salary" value="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Deductions</label>
                                <input type="number" step="0.01" name="deductions" id="addDeductions" class="form-control calc-salary" value="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Calculated Net Pay</label>
                                <input type="text" id="addNetPayDisplay" class="form-control font-weight-bold text-success bg-light" readonly value="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Status *</label>
                                <select name="status" class="form-select" required style="font-size: 13px;">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Notes / Description</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional payment notes..." style="font-size: 13px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Payroll Modal --}}
    <div class="modal fade" id="editPayrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="editPayrollForm" method="POST" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Edit Payroll Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Employee Name *</label>
                                <input type="text" name="employee_name" id="editEmployeeName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Position / Role *</label>
                                <select name="employee_type" id="editEmployeeType" class="form-select" required style="font-size: 13px;">
                                    <option value="Waiter">Waiter</option>
                                    <option value="Chef">Chef / Kitchen Staff</option>
                                    <option value="Bar Tender">Bar Tender</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Driver">Driver / Delivery</option>
                                    <option value="Cleaner">Cleaner / Housekeeping</option>
                                    <option value="Security">Security Guard</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payroll Month *</label>
                                <input type="text" name="month" id="editMonth" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Method *</label>
                                <select name="payment_method" id="editPaymentMethod" class="form-select" required style="font-size: 13px;">
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Mobile Money">Mobile Money (MOMO)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Base Salary *</label>
                                <input type="number" step="0.01" name="base_salary" id="editBaseSalary" class="form-control edit-calc-salary" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Bonuses / Allowances</label>
                                <input type="number" step="0.01" name="bonuses" id="editBonuses" class="form-control edit-calc-salary" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Deductions</label>
                                <input type="number" step="0.01" name="deductions" id="editDeductions" class="form-control edit-calc-salary" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Calculated Net Pay</label>
                                <input type="text" id="editNetPayDisplay" class="form-control font-weight-bold text-success bg-light" readonly style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Status *</label>
                                <select name="status" id="editStatus" class="form-select" required style="font-size: 13px;">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Date</label>
                                <input type="date" name="payment_date" id="editPaymentDate" class="form-control" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Notes / Description</label>
                                <textarea name="notes" id="editNotes" class="form-control" rows="2" style="font-size: 13px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deletePayrollModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deletePayrollForm" method="POST" style="width: 100%;">
                @csrf
                @method('DELETE')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete this payroll record?
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
