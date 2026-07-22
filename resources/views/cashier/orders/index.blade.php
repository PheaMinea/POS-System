@extends('layouts.cashier')

@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-receipt text-emerald-500 mr-3"></i>Orders
            </h1>
            <p class="text-slate-400 mt-1">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-2 align-middle"></i>
                Manage customer orders
            </p>
        </div>

        <a href="{{ route('cashier.pos.index') }}"
           class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-lg shadow-emerald-500/25">
            <i class="fas fa-plus-circle"></i>
            New Order
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-emerald-600" id="totalOrders">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Total Orders</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-blue-600" id="todayOrders">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Today</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-amber-600" id="pendingOrders">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Pending</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-emerald-600" id="completedOrders">0</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Completed</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       id="searchOrder"
                       placeholder="Search by order ID or customer..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none bg-slate-50/50 hover:bg-white">
            </div>
            <select id="statusFilter" class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none bg-white">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="accepted">Accepted</option>
                <option value="preparing">Preparing</option>
                <option value="ready">Ready</option>
                <option value="completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">
                <i class="fas fa-list text-emerald-500 mr-2"></i>Order List
            </h3>
            <span class="text-sm text-slate-400" id="orderCount">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-1.5 align-middle"></i>
                Loading...
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Order ID</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Items</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="orderTable">
                    <tr>
                        <td colspan="7" class="text-center py-10 text-slate-400">
                            <i class="fas fa-spinner fa-spin text-2xl block mb-2"></i>
                            Loading orders...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    // Use server-rendered orders to avoid API auth issues.
    let orders = @json($orders->toArray());
    let lastOrderId = orders.length ? orders[0].id : null;
    let newOrderSound = null;

    // Create notification sound
    function playNewOrderSound() {
        try {
            if (!newOrderSound) {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                newOrderSound = audioCtx;
            }
            // Play a short notification beep
            const oscillator = newOrderSound.createOscillator();
            const gainNode = newOrderSound.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(newOrderSound.destination);
            oscillator.frequency.value = 880;
            oscillator.type = 'sine';
            gainNode.gain.setValueAtTime(0.3, newOrderSound.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, newOrderSound.currentTime + 0.5);
            oscillator.start(newOrderSound.currentTime);
            oscillator.stop(newOrderSound.currentTime + 0.5);

            // Second beep
            setTimeout(() => {
                const osc2 = newOrderSound.createOscillator();
                const gain2 = newOrderSound.createGain();
                osc2.connect(gain2);
                gain2.connect(newOrderSound.destination);
                osc2.frequency.value = 1108;
                osc2.type = 'sine';
                gain2.gain.setValueAtTime(0.3, newOrderSound.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.001, newOrderSound.currentTime + 0.5);
                osc2.start(newOrderSound.currentTime);
                osc2.stop(newOrderSound.currentTime + 0.5);
            }, 200);

            // Third beep
            setTimeout(() => {
                const osc3 = newOrderSound.createOscillator();
                const gain3 = newOrderSound.createGain();
                osc3.connect(gain3);
                gain3.connect(newOrderSound.destination);
                osc3.frequency.value = 1320;
                osc3.type = 'sine';
                gain3.gain.setValueAtTime(0.3, newOrderSound.currentTime);
                gain3.gain.exponentialRampToValueAtTime(0.001, newOrderSound.currentTime + 0.5);
                osc3.start(newOrderSound.currentTime);
                osc3.stop(newOrderSound.currentTime + 0.5);
            }, 400);
        } catch (e) {
            console.log('Sound notification not available');
        }
    }

    // Request notification permission
    function requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Show browser notification
    function showBrowserNotification(order) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const customerName = order.customer?.name || 'Customer';
            const total = parseFloat(order.total_price || 0).toFixed(2);
            new Notification('🆕 New Order #' + order.id, {
                body: customerName + ' - $' + total + ' - ' + (order.orderItems?.length || order.order_items?.length || 0) + ' items',
                icon: '/favicon.svg',
                tag: 'order-' + order.id,
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderOrders(orders);
        updateStats(orders);
        requestNotificationPermission();
        fetchLatestOrders();
        setInterval(fetchLatestOrders, 5000);
    });

    // Helper to normalize items array from different key styles
    function getItems(order) {
        return order.items || order.order_items || order.orderItems || [];
    }

    function renderOrders(items) {
        let html = '';

        if (items.length === 0) {
            html = `
                <tr>
                    <td colspan="7" class="text-center py-12 text-slate-400">
                        <i class="fas fa-receipt text-4xl block mb-3 opacity-30"></i>
                        <p class="text-lg font-medium text-slate-500">No Orders Found</p>
                        <p class="text-sm">Start by creating a new order</p>
                    </td>
                </tr>
            `;
        } else {
            items.forEach((order, index) => {
                const displayStatus = getDisplayStatus(order);
                let statusClass, statusIcon;
                switch(displayStatus) {
                    case 'pending':
                        statusClass = 'amber';
                        statusIcon = 'fa-clock';
                        break;
                    case 'pending_payment':
                        statusClass = 'violet';
                        statusIcon = 'fa-wallet';
                        break;
                    case 'accepted':
                        statusClass = 'sky';
                        statusIcon = 'fa-handshake';
                        break;
                    case 'preparing':
                        statusClass = 'orange';
                        statusIcon = 'fa-fire';
                        break;
                    case 'ready':
                        statusClass = 'blue';
                        statusIcon = 'fa-check-circle';
                        break;
                    case 'completed':
                        statusClass = 'emerald';
                        statusIcon = 'fa-flag-checkered';
                        break;
                    default:
                        statusClass = 'amber';
                        statusIcon = 'fa-clock';
                }

                html += `
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="p-4 text-sm text-slate-400 font-medium">${index + 1}</td>

                        <td class="p-4">
                            <span class="font-bold text-slate-800">
                                <i class="fas fa-receipt text-emerald-400 mr-2"></i>#${order.id}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold text-xs">
                                    ${order.customer?.name ? order.customer.name.charAt(0).toUpperCase() : 'W'}
                                </div>
                                <span class="font-medium text-slate-700">
                                    ${order.customer?.name || 'Walk-in Customer'}
                                </span>
                            </div>
                        </td>

                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                ${getItems(order).length || 0} items
                            </span>
                        </td>

                        <td class="p-4 font-bold text-emerald-600">
                            $${parseFloat(order.total_price || 0).toFixed(2)}
                        </td>

                        <td class="p-4">
                            <span class="bg-${statusClass}-100 text-${statusClass}-700 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas ${statusIcon} mr-1"></i>
                                ${formatStatus(displayStatus)}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="/cashier/orders/${order.id}"
                                   class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                    <span class="hidden sm:inline">View</span>
                                </a>

                                <a href="/cashier/orders/${order.id}/receipt"
                                   class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-print"></i>
                                    <span class="hidden sm:inline">Receipt</span>
                                </a>

                                <button onclick="confirmDelete(${order.id})"
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

        document.getElementById('orderTable').innerHTML = html;
        document.getElementById('orderCount').innerHTML = `
            <i class="fas fa-circle text-[6px] text-emerald-400 mr-1.5 align-middle"></i>
            ${items.length} orders
        `;
    }

    function updateStats(orders) {
        document.getElementById('totalOrders').textContent = orders.length;

        const today = new Date().toDateString();
        const todayOrders = orders.filter(o => {
            if (!o.created_at) return false;
            return new Date(o.created_at).toDateString() === today;
        });
        document.getElementById('todayOrders').textContent = todayOrders.length;

        const pending = orders.filter(o => {
            return ['pending', 'accepted', 'preparing', 'ready'].includes(getDisplayStatus(o));
        });
        document.getElementById('pendingOrders').textContent = pending.length;

        const completed = orders.filter(o => {
            return o.status === 'completed';
        });
        document.getElementById('completedOrders').textContent = completed.length;
    }

    function formatStatus(status) {
        const labels = {
            pending_payment: 'Pending Payment',
            pending: 'Waiting Accept',
            accepted: 'Accepted',
            preparing: 'Preparing',
            ready: 'Ready',
            completed: 'Completed',
        };

        return labels[status] || status.charAt(0).toUpperCase() + status.slice(1);
    }

    function getDisplayStatus(order) {
        const orderStatus = order.status || 'pending';
        const paymentStatus = order.payment?.status || 'pending';

        if (orderStatus === 'pending_payment' && paymentStatus === 'paid') {
            return 'pending';
        }

        return orderStatus;
    }

    function fetchLatestOrders() {
        fetch('{{ route('cashier.orders.data') }}', {
            headers: {
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success || !Array.isArray(data.orders)) {
                return;
            }

            const latestOrders = data.orders;
            const newestOrderId = latestOrders.length ? latestOrders[0].id : null;

            if (latestOrders.length !== orders.length || newestOrderId !== lastOrderId) {
                const isNewOrder = newestOrderId && newestOrderId !== lastOrderId;
                const newestOrder = isNewOrder ? latestOrders[0] : null;

                orders = latestOrders;
                lastOrderId = newestOrderId;
                renderOrders(orders);
                updateStats(orders);

                if (isNewOrder && newestOrder) {
                    playNewOrderSound();
                    showBrowserNotification(newestOrder);
                    showToast('success', '🆕 New Order #' + newestOrder.id + ' from ' + (newestOrder.customer?.name || 'Customer'));
                }
            }
        })
        .catch(error => {
            console.error('Failed to refresh orders:', error);
        });
    }

    // Search
    document.getElementById('searchOrder').addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        const filtered = orders.filter(o =>
            o.id.toString().includes(keyword) ||
            o.customer?.name?.toLowerCase().includes(keyword)
        );
        renderOrders(filtered);
    });

    // Filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        const status = this.value;
        if (status === 'all') {
            renderOrders(orders);
        } else {
            const filtered = orders.filter(o => {
                return getDisplayStatus(o) === status;
            });
            renderOrders(filtered);
        }
    });

    // Delete order with confirmation
    function confirmDelete(orderId) {
        if (!confirm('Are you sure you want to delete this order? This will restore product stock and cannot be undone.')) {
            return;
        }

        const deleteUrl = `/cashier/orders/${orderId}`;
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
                // Remove from local array and re-render
                orders = orders.filter(o => o.id !== orderId);
                renderOrders(orders);
                updateStats(orders);
                showToast('success', 'Order deleted successfully');
            } else {
                showToast('error', data.message || 'Failed to delete order');
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
            showToast('error', 'Failed to delete order. Please try again.');
        });
    }

    // Simple toast notification
    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-[3000] px-6 py-4 rounded-xl shadow-lg text-white font-medium transition-all duration-300 ${
            type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'
        }`;
        toast.style.transform = 'translateX(120%)';
        toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${message}`;
        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 50);

        // Remove after 3 seconds
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
