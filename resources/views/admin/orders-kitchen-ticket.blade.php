<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kitchen Ticket #{{ $order->order_no }}</title>
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
        .font-bold { font-weight: bold; font-size: 16px; }
        .mt-1 { margin-top: 5px; }
        .mb-1 { margin-bottom: 5px; }
        hr { border: none; border-top: 2px dashed #000; margin: 10px 0; }
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
        <div class="text-center font-bold" style="font-size: 20px;">
            KITCHEN / BAR TICKET
        </div>
        
        <hr>
        
        <div class="font-bold">Order No: #{{ $order->order_no }}</div>
        <div><strong>Date:</strong> {{ date('d M Y, H:i') }}</div>
        @if($order->waiter)
            <div><strong>Waiter:</strong> {{ $order->waiter->name }}</div>
        @endif
        @if($order->restaurantTable)
            <div class="font-bold" style="font-size: 18px;">Table: {{ $order->restaurantTable->name }}</div>
        @endif
        
        <hr>
        
        <table>
            <thead>
                <tr>
                    <th class="text-left font-bold">Qty</th>
                    <th class="text-left font-bold" style="padding-left: 10px;">Item</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unprintedItems as $item)
                <tr class="item-row">
                    <td class="font-bold" style="font-size: 18px; width: 30px;">{{ $item->quantity }} x</td>
                    <td class="font-bold" style="padding-left: 10px;">
                        {{ $item->menu_name }}
                        @if(!empty($item->item_note))
                            <br><small style="font-style: italic; font-weight: normal; color: #333;">* Note: {{ $item->item_note }}</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($order->additional_info)
        <hr>
        <div><strong>Notes:</strong></div>
        <div style="white-space: pre-wrap;">{{ $order->additional_info }}</div>
        @endif

        <hr>
        
        <div class="text-center">
            -- End of Ticket --
        </div>
    </div>
    
    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
