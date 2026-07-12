@extends('layouts.cashier')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-users text-emerald-500 mr-3"></i>Customers
            </h1>
            <p class="text-slate-400 mt-1">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-2 align-middle"></i>
                Manage customer information
            </p>
        </div>

        <a href="{{ route('cashier.customers.create') }}"
           class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35">
            <i class="fas fa-plus-circle"></i>
            Add Customer
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-emerald-600" id="totalCustomers">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Customers</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-blue-600" id="activeCustomers">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Active</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-amber-600" id="newCustomers">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">New This Month</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       id="searchCustomer"
                       placeholder="Search customers..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none bg-slate-50/50 hover:bg-white">
            </div>
            <button class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl hover:bg-emerald-100 transition">
                <i class="fas fa-sliders-h"></i>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Phone</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Orders</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="customerTable">
                    <tr>
                        <td colspan="5" class="text-center py-10 text-slate-400">
                            <i class="fas fa-spinner fa-spin text-2xl block mb-2"></i>
                            Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    let customers = @json($customers->toArray());

    document.addEventListener('DOMContentLoaded', function() {
        renderCustomers(customers);
        updateStats(customers);
    });

    function renderCustomers(items) {
        let html = '';

        if (items.length === 0) {
            html = `
                <tr>
                    <td colspan="5" class="text-center py-12 text-slate-400">
                        <i class="fas fa-users-slash text-4xl block mb-3 opacity-30"></i>
                        <p class="text-lg font-medium text-slate-500">No Customers Found</p>
                        <p class="text-sm">Start by adding your first customer</p>
                    </td>
                </tr>
            `;
        } else {
            items.forEach((customer, index) => {
                const initial = customer.name ? customer.name.charAt(0).toUpperCase() : '?';
                const colors = ['emerald', 'blue', 'purple', 'rose', 'amber', 'cyan'];

                html += `
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="p-4 text-sm text-slate-400 font-medium">${index + 1}</td>

                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-${colors[index % colors.length]}-100 flex items-center justify-center text-${colors[index % colors.length]}-600 font-bold text-sm">
                                    ${initial}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-700">${customer.name || 'Unnamed'}</p>
                                    <p class="text-xs text-slate-400">ID: ${customer.id}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">
                            <span class="flex items-center gap-2 text-slate-600">
                                <i class="fas fa-phone text-emerald-400 text-sm"></i>
                                ${customer.phone || '-'}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                ${customer.orders_count ?? 0}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="/cashier/customers/${customer.id}"
                                   class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                    <span class="hidden sm:inline">View</span>
                                </a>

                                <a href="/cashier/customers/${customer.id}/edit"
                                   class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-edit"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                                <button onclick="confirmDelete(${customer.id})"
                                        class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="hidden sm:inline">Delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        document.getElementById('customerTable').innerHTML = html;
    }

    function updateStats(customers) {
        document.getElementById('totalCustomers').textContent = customers.length;
        document.getElementById('activeCustomers').textContent = customers.filter(c => c.is_active !== false).length;

        const now = new Date();
        const firstDayOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        const newCount = customers.filter(c => {
            if (!c.created_at) return false;
            return new Date(c.created_at) >= firstDayOfMonth;
        }).length;
        document.getElementById('newCustomers').textContent = newCount;
    }

    // Search
    document.getElementById('searchCustomer').addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        const filtered = customers.filter(c =>
            c.name?.toLowerCase().includes(keyword) ||
            c.phone?.includes(keyword)
        );
        renderCustomers(filtered);
    });

    // Delete customer with confirmation
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
                customers = customers.filter(c => c.id !== customerId);
                renderCustomers(customers);
                updateStats(customers);
                showToast('success', 'Customer deleted successfully');
            } else {
                showToast('error', data.message || 'Failed to delete customer');
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
            showToast('error', 'Failed to delete customer. Please try again.');
        });
    }

    // Toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-[3000] px-6 py-4 rounded-xl shadow-lg text-white font-medium transition-all duration-300 ${
            type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'
        }`;
        toast.style.transform = 'translateX(120%)';
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${message}`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 50);

        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>

<style>
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
</style>

@endsection