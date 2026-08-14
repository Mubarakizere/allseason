<!DOCTYPE html>
<html>
<head>
    <title>New Order</title>
</head>
<body>
    <h2>New Order Notification</h2>
    <p>A new order has been received.</p>
    <p><strong>Order No:</strong> {{ $order->order_no }}</p>
    <p><strong>Customer:</strong> {{ $order->customer->first_name ?? 'Walk-in' }} {{ $order->customer->last_name ?? '' }} ({{ $order->customer->email ?? 'N/A' }})</p>
    <p><strong>Type:</strong> {{ ucfirst($order->order_type) }}</p>
    <p><strong>Total:</strong> {{ $order->total_price }}</p>
    
    <h3>Items:</h3>
    <ul>
        @foreach($order->orderItems as $item)
            <li>{{ $item->quantity }}x {{ $item->menu_name }} - {{ $item->subtotal }}</li>
        @endforeach
    </ul>

    <p>Please check the admin dashboard for full details.</p>
</body>
</html>
