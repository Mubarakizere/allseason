<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $order->order_no }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #fff;
            width: 80mm; /* standard thermal receipt width */
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
        th, td { padding: 2px 0; }
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
            <h2 class="mb-1">{{ config('site.name') }}</h2>
            <div>{{ config('site.address', '') }}</div>
            @php
                $siteSetting = \App\Models\SiteSetting::first();
                $country = $siteSetting ? $siteSetting->country : config('site.country', '');
                $phone = \App\Models\RestaurantPhoneNumber::first();
            @endphp
            <div>{{ $country }}</div>
            @if($phone)
                <div>Tel: {{ $phone->phone_number }}</div>
            @endif
            @if(!empty($siteSetting?->momo_code))
                <div>MoMo Pay: <strong>{{ $siteSetting->momo_code }}</strong></div>
            @endif
            <div class="mt-1 font-bold">RECEIPT</div>
        </div>
        
        <hr>
        
        <div><strong>Order No:</strong> #{{ $order->order_no }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</div>
        <div><strong>Customer:</strong> {{ $order->customer->first_name ?? 'Walk-in' }} {{ $order->customer->last_name ?? '' }}</div>
        @if($order->customer && $order->customer->phone_number)
            <div><strong>Phone:</strong> {{ $order->customer->phone_number }}</div>
        @endif
        <div><strong>Type:</strong> {{ ucfirst($order->order_type) }}</div>
        @if($order->waiter)
            <div><strong>Waiter:</strong> {{ $order->waiter->name }}</div>
        @endif
        @if($order->restaurantTable)
            <div><strong>Table:</strong> {{ $order->restaurantTable->name }}</div>
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
                    <td class="text-right">{{ config('site.currency_symbol') }}{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <hr>
        
        <table>
            @if($order->discount_amount > 0)
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ config('site.currency_symbol') }}{{ number_format($order->total_price + $order->discount_amount - ($order->delivery_fee ?? 0), 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td class="text-right">-{{ config('site.currency_symbol') }}{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @else
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ config('site.currency_symbol') }}{{ number_format($order->total_price - ($order->delivery_fee ?? 0), 2) }}</td>
            </tr>
            @endif

            @if($order->delivery_fee > 0)
            <tr>
                <td>Delivery Fee</td>
                <td class="text-right">{{ config('site.currency_symbol') }}{{ number_format($order->delivery_fee, 2) }}</td>
            </tr>
            @endif

            <tr>
                <td class="font-bold">TOTAL</td>
                <td class="text-right font-bold">{{ config('site.currency_symbol') }}{{ number_format($order->total_price, 2) }}</td>
            </tr>

            @if($order->amount_tendered > 0)
            <tr>
                <td>Tendered</td>
                <td class="text-right">{{ config('site.currency_symbol') }}{{ number_format($order->amount_tendered, 2) }}</td>
            </tr>
            <tr>
                <td>Change Due</td>
                <td class="text-right">{{ config('site.currency_symbol') }}{{ number_format($order->change_due, 2) }}</td>
            </tr>
            @endif
        </table>
        
        <hr>
        
        <div class="text-center font-bold">
            {{ strtoupper($order->status_online_pay ?? 'Paid') }} - {{ $order->payment_method ?? 'CASH' }}
        </div>
        @if(!empty($siteSetting?->momo_code))
            <div class="text-center mt-1">
                <strong>MoMo Pay Code: {{ $siteSetting->momo_code }}</strong>
            </div>
        @endif
    </div>
    
    <script>
        // Close the window after printing (if opened as popup)
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
