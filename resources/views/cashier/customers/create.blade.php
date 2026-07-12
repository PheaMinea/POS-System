@extends('layouts.cashier')

@section('title', 'Add Customer')
@section('page_title', 'Add Customer')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-user-plus text-emerald-500"></i>
                Add Customer
            </h2>
            <p class="text-slate-400 text-sm mt-1">
                <i class="fas fa-info-circle mr-1"></i>
                Enter customer details to create a new profile
            </p>
        </div>

        <form id="customerForm" action="{{ route('cashier.customers.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-user text-emerald-400 mr-2"></i>Full Name
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Enter customer name"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none @error('name') border-rose-400 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-phone text-emerald-400 mr-2"></i>Phone Number
                </label>
                <input type="text"
                       id="phone"
                       name="phone"
                       value="{{ old('phone') }}"
                       placeholder="Enter phone number"
                       class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none @error('phone') border-rose-400 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div>
                <label class="block mb-2 text-sm font-semibold text-slate-700">
                    <i class="fas fa-map-pin text-emerald-400 mr-2"></i>Address
                </label>
                <textarea id="address"
                          name="address"
                          rows="4"
                          placeholder="Enter customer address"
                          class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none @error('address') border-rose-400 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit"
                        class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-lg shadow-emerald-500/25">
                    <i class="fas fa-save"></i>
                    Save Customer
                </button>

                <a href="{{ route('cashier.customers.index') }}"
                   class="text-slate-500 hover:text-slate-700 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
            </div>

        </form>

    </div>

</div>

</script>

@endsection