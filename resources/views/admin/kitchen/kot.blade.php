@extends('layouts.admin')

@section('title', 'Live KOT Display — All The Season Garden')

@push('styles')
<style>
    .kot-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .kot-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .kot-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .kot-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .kot-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        background: #fef2f2;
        color: #dc2626;
        font-size: 12.5px;
        font-weight: 600;
        border: 1px solid #fee2e2;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #dc2626;
        box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
        animation: pulse 1.6s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(220, 38, 38, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    /* KOT Grid */
    .kot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 20px;
    }

    /* Ticket Card */
    .kot-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-top: 4px solid #dc2626;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .kot-card:hover {
        border-color: #d1d5db;
        border-top-color: #dc2626;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }

    /* Card Header */
    .kot-card-header {
        padding: 16px 18px 12px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .kot-ticket-num {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 2px;
    }
    .kot-time {
        font-size: 12px;
        color: #6b7280;
    }
    .kot-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    /* Card Body */
    .kot-card-body {
        padding: 16px 18px;
        flex: 1;
    }
    .kot-waiter-info {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .kot-items-header {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #9ca3af;
        margin-bottom: 10px;
    }
    
    /* Items List */
    .kot-items-list {
        list-style: none;
        padding: 0;
        margin: 0 0 14px 0;
    }
    .kot-item-row {
        padding: 8px 0;
        border-bottom: 1px solid #f9fafb;
        font-size: 13.5px;
        color: #111827;
    }
    .kot-item-row:last-child {
        border-bottom: none;
    }
    .kot-qty {
        display: inline-block;
        min-width: 26px;
        padding: 2px 6px;
        background: #111827;
        color: #ffffff;
        font-weight: 700;
        font-size: 12px;
        border-radius: 4px;
        text-align: center;
        margin-right: 8px;
    }
    .kot-item-note {
        font-size: 12px;
        color: #dc2626;
        margin-top: 3px;
        padding-left: 34px;
        font-weight: 500;
    }
    .kot-order-note {
        background: #fffbe6;
        border: 1px solid #ffe58f;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12px;
        color: #855900;
        margin-top: 10px;
    }

    /* Card Footer Actions */
    .kot-card-footer {
        padding: 12px 18px 16px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        gap: 8px;
    }
    .kot-btn-print {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 6px;
        background: #ffffff;
        color: #374151;
        font-size: 12.5px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .kot-btn-print:hover {
        background: #f9fafb;
    }
    .kot-btn-ready {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 6px;
        background: #16a34a;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .kot-btn-ready:hover {
        background: #15803d;
    }

    /* Empty State */
    .kot-empty {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 60px 20px;
        text-align: center;
    }
    .kot-empty-icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #f0fdf4;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 16px;
    }
    .kot-empty h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px;
    }
    .kot-empty p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh KOT screen every 12 seconds
    setInterval(function() {
        fetch("{{ route('admin.kitchen.kot') }}", {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        }).catch(err => console.log(err));
    }, 12000);
</script>
@endpush

@section('content')
<div class="content-wrapper kot-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="kot-header">
        <div class="kot-title-group">
            <h1>Live KOT Display</h1>
            <p>Real-time kitchen order tickets display for chefs and staff.</p>
        </div>
        <div class="kot-status-badge">
            <span class="pulse-dot"></span>
            <span>Live Auto-Sync Active ({{ $orders->count() }} {{ $orders->count() == 1 ? 'Ticket' : 'Tickets' }})</span>
        </div>
    </div>

    {{-- Tickets Grid --}}
    <div class="kot-grid">
        @forelse ($orders as $order)
            <div class="kot-card">
                
                {{-- Header --}}
                <div class="kot-card-header">
                    <div>
                        <h3 class="kot-ticket-num">Ticket #{{ $order->order_no }}</h3>
                        <div class="kot-time">
                            <i class="far fa-clock me-1"></i> {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div>
                        @if($order->order_type == 'instore')
                            <span class="kot-tag"><i class="fas fa-utensils text-muted me-1"></i> {{ $order->restaurantTable->name ?? 'Walk-in' }}</span>
                        @else
                            <span class="kot-tag"><i class="fas fa-motorcycle text-muted me-1"></i> {{ ucfirst($order->order_type) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Body --}}
                <div class="kot-card-body">
                    @if($order->waiter)
                        <div class="kot-waiter-info">
                            <i class="fas fa-user-circle me-1"></i> Staff: <strong>{{ $order->waiter->name }}</strong>
                        </div>
                    @endif

                    <div class="kot-items-header">Items to Prepare:</div>
                    
                    <ul class="kot-items-list">
                        @foreach ($order->orderItems as $item)
                            <li class="kot-item-row">
                                <div>
                                    <span class="kot-qty">{{ $item->quantity }}x</span>
                                    <span class="fw-semibold">{{ $item->menu_name }}</span>
                                </div>
                                @if($item->item_note)
                                    <div class="kot-item-note">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $item->item_note }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    @if($order->additional_info)
                        <div class="kot-order-note">
                            <strong>Note:</strong> {{ $order->additional_info }}
                        </div>
                    @endif
                </div>

                {{-- Footer Actions --}}
                <div class="kot-card-footer">
                    <button type="button" 
                            onclick="window.open('{{ route('admin.orders.receipt', $order->id) }}?kitchen=1', '_blank', 'width=400,height=600')" 
                            class="kot-btn-print">
                        <i class="fas fa-print"></i> Print KOT
                    </button>
                    
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="flex: 1;">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="kot-btn-ready w-100">
                            <i class="fas fa-check"></i> Prepared
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-12" style="grid-column: 1 / -1;">
                <div class="kot-empty">
                    <div class="kot-empty-icon"><i class="fas fa-check"></i></div>
                    <h3>Kitchen is All Caught Up</h3>
                    <p>New orders placed in POS or online will automatically appear on this screen.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
