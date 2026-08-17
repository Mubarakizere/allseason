<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $ticketTitle ?? 'Preparation Ticket' }} #{{ $order->order_no }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background: #fff;
            width: 80mm;
        }
        .receipt-container {
            padding: 10px;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .ticket-header-box {
            border: 2px solid #000;
            padding: 6px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .mt-1 { margin-top: 5px; }
        .mb-1 { margin-bottom: 5px; }
        hr { border: none; border-top: 2px dashed #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 4px 0; }
        .item-row td { vertical-align: top; border-bottom: 1px dotted #000; padding: 6px 0; }
        
        @media print {
            body { width: 100%; }
            .receipt-container { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt-container">
        @php
            $headerTitle = $ticketTitle ?? 'KITCHEN / BAR TICKET';
            $itemsList = $displayItems ?? ($unprintedItems ?? $order->orderItems);
        @endphp

        <div class="ticket-header-box">
            *** {{ $headerTitle }} ***
        </div>
        
        <div style="font-size: 15px; margin-bottom: 4px;"><strong>Order No:</strong> #{{ $order->order_no }}</div>
        <div><strong>Time:</strong> {{ date('H:i - d M Y') }}</div>
        @if($order->waiter)
            <div><strong>Waiter:</strong> {{ $order->waiter->name }}</div>
        @endif
        @if($order->restaurantTable)
            <div class="font-bold" style="font-size: 20px; margin-top: 4px; background: #eee; padding: 3px 6px; border: 1px solid #000;">
                TABLE: {{ $order->restaurantTable->name }}
            </div>
        @else
            <div class="font-bold" style="font-size: 16px; margin-top: 4px;">
                SERVICE: {{ strtoupper($order->order_type) }}
            </div>
        @endif
        
        <hr>
        
        <table>
            <thead>
                <tr>
                    <th class="text-left font-bold" style="font-size: 15px; width: 45px;">QTY</th>
                    <th class="text-left font-bold" style="font-size: 15px; padding-left: 6px;">ITEM DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemsList as $item)
                <tr class="item-row">
                    <td class="font-bold" style="font-size: 20px; width: 45px;">{{ $item->quantity }}x</td>
                    <td class="font-bold" style="font-size: 16px; padding-left: 6px;">
                        {{ $item->menu_name }}
                        @if(!empty($item->item_note))
                            <br><span style="font-style: italic; font-weight: normal; font-size: 13px; color: #000;">* Note: {{ $item->item_note }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center py-2">No items for this ticket.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($order->additional_info)
        <hr>
        <div class="font-bold" style="font-size: 13px;">SPECIAL ORDER INSTRUCTIONS:</div>
        <div style="white-space: pre-wrap; font-size: 13px; font-weight: bold;">{{ $order->additional_info }}</div>
        @endif

        <hr>
        
        <div class="text-center font-bold" style="font-size: 12px;">
            -- END OF {{ $headerTitle }} --
        </div>
    </div>
    
    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
