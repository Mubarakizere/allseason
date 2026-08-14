<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Venue Booking Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 40px auto; padding: 20px; border-radius: 10px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .logo { text-align: center; margin-bottom: 20px; background-color: #000; padding: 10px; border-radius: 5px; }
        h1 { color: #0073e6; font-size: 22px; margin-bottom: 20px; }
        p { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        table th, table td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        table th { background-color: #f9f9f9; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 12px; text-align: center; color: #666; }
        .footer hr { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h2 style="color: #333; margin: 0; font-family: sans-serif;">All The Season Garden</h2>
        </div>
        <h1>Hello, {{ $booking->customer_name }},</h1>
        <p>Thank you for booking a venue with us! Below are the details of your confirmed booking.</p>

        <table>
            <tbody>
                <tr><th>Venue</th><td>{{ $booking->venue->name }}</td></tr>
                <tr><th>Package</th><td>{{ $booking->package->name }}</td></tr>
                <tr><th>Date</th><td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</td></tr>
                <tr><th>Total Price</th><td>{!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price, 2) }}</td></tr>
                <tr><th>Deposit Paid</th><td>{!! $site_settings->currency_symbol !!}{{ number_format($booking->deposit_amount, 2) }}</td></tr>
                <tr><th>Remaining Balance</th><td>{!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price - $booking->deposit_amount, 2) }}</td></tr>
            </tbody>
        </table>

        <p>If you have any questions or need to make changes to your booking, please contact us.</p>
        <p><strong>Contact Information:</strong></p>
        <p>Email: {{ config('site.email') }}</p>
        <p>Phone: {{ $companyPhone ? $companyPhone : 'Not available' }}</p>

        <div class="footer">
            <hr>
            <p>If you believe this email is not intended for you, please ignore it.</p>
            <p>Regards,<br>{{ config('site.name') }}</p>
        </div>
    </div>
</body>
</html>
