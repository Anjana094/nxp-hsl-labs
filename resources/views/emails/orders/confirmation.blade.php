<!doctype html>
<html>
<head><meta charset="utf-8"><title>Order Confirmation</title></head>
<body>
    <p>Hi {{ $order->provider->name }},</p>
    <p>Your order #{{ $order->id }} for {{ $order->quantity }} unit(s) was placed on {{ $order->ordered_at->toDateTimeString() }}.</p>
    <p>Updated stock: {{ $order->provider->inventory->stock }}</p>
    <p>Regards,<br>HSL Labs</p>
</body>
</html>
