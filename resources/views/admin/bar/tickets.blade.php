@extends('layouts.admin')

@section('title', 'Live Bar Dispense Tickets — All The Season Garden')

@push('styles')
<style>
    .bar-ticket-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .bar-ticket-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .bar-ticket-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bar-ticket-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .bar-ticket-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        background: #fffbe6;
        color: #d97706;
        font-size: 12.5px;
        font-weight: 600;
        border: 1px solid #fef3c7;
    }
    .pulse-dot-bar {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #d97706;
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7);
        animation: pulseBar 1.6s infinite;
    }
    @keyframes pulseBar {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(217, 119, 6, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
    }

    /* Tickets Grid */
    .bar-ticket-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 20px;
    }

    /* Ticket Card */
    .bar-ticket-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-top: 4px solid #d97706;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .bar-ticket-card:hover {
        border-color: #d1d5db;
        border-top-color: #d97706;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
    }

    /* Card Header */
    .bar-ticket-card-header {
        padding: 16px 18px 12px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }
    .bar-ticket-num {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 2px;
    }
    .bar-ticket-time {
        font-size: 12px;
        color: #6b7280;
    }
    .bar-ticket-tag {
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
    .bar-ticket-card-body {
        padding: 16px 18px;
        flex: 1;
    }
    .bar-ticket-waiter-info {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .bar-ticket-items-header {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #9ca3af;
        margin-bottom: 10px;
    }
    
    /* Items List */
    .bar-ticket-items-list {
        list-style: none;
        padding: 0;
        margin: 0 0 14px 0;
    }
    .bar-ticket-item-row {
        padding: 8px 0;
        border-bottom: 1px solid #f9fafb;
        font-size: 13.5px;
        color: #111827;
    }
    .bar-ticket-item-row:last-child {
        border-bottom: none;
    }
    .bar-ticket-qty {
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
    .bar-ticket-item-note {
        font-size: 12px;
        color: #d97706;
        margin-top: 3px;
        padding-left: 34px;
        font-weight: 500;
    }
    .bar-ticket-order-note {
        background: #fffbe6;
        border: 1px solid #ffe58f;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12px;
        color: #855900;
        margin-top: 10px;
    }

    /* Card Footer Actions */
    .bar-ticket-card-footer {
        padding: 12px 18px 16px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        gap: 8px;
    }
    .bar-ticket-btn-print {
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
    .bar-ticket-btn-print:hover {
        background: #f9fafb;
    }
    .bar-ticket-btn-dispense {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 6px;
        background: #d97706;
        color: #ffffff;
        font-size: 12.5px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .bar-ticket-btn-dispense:hover {
        background: #b45309;
    }

    /* Empty State */
    .bar-ticket-empty {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 60px 20px;
        text-align: center;
    }
    .bar-ticket-empty-icon {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: #fffbe6;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 16px;
    }
    .bar-ticket-empty h3 {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px;
    }
    .bar-ticket-empty p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-refresh Bar Tickets screen every 12 seconds
    setInterval(function() {
        fetch("{{ route('admin.bar.tickets') }}", {
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
<div class="content-wrapper bar-ticket-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="bar-ticket-header">
        <div class="bar-ticket-title-group">
            <h1>Bar Dispense Tickets</h1>
            <p>Real-time beverage dispense tickets screen for bartenders.</p>
        </div>
        <div class="bar-ticket-status-badge">
            <span class="pulse-dot-bar"></span>
            <span>Live Auto-Sync Active ({{ $orders->count() }} {{ $orders->count() == 1 ? 'Ticket' : 'Tickets' }})</span>
        </div>
    </div>

    {{-- Tickets Grid --}}
    <div class="bar-ticket-grid">
        @forelse ($orders as $order)
            <div class="bar-ticket-card">
                
                {{-- Header --}}
                <div class="bar-ticket-card-header">
                    <div>
                        <h3 class="bar-ticket-num">Bar Ticket #{{ $order->order_no }}</h3>
                        <div class="bar-ticket-time">
                            <i class="far fa-clock me-1"></i> {{ $order->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div>
                        @if($order->order_type == 'instore')
                            <span class="bar-ticket-tag"><i class="fas fa-utensils text-muted me-1"></i> {{ $order->restaurantTable->name ?? 'Walk-in' }}</span>
                        @else
                            <span class="bar-ticket-tag"><i class="fas fa-motorcycle text-muted me-1"></i> {{ ucfirst($order->order_type) }}</span>
                        @endif
                    </div>
                </div>

                {{-- Body --}}
                <div class="bar-ticket-card-body">
                    @if($order->waiter)
                        <div class="bar-ticket-waiter-info">
                            <i class="fas fa-user-circle me-1"></i> Staff: <strong>{{ $order->waiter->name }}</strong>
                        </div>
                    @endif

                    <div class="bar-ticket-items-header">Drinks to Dispense:</div>
                    
                    <ul class="bar-ticket-items-list">
                        @foreach ($order->orderItems as $item)
                            <li class="bar-ticket-item-row">
                                <div>
                                    <span class="bar-ticket-qty">{{ $item->quantity }}x</span>
                                    <span class="fw-semibold">{{ $item->menu_name }}</span>
                                </div>
                                @if($item->item_note)
                                    <div class="bar-ticket-item-note">
                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $item->item_note }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    @if($order->additional_info)
                        <div class="bar-ticket-order-note">
                            <strong>Note:</strong> {{ $order->additional_info }}
                        </div>
                    @endif
                </div>

                {{-- Footer Actions --}}
                <div class="bar-ticket-card-footer">
                    <button type="button" 
                            onclick="window.open('{{ route('admin.orders.receipt', $order->id) }}?kitchen=1', '_blank', 'width=400,height=600')" 
                            class="bar-ticket-btn-print">
                        <i class="fas fa-print"></i> Print Ticket
                    </button>
                    
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" style="flex: 1;">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="bar-ticket-btn-dispense w-100">
                            <i class="fas fa-glass-cheers"></i> Dispense
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="col-12" style="grid-column: 1 / -1;">
                <div class="bar-ticket-empty">
                    <div class="bar-ticket-empty-icon"><i class="fas fa-glass-martini-alt"></i></div>
                    <h3>No Pending Bar Tickets</h3>
                    <p>New drink orders placed in POS or online will automatically appear on this screen.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
