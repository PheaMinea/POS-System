@extends('layouts.admin')

@section('title', 'Shop Settings')
@section('page_title', 'Shop Settings')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-8 border-b border-slate-100 pb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-gear text-indigo-600 mr-3"></i>Shop Settings
                </h1>
                <p class="text-slate-500 mt-1">
                    <i class="fas fa-info-circle mr-2"></i>Configure shop details, currency, logo, and VAT
                </p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Errors -->
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl p-4 flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-rose-500 text-xl mt-0.5"></i>
                <div>
                    <p class="font-medium">Please fix the following errors:</p>
                    <ul class="list-disc ml-5 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST"
              action="{{ route('admin.settings.update') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="grid md:grid-cols-2 gap-6">

                <!-- Shop Name -->
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-store text-indigo-500 mr-2"></i>Shop Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="shop_name"
                           value="{{ old('shop_name', $setting->shop_name ?? '') }}"
                           placeholder="Enter shop name"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Shop Email -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-envelope text-indigo-500 mr-2"></i>Shop Email
                    </label>
                    <input type="email"
                           name="shop_email"
                           value="{{ old('shop_email', $setting->shop_email ?? '') }}"
                           placeholder="Enter shop email address"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Shop Phone -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-phone text-indigo-500 mr-2"></i>Shop Phone
                    </label>
                    <input type="text"
                           name="shop_phone"
                           value="{{ old('shop_phone', $setting->shop_phone ?? '') }}"
                           placeholder="Enter shop phone number"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Currency Symbol -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-dollar-sign text-indigo-500 mr-2"></i>Currency Symbol <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="currency_symbol"
                           value="{{ old('currency_symbol', $setting->currency_symbol ?? '$') }}"
                           placeholder="e.g. $, ៛, €"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Currency Position -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-arrows-left-right text-indigo-500 mr-2"></i>Currency Position <span class="text-rose-500">*</span>
                    </label>
                    <select name="currency_position"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                        <option value="before" {{ old('currency_position', $setting->currency_position ?? 'before') == 'before' ? 'selected' : '' }}>
                            Before Amount (e.g. $10.00)
                        </option>
                        <option value="after" {{ old('currency_position', $setting->currency_position ?? 'before') == 'after' ? 'selected' : '' }}>
                            After Amount (e.g. 10.00 ៛)
                        </option>
                    </select>
                </div>

                <!-- VAT / Tax Percentage -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-percent text-indigo-500 mr-2"></i>VAT / Tax (%) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           step="0.01"
                           name="vat_percentage"
                           value="{{ old('vat_percentage', $setting->vat_percentage ?? '0.00') }}"
                           placeholder="Enter tax percentage"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Shop Logo -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-image text-indigo-500 mr-2"></i>Shop Logo
                    </label>
                    <input type="file"
                           name="shop_logo"
                           accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- Shop Address -->
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-map-location-dot text-indigo-500 mr-2"></i>Shop Address
                    </label>
                    <textarea name="shop_address"
                              rows="3"
                              placeholder="Enter shop address"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none resize-none">{{ old('shop_address', $setting->shop_address ?? '') }}</textarea>
                </div>

                <!-- Logo Preview (if exists) -->
                @if(isset($setting) && $setting->shop_logo)
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            Current Shop Logo
                        </label>
                        <div class="w-32 h-32 border border-slate-200 rounded-xl p-2 flex items-center justify-center bg-slate-50 overflow-hidden">
                            <img src="{{ asset('storage/' . $setting->shop_logo) }}"
                                 alt="Shop Logo"
                                 class="max-w-full max-h-full object-contain">
                        </div>
                    </div>
                @endif

            </div>

            <!-- Submit -->
            <div class="mt-8 border-t border-slate-100 pt-6 flex items-center gap-4">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-medium transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Settings
                </button>

                <a href="{{ route('admin.dashboard') }}"
                   class="text-slate-500 hover:text-slate-700 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
