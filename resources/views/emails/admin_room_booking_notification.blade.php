<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Room Booking Received</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; border-radius: 10px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { color: #28a745; font-size: 22px; margin-bottom: 20px; border-bottom: 2px solid #28a745; padding-bottom: 10px; }
        p { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        table th, table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        table th { background-color: #f9f9f9; font-weight: bold; width: 35%; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Room Booking</h1>
        <p>A new room booking has just been confirmed on the website.</p>

        <table>
            <tbody>
                <tr><th>Customer Name</th><td>{{ $booking->customer_name }}</td></tr>
                <tr><th>Customer Email</th><td>{{ $booking->customer_email }}</td></tr>
                <tr><th>Customer Phone</th><td>{{ $booking->customer_phone }}</td></tr>
                <tr><th>Room</th><td>{{ $booking->room->name }}</td></tr>
                <tr><th>Check-in Date</th><td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('F d, Y') }}</td></tr>
                <tr><th>Check-out Date</th><td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('F d, Y') }}</td></tr>
                <tr><th>Total Price</th><td>{!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price, 2) }}</td></tr>
                <tr><th>Deposit Paid</th><td>{!! $site_settings->currency_symbol !!}{{ number_format($booking->deposit_amount, 2) }}</td></tr>
            </tbody>
        </table>

        <div class="footer">
            <p>Automated notification from {{ config('site.name') }}</p>
        </div>
    </div>
</body>
</html>
