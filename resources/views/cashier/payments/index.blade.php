@extends('layouts.cashier')

@section('title', 'Payments')
@section('page_title', 'Payments')

@section('content')

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-200 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-blue-500"></i>
                Payment Records
            </h2>
            <p class="text-sm text-slate-400 mt-1">Track cashier payments and Bakong status</p>
        </div>

        <a href="{{ route('cashier.pos.index') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 rounded-xl font-medium transition flex items-center gap-2">
            <i class="fas fa-cash-register"></i>
            New Sale
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Reference</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Method</th>
                    <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    <th class="p-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Amount</th>
                    <th class="p-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    @php
                        $statusClass = match ($payment->status) {
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'failed' => 'bg-rose-100 text-rose-700',
                            default => 'bg-amber-100 text-amber-700',
                        };
                    @endphp
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                        <td class="p-4">
                            <div class="font-semibold text-slate-700">
                                {{ $payment->reference_no ?? 'INV-' . str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-xs text-slate-400">Order #{{ $payment->order_id }}</div>
                        </td>
                        <td class="p-4 text-slate-600">
                            {{ $payment->order?->customer?->name ?? 'Walk-in Customer' }}
                        </td>
                        <td class="p-4">
                            <span class="font-medium text-slate-700">{{ ucfirst($payment->payment_method) }}</span>
                        </td>
                        <td class="p-4">
                            <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-semibold">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-bold text-blue-600">
                            ${{ number_format($payment->amount, 2) }}
                        </td>
                        <td class="p-4 text-right text-sm text-slate-400">
                            {{ $payment->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-slate-400">
                            <i class="fas fa-receipt text-4xl block mb-3 opacity-30"></i>
                            <p class="font-medium text-slate-500">No payments found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection