<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Report</title>
    <style>
        body { font-family: 'Khmer OS', 'Arial', sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h1 { text-align: center; }
        .text-right { text-align: right; }
        .low-stock { color: #e11d48; font-weight: bold; }
        .medium-stock { color: #d97706; font-weight: bold; }
        .in-stock { color: #059669; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Stock Report</h1>
    <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Stock Value</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product->name }} (SKU: {{ $product->id }})</td>
                <td>{{ $product->category?->name ?? '-' }}</td>
                <td>${{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>
                <td>${{ number_format($product->stock * $product->price, 2) }}</td>
                <td>
                    @if($product->stock <= 5)
                        Low Stock
                    @elseif($product->stock <= 20)
                        Medium Stock
                    @else
                        In Stock
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <p><strong>Total Products:</strong> {{ $products->count() }}</p>
    <p><strong>Total Stock:</strong> {{ $products->sum('stock') }}</p>
    <p><strong>Inventory Value:</strong> ${{ number_format($products->sum(fn($p) => $p->stock * $p->price), 2) }}</p>
    <p><strong>Low Stock Items:</strong> {{ $products->where('stock', '<=', 5)->count() }}</p>
</body>
</html>