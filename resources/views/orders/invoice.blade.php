<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Invoice for Order #{{ $order->id }}</h1>
        <p>Date: {{ $order->date->format('d/m/Y') }}</p>
    </div>

    <h3>Customer Details</h3>
    <p>Name: {{ $order->member->name }}</p>
    <p>NIF: {{ $order->nif }}</p>
    <p>Delivery Address: {{ $order->delivery_address }}</p>

    <h3>Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Unit Price (€)</th>
                <th>Quantity</th>
                <th>Discount (€)</th>
                <th>Subtotal (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->order_item->unit_price, 2) }}</td>
                <td>{{ $product->order_item->quantity }}</td>
                <td>{{ number_format($product->order_item->discount, 2) }}</td>
                <td>{{ number_format($product->order_item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total: €{{ number_format($order->total, 2) }}</strong></p>
</body>
</html>
