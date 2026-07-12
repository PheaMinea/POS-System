<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Report</title>
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
    <h1>Purchase Report</h1>
    <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice</th>
                <th>Supplier</th>
                <th>Total Cost</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>#{{ $purchase->id }}</td>
                <td>{{ $purchase->supplier?->name ?? 'N/A' }}</td>
                <td>${{ number_format($purchase->total_price, 2) }}</td>
                <td>{{ $purchase->created_at->format('M d, Y h:i A') }}</td>
                <td>Completed</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No purchases found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p><strong>Total Purchases:</strong> {{ $purchases->count() }}</p>
    <p><strong>Total Cost:</strong> ${{ number_format($purchases->sum('total_price'), 2) }}</p>
</body>
</html>