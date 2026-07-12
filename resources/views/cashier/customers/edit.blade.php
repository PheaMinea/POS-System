@extends('layouts.cashier')

@section('title', 'Edit Customer')
@section('page_title', 'Edit Customer')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-user-edit text-amber-500"></i>
                Edit Customer
            </h2>
            <p class="text-slate-400 text-sm mt-1">
                <i class="fas fa-info-circle mr-1"></i>
                Update customer information
            </p>
        </div>

        <form id="editForm" class="space-y-5">

            <!-- Name -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-user text-emerald-400 mr-2"></i>Full Name
                </label>
                <input type="text"
                       id="name"
                       placeholder="Enter customer name"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-phone text-emerald-400 mr-2"></i>Phone Number
                </label>
                <input type="text"
                       id="phone"
                       placeholder="Enter phone number"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none">
            </div>

            <!-- Address -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-map-pin text-emerald-400 mr-2"></i>Address
                </label>
                <textarea id="address"
                          rows="4"
                          placeholder="Enter customer address"
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none"></textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit"
                        class="bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-600 hover:to-yellow-700 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-lg shadow-amber-500/25">
                    <i class="fas fa-save"></i>
                    Update Customer
                </button>

                <a href="{{ route('cashier.customers.index') }}"
                   class="text-slate-500 hover:text-slate-700 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<script>
    const customerId = window.location.pathname.split('/').pop();

    async function loadCustomer() {
        try {
            const response = await fetch(`/api/customers/${customerId}`);
            const result = await response.json();
            const customer = result.data;

            document.getElementById('name').value = customer.name || '';
            document.getElementById('phone').value = customer.phone || '';
            document.getElementById('address').value = customer.address || '';
        } catch (error) {
            console.error(error);
        }
    }

    loadCustomer();

    document.getElementById('editForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', document.getElementById('name').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('address', document.getElementById('address').value);

        try {
            const response = await fetch(`/api/customers/${customerId}`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = '/cashier/customers';
            } else {
                alert('Error: ' + (result.message || 'Something went wrong'));
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });
</script>

@endsection