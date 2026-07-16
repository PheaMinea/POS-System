@extends('layouts.admin')

@section('title', 'Create Purchase')
@section('page_title', 'Create Purchase')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            <i class="fas fa-plus-circle text-indigo-600 mr-3"></i>Create Purchase
        </h1>
        <p class="text-slate-500 mt-1">
            <i class="fas fa-info-circle mr-2"></i>Record new stock purchase from supplier
        </p>
    </div>

    <form method="POST"
          action="{{ route('admin.purchases.store') }}">

        @csrf

        <div class="bg-white rounded-2xl shadow-sm p-6">

            <!-- Supplier -->
            <div class="mb-6">
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-truck text-indigo-500 mr-2"></i>Supplier
                </label>
                <select name="supplier_id"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none"
                        required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Purchase Price</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Quantity</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($products as $product)
                        <tr class="purchase-item border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}"
                                             class="w-12 h-12 rounded-xl object-cover border-2 border-slate-200"
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                            <i class="fas fa-box text-slate-400 text-lg"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <h4 class="font-medium text-slate-800">{{ $product->name }}</h4>
                                        <p class="text-xs text-slate-400">SKU: {{ $product->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">$</span>
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           name="items[{{ $product->id }}][price]"
                                           value="{{ $product->price }}"
                                           class="item-price w-full border border-slate-200 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                                </div>
                            </td>

                            <td class="p-4">
                                <input type="number"
                                       min="0"
                                       value="0"
                                       name="items[{{ $product->id }}][quantity]"
                                       class="item-quantity w-full border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                            </td>

                            <td class="p-4 font-bold text-emerald-600 item-subtotal">
                                $0.00
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="border-t-2 border-slate-200">
                            <th colspan="3" class="text-right p-4 text-sm font-semibold text-slate-600">
                                <i class="fas fa-calculator text-indigo-500 mr-2"></i>Grand Total
                            </th>
                            <th id="purchaseTotal" class="p-4 text-2xl font-bold text-emerald-600">
                                $0.00
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-slate-200">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-save"></i>
                    Save Purchase
                </button>

                <a href="{{ route('admin.purchases.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-8 py-3 rounded-xl font-medium transition flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>

        </div>

    </form>

</div>

<script>
    function calculatePurchaseTotal() {
        let total = 0;

        document.querySelectorAll('.purchase-item').forEach(row => {
            const price = Number(row.querySelector('.item-price').value) || 0;
            const quantity = Number(row.querySelector('.item-quantity').value) || 0;
            const subtotal = price * quantity;

            row.querySelector('.item-subtotal').textContent = '$' + subtotal.toFixed(2);
            total += subtotal;
        });

        document.getElementById('purchaseTotal').textContent = '$' + total.toFixed(2);
    }

    document.querySelectorAll('.item-price, .item-quantity').forEach(input => {
        input.addEventListener('input', calculatePurchaseTotal);
        input.addEventListener('change', calculatePurchaseTotal);
    });

    calculatePurchaseTotal();
</script>

<style>
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>

@endsection
