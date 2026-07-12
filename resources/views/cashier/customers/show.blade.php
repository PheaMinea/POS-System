@extends('layouts.cashier')

@section('title', 'Customer Detail')
@section('page_title', 'Customer Detail')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-green-600 p-8 text-white">
            <div class="flex items-center gap-3">
                <i class="fas fa-user text-3xl opacity-50"></i>
                <div>
                    <h1 class="text-3xl font-bold">Customer Detail</h1>
                    <p class="text-emerald-100 mt-1">
                        <i class="fas fa-info-circle mr-2"></i>View customer information
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">

            <!-- Avatar & Name -->
            <div class="flex items-center gap-5 mb-8">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-100 to-green-100 flex items-center justify-center shadow-lg border-4 border-white">
                    <span class="text-3xl font-bold text-emerald-600">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $customer->name }}</h2>
                    <p class="text-slate-400">
                        <i class="fas fa-id-card mr-1"></i>Customer Profile
                    </p>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-1"></i>Customer ID
                    </label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 font-medium text-slate-700">
                        {{ $customer->id }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-phone mr-1"></i>Phone Number
                    </label>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 font-medium text-slate-700">
                        {{ $customer->phone ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-400 mb-1.5 uppercase tracking-wider">
                    <i class="fas fa-map-pin mr-1"></i>Address
                </label>
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 min-h-[80px] text-slate-700">
                    {{ $customer->address ?? 'No address provided' }}
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6">
                <div class="bg-emerald-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-emerald-600">{{ $customer->orders_count ?? 0 }}</p>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Orders</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">$0.00</p>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Spent</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-amber-600">{{ $customer->created_at ? $customer->created_at->format('M d, Y') : '-' }}</p>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Joined</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('cashier.customers.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-5 py-3 rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>

                <a href="{{ route('cashier.customers.edit', $customer->id) }}"
                   class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit Customer
                </a>

                <button onclick="confirmDelete({{ $customer->id }})"
                        class="bg-rose-500 hover:bg-rose-600 text-white px-5 py-3 rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i>
                    Delete Customer
                </button>
            </div>

        </div>

    </div>

</div>

<script>
    function confirmDelete(customerId) {
        if (!confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
            return;
        }

        const deleteUrl = `/cashier/customers/${customerId}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        fetch(deleteUrl, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("cashier.customers.index") }}';
            } else {
                alert('Failed to delete customer: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
            alert('Failed to delete customer. Please try again.');
        });
    }
</script>

<style>
    @media (max-width: 640px) {
        .grid.md\:grid-cols-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

@endsection