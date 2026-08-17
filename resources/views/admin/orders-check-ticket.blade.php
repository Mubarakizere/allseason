<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pre-Bill Order Ticket #{{ $order->order_no }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            width: 80mm;
        }
        .receipt-container {
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-1 { margin-top: 5px; }
        .mb-1 { margin-bottom: 5px; }
        hr { border: none; border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 3px 0; }
        .item-row td { vertical-align: top; }
        
        @media print {
            body { width: 100%; }
            .receipt-container { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        <div class="text-center">
            <h2 class="mb-1" style="font-size: 16px;">{{ config('site.name') }}</h2>
            <div class="font-bold" style="font-size: 14px; margin-top: 4px;">ORDER TICKET / PRE-BILL CHECK</div>
            <div style="font-size: 11px; font-style: italic;">(NOT AN OFFICIAL RECEIPT)</div>
        </div>
        
        <hr>
        
        <div><strong>Order No:</strong> #{{ $order->order_no }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</div>
        <div><strong>Customer:</strong> {{ $order->customer->first_name ?? 'Guest / Walk-in' }} {{ $order->customer->last_name ?? '' }}</div>
        <div><strong>Type:</strong> {{ ucfirst($order->order_type) }}</div>
        @if($order->waiter)
            <div><strong>Waiter:</strong> {{ $order->waiter->name }}</div>
        @endif
        @if($order->restaurantTable)
            <div class="font-bold" style="font-size: 13px;"><strong>Table:</strong> {{ $order->restaurantTable->name }}</div>
        @endif
        
        <hr>
        
        <table>
            <thead>
                <tr>
                    <th style="text-align: left;">Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr class="item-row">
                    <td>
                        {{ $item->menu_name }}
                        @if(!empty($item->item_note))
                            <br><small style="font-style: italic;">* {{ $item->item_note }}</small>
                        @endif
                    </td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{!! html_entity_decode($site_settings->currency_symbol ?? 'RWF') !!}{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <hr>
        
        <table>
            @if($order->discount_amount > 0)
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{!! html_entity_decode($site_settings->currency_symbol ?? 'RWF') !!}{{ number_format($order->total_price + $order->discount_amount - ($order->delivery_fee ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td class="text-right">-{!! html_entity_decode($site_settings->currency_symbol ?? 'RWF') !!}{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @else
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{!! html_entity_decode($site_settings->currency_symbol ?? 'RWF') !!}{{ number_format($order->total_price - ($order->delivery_fee ?? 0), 2) }}</td>
            </tr>
            @endif

            @if($order->delivery_fee > 0)
            <tr>
                <td>Delivery Fee</td>
                <td class="text-right">{!! html_entity_decode($site_settings->currency_symbol ?? 'RWF') !!}{{ number_format($order->delivery_fee, 2) }}</td>
            </tr>
            @endif

            <tr>
                <td class="font-bold" style="font-size: 14px;">TOTAL DUE</td>
                <td class="text-right font-bold" style="font-size: 14px;">{!! html_entity_decode($site_settings->currency_symbol ?? 'RWF') !!}{{ number_format($order->total_price, 2) }}</td>
            </tr>
        </table>
        
        @if(!empty($site_settings->momo_code))
        <div class="text-center mt-1" style="border: 1px dashed #000; padding: 6px; margin: 8px 0;">
            <div class="font-bold" style="font-size: 13px;">MoMo Pay Code: {{ $site_settings->momo_code }}</div>
            <div style="font-size: 10px;">Dial *182*8*1*{{ $site_settings->momo_code }}# to pay</div>
        </div>
        @endif

        <hr>
        
        <div class="text-center font-bold" style="font-size: 11px;">
            *** PRE-BILL CHECK FOR TABLE PAYMENT ***
        </div>
    </div>
    
    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
