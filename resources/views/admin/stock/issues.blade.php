@extends('layouts.admin')

@section('title', 'Stock Issues — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .iss-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .iss-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .iss-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .iss-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-iss {
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
    .btn-add-iss:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .iss-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .iss-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .iss-card-title {
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
        $('#stock-issues-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            order: [],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search stock issues..."
            }
        });

        let itemIndex = 1;
        $('#addItemBtn').on('click', function() {
            let row = `
                <div class="row g-2 mt-2 item-row align-items-center">
                    <div class="col-md-7">
                        <select name="items[${itemIndex}][stock_item_id]" class="form-select" required style="font-size: 13px;">
                            <option value="">Select Item...</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} (In Stock: {{ $item->quantity }} {{ $item->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" required style="font-size: 13px;">
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-item-btn"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            `;
            $('#itemsContainer').append(row);
            itemIndex++;
        });

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('.item-row').remove();
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let actionUrl = "{{ route('admin.stock-issues.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper iss-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="iss-header">
        <div class="iss-title-group">
            <h1>Stock Issues & Requisitions (OUT)</h1>
            <p>Track raw materials dispatched from central store to Kitchen, Bar, or other departments.</p>
        </div>
        <button class="btn-add-iss" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Issue Stock
        </button>
    </div>

    {{-- Issues Card --}}
    <div class="iss-card">
        <div class="iss-card-header">
            <h3 class="iss-card-title">All Stock Requisitions ({{ $issues->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="stock-issues-table">
                    <thead>
                        <tr>
                            <th>Issue Date</th>
                            <th>Department</th>
                            <th>Requisition Notes</th>
                            <th>Issued By</th>
                            <th style="min-width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($issues as $issue)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $issue->date }}</div>
                                </td>
                                <td>
                                    @if($issue->department == 'Kitchen')
                                        <span class="badge bg-danger text-white fw-semibold" style="font-size: 11px;"><i class="fas fa-utensils me-1"></i> Kitchen (Chef)</span>
                                    @elseif($issue->department == 'Bar')
                                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 11px;"><i class="fas fa-glass-martini-alt me-1"></i> Bar</span>
                                    @else
                                        <span class="badge bg-secondary text-white fw-semibold" style="font-size: 11px;">{{ $issue->department }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($issue->note)
                                        <span class="text-secondary" style="font-size: 12.5px;">{{ $issue->note }}</span>
                                    @else
                                        <span class="text-muted small">No additional notes</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-dark fw-semibold"><i class="fas fa-user-circle me-1 text-muted"></i> {{ $issue->createdBy->first_name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" 
                                        data-id="{{ $issue->id }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal"
                                        title="Delete Issue Record">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No stock issues recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="POST" action="{{ route('admin.stock-issues.store') }}" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Issue Stock to Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Issue Date *</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Destination Department *</label>
                                <select name="department" class="form-select" required style="font-size: 13px;">
                                    <option value="Kitchen">Kitchen (Chef)</option>
                                    <option value="Bar">Bar</option>
                                    <option value="Other">Other Department</option>
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Requisition Notes</label>
                                <input type="text" name="note" class="form-control" placeholder="e.g. Daily shift kitchen prep stock" style="font-size: 13px;">
                            </div>
                        </div>

                        <hr class="my-3">
                        
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold mb-0" style="font-size: 13px;">Stock Items to Issue</h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="addItemBtn" style="font-size: 11.5px;">
                                <i class="fas fa-plus me-1"></i> Add Item Row
                            </button>
                        </div>

                        <div id="itemsContainer">
                            <div class="row g-2 item-row align-items-center mb-2">
                                <div class="col-md-7">
                                    <select name="items[0][stock_item_id]" class="form-select" required style="font-size: 13px;">
                                        <option value="">Select Item...</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} (In Stock: {{ $item->quantity }} {{ $item->unit }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" name="items[0][quantity]" class="form-control" placeholder="Qty" required style="font-size: 13px;">
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Issue Stock</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="deleteForm" style="width: 100%;">
                @csrf
                @method('DELETE')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete this issue record?
                        <div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle me-1"></i> This will REVERT the stock back to the main store!</div>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete & Revert Stock</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
