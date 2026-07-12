
@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <!-- Analytics Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 text-white">

        <h1 class="text-4xl font-bold">
            Purchase Report
        </h1>

        <p class="mt-2 text-indigo-100">
            Monitor supplier purchases and inventory expenses
        </p>

    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Total Purchases
                    </p>

                    <h2 class="text-4xl font-bold text-blue-600 mt-2">
                        {{ $purchases->count() }}
                    </h2>

                </div>

                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">

                    <i class="fas fa-cart-shopping text-blue-600 text-xl"></i>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Total Cost
                    </p>

                    <h2 class="text-4xl font-bold text-red-600 mt-2">
                        ${{ number_format($purchases->sum('total_price'),2) }}
                    </h2>

                </div>

                <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center">

                    <i class="fas fa-dollar-sign text-red-600 text-xl"></i>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">

            <div class="flex justify-between items-center">

                <div>

                    <p class="text-gray-500">
                        Today Purchases
                    </p>

                    <h2 class="text-4xl font-bold text-purple-600 mt-2">
                        ${{ number_format(
                            $purchases->where('created_at','>=',now()->startOfDay())->sum('total_price'),
                            2
                        ) }}
                    </h2>

                </div>

                <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">

                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- Search Box -->
    <div class="bg-white rounded-2xl shadow-sm p-6">

        <div class="relative">

            <i class="fas fa-search absolute left-4 top-4 text-gray-400"></i>

            <input
                type="text"
                id="searchPurchase"
                placeholder="Search supplier..."
                class="w-full border rounded-xl pl-12 pr-4 py-3">

        </div>

    </div>

    <!-- Purchase Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <div class="p-6 border-b">

            <h3 class="text-xl font-semibold">
                Purchase Transactions
            </h3>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="p-4 text-left">
                            Purchase ID
                        </th>

                        <th class="p-4 text-left">
                            Supplier
                        </th>

                        <th class="p-4 text-left">
                            Total Cost
                        </th>

                        <th class="p-4 text-left">
                            Date
                        </th>

                        <th class="p-4 text-left">
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody id="purchaseTable">

                    @forelse($purchases as $purchase)

                    <tr class="border-b hover:bg-slate-50">

                        <td class="p-4 font-medium">
                            #{{ $purchase->id }}
                        </td>

                        <td class="p-4 supplier-name">
                            {{ $purchase->supplier?->name ?? '-' }}
                        </td>

                        <td class="p-4 font-bold text-red-600">
                            ${{ number_format($purchase->total_price,2) }}
                        </td>

                        <td class="p-4">
                            {{ $purchase->created_at->format('d M Y H:i') }}
                        </td>

                        <td class="p-4">

                            <span
                                class="inline-flex items-center gap-2
                                       bg-green-100 text-green-700
                                       px-3 py-1 rounded-full text-sm">

                                <i class="fas fa-circle-check"></i>

                                Completed

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-10 text-gray-500">

                            No Purchases Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>

document
.getElementById('searchPurchase')
.addEventListener('keyup', function(){

    let value =
    this.value.toLowerCase();

    let rows =
    document.querySelectorAll('#purchaseTable tr');

    rows.forEach(row => {

        let supplier =
        row.querySelector('.supplier-name');

        if(!supplier) return;

        let text =
        supplier.textContent.toLowerCase();

        row.style.display =
        text.includes(value)
        ? ''
        : 'none';

    });

});

</script>

@endsection

