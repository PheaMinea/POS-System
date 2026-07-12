<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body { font-family: 'Khmer OS', 'Arial', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h1 { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->customer?->name ?? 'Walk-in Customer' }}</td>
                <td>${{ number_format($order->total_price, 2) }}</td>
                <td>{{ $order->created_at->format('M d, Y h:i A') }}</td>
                <td>Completed</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No sales found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p><strong>Total Orders:</strong> {{ $orders->count() }}</p>
    <p><strong>Total Revenue:</strong> ${{ number_format($orders->sum('total_price'), 2) }}</p>
</body>
</html>