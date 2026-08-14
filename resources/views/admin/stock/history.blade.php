@extends('layouts.admin')

@push('styles')
    <link rel="stylesheet" href="/admin_resources/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="/admin_resources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/admin_resources/css/vertical-layout-light/style.css">
@endpush

@push('scripts')
<script src="/admin_resources/vendors/js/vendor.bundle.base.js"></script>
<script src="/admin_resources/js/off-canvas.js"></script>
<script src="/admin_resources/js/hoverable-collapse.js"></script>
<script src="/admin_resources/js/template.js"></script>
<script src="/admin_resources/js/settings.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@section('title', 'Admin - Stock - History')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stock Movement Ledger</span>
            </div>
            <div class="card-body">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Quantity Change</th>
                            <th>Running Balance</th>
                            <th>Reference</th>
                            <th>Notes</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                        <tr>
                            <td>{{ $history->date }}</td>
                            <td>{{ $history->stockItem->name ?? 'N/A' }}</td>
                            <td>
                                @if($history->type == 'in')
                                    <span class="badge badge-success">IN</span>
                                @elseif($history->type == 'out')
                                    <span class="badge badge-danger">OUT</span>
                                @elseif($history->type == 'sale')
                                    <span class="badge badge-primary">SALE</span>
                                @elseif($history->type == 'issue')
                                    <span class="badge badge-warning">ISSUE</span>
                                @else
                                    <span class="badge badge-secondary">{{ strtoupper($history->type) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $history->type == 'in' ? '+' : '-' }}{{ $history->quantity }}
                            </td>
                            <td>{{ $history->balance }}</td>
                            <td>{{ $history->reference }}</td>
                            <td>{{ $history->note }}</td>
                            <td>{{ $history->user->first_name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No stock history recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    
    </div>
    @include('partials.admin.footer')
</div>
@endsection
