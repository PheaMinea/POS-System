@extends('layouts.cashier')

@section('title', 'Point of Sale')
@section('page_title', 'Point of Sale')

@section('content')

<div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-amber-700">Customer Orders</h2>
            <p class="text-sm text-amber-600">New online orders will appear here automatically.</p>
        </div>
        <div class="rounded-full bg-white px-3 py-1 text-sm font-semibold text-amber-700" id="pendingOrdersBadge">0 pending</div>
    </div>

    <div id="pendingOrdersList" class="mt-4 space-y-2">
        <div class="text-sm text-amber-600">Loading customer orders...</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Products list -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Products</h2>
                <div class="text-sm text-slate-400">Click a product to add to cart</div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="border rounded-xl p-4 hover:shadow transition">
                        @if($product->image_url)
                            <div class="mb-3 h-28 flex items-center justify-center overflow-hidden rounded-md bg-slate-50">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="object-cover w-full h-full">
                            </div>
                        @else
                            <div class="mb-3 h-28 flex items-center justify-center rounded-md bg-slate-50 text-slate-400 font-bold text-2xl">
                                {{ strtoupper(substr($product->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between mb-2">
                            <div class="font-medium text-slate-700">{{ $product->name }}</div>
                            <div class="text-sm text-slate-400">Stock: {{ $product->stock }}</div>
                        </div>
                        <p class="text-emerald-600 font-bold mb-3">${{ number_format($product->price, 2) }}</p>
                        <div class="flex justify-between items-center">
                            <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }})"
                                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm font-medium">
                                Add
                            </button>
                            <a href="{{ route('cashier.orders.index') }}" class="text-xs text-slate-400">Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Cart -->
    <div>
        @include('cashier.pos.cart')
    </div>

</div>

<script>
    let pendingOrders = [];

    function renderPendingOrders(orders) {
        const container = document.getElementById('pendingOrdersList');
        const badge = document.getElementById('pendingOrdersBadge');

        const pending = orders.filter(order => ['pending', 'preparing', 'ready'].includes(order.status || 'pending'));
        pendingOrders = pending;

        badge.textContent = `${pending.length} pending`;

        if (!pending.length) {
            container.innerHTML = '<div class="text-sm text-slate-500">No new customer orders right now.</div>';
            return;
        }

        container.innerHTML = pending.map(order => `
            <div class="flex flex-wrap items-center justify-between rounded-xl border border-amber-200 bg-white px-4 py-3">
                <div>
                    <div class="font-semibold text-slate-800">Order #${order.id}</div>
                    <div class="text-sm text-slate-500">${order.customer?.name || 'Walk-in Customer'} • ${order.order_items?.length || 0} items</div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">${(order.status || 'pending').toUpperCase()}</span>
                    <a href="/cashier/orders/${order.id}" class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white">View</a>
                </div>
            </div>
        `).join('');
    }

    function fetchPendingOrders() {
        fetch('{{ route('cashier.orders.data') }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && Array.isArray(data.orders)) {
                const previousCount = pendingOrders.length;
                renderPendingOrders(data.orders);

                if (data.orders.length > 0 && pendingOrders.length > previousCount) {
                    showToast('New customer order arrived');
                }
            }
        })
        .catch(() => {
            document.getElementById('pendingOrdersList').innerHTML = '<div class="text-sm text-rose-500">Unable to reload customer orders right now.</div>';
        });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed right-4 top-4 z-[3000] rounded-xl bg-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-lg';
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    document.addEventListener('DOMContentLoaded', function () {
        fetchPendingOrders();
        setInterval(fetchPendingOrders, 5000);
    });
</script>

@endsection
