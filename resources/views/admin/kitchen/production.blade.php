@extends('layouts.admin')

@section('title', 'Batch Preparation Log — All The Season Garden')

@push('styles')
<style>
    .prod-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .prod-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .prod-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .prod-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-log-batch {
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
    .btn-log-batch:hover {
        background: #b91c1c;
    }

    /* Search Bar */
    .prod-search-bar {
        position: relative;
        margin-bottom: 20px;
        max-width: 400px;
    }
    .prod-search-bar i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }
    .prod-search-input {
        width: 100%;
        height: 40px;
        padding: 0 16px 0 38px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 13px;
        color: #111827;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .prod-search-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
    }

    /* Card & Table */
    .prod-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .prod-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .prod-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .prod-table {
        width: 100%;
        border-collapse: collapse;
    }
    .prod-table th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 18px;
    }
    .prod-table td {
        padding: 12px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
        color: #111827;
        font-size: 13px;
    }
    .prod-table tr:last-child td {
        border-bottom: none;
    }
    .prod-table tr:hover {
        background-color: #f9fafb;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#prod-search-input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#prod-table-body tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper prod-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="prod-header">
        <div class="prod-title-group">
            <h1>Batch Food Preparation Log</h1>
            <p>Track kitchen batch preparations and auto-deduct raw material ingredients from inventory.</p>
        </div>
        <button class="btn-log-batch" data-bs-toggle="modal" data-bs-target="#addProductionModal">
            <i class="fas fa-plus me-1"></i> Log Batch Preparation
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="prod-search-bar">
        <i class="fas fa-search"></i>
        <input type="text" id="prod-search-input" class="prod-search-input" placeholder="Search batch records, dish, or chef...">
    </div>

    {{-- Production Card & Table --}}
    <div class="prod-card">
        <div class="prod-card-header">
            <h3 class="prod-card-title">Production Records</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="prod-table">
                    <thead>
                        <tr>
                            <th>Prep ID</th>
                            <th>Dish Prepared</th>
                            <th>Quantity Prepared</th>
                            <th>Prepared By</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody id="prod-table-body">
                        @forelse ($preparations as $prep)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size: 11px;">#PREP-{{ $prep->id }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $prep->item_name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-success text-white fw-semibold" style="font-size: 11.5px; padding: 4px 10px;">
                                        {{ number_format($prep->quantity_prepared, 0) }} Portions
                                    </span>
                                </td>
                                <td>
                                    <span class="text-secondary"><i class="fas fa-user-circle me-1 text-muted" style="font-size:11px;"></i> {{ $prep->prepared_by }}</span>
                                </td>
                                <td>
                                    <div class="text-dark">{{ $prep->created_at->format('g:i A — d M, Y') }}</div>
                                    <small class="text-muted" style="font-size: 11px;">{{ $prep->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-success border fw-semibold" style="font-size: 11px;">
                                        <i class="fas fa-check-circle me-1"></i> Deducted
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size: 12px;">{{ $prep->notes ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No batch food preparations logged yet. Click "Log Batch Preparation" to create one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($preparations->hasPages())
            <div class="p-3 border-top">
                {{ $preparations->links() }}
            </div>
        @endif
    </div>

    {{-- Log Batch Modal --}}
    <div class="modal fade" id="addProductionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.kitchen.production.store') }}" method="POST" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Log Batch Food Preparation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Select Prepared Food Item *</label>
                            <select name="menu_id" class="form-select" required style="font-size: 13px;">
                                <option value="">-- Select Food Dish --</option>
                                @foreach ($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Batch Quantity Prepared (Portions) *</label>
                            <input type="number" step="1" name="quantity_prepared" class="form-control" required value="1" min="1" style="font-size: 13px;">
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">Raw ingredients will auto-deduct from stock based on this recipe quantity.</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Prepared By (Chef / Staff)</label>
                            <input type="text" name="prepared_by" class="form-control" value="Head Chef" placeholder="e.g. Head Chef John" style="font-size: 13px;">
                        </div>

                        <div class="mb-2">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Preparation Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Morning batch preparation for lunch buffet" style="font-size: 13px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Log & Deduct Ingredients</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
