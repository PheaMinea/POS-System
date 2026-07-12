@extends('layouts.admin')

@section('title', 'Supplier Detail')
@section('page_title', 'Supplier Detail')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
            <div class="flex items-center gap-3">
                <i class="fas fa-truck text-3xl opacity-50"></i>
                <div>
                    <h1 class="text-3xl font-bold">Supplier Detail</h1>
                    <p class="text-indigo-100 mt-1">
                        <i class="fas fa-info-circle mr-2"></i>View supplier information
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8 text-center">

            <!-- Image -->
            @if($supplier->image)
                <div class="relative inline-block">
                    <img src="{{ asset('storage/'.$supplier->image) }}"
                         alt="{{ $supplier->name }}"
                         class="w-40 h-40 rounded-2xl object-cover shadow-lg border-4 border-white">
                    <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-2 rounded-full shadow-lg">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                </div>
            @else
                <div class="w-40 h-40 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center shadow-lg border-4 border-white mx-auto">
                    <i class="fas fa-truck text-indigo-400 text-6xl opacity-50"></i>
                </div>
            @endif

            <!-- Name -->
            <h2 class="text-3xl font-bold text-slate-800 mt-6">
                <i class="fas fa-user text-indigo-500 mr-2"></i>{{ $supplier->name }}
            </h2>

            <!-- Phone -->
            <p class="text-slate-500 mt-2 flex items-center justify-center gap-2">
                <i class="fas fa-phone text-indigo-400"></i>
                {{ $supplier->phone }}
            </p>

            <!-- Address -->
            @if($supplier->address)
                <div class="mt-4 bg-slate-50 rounded-xl p-4 max-w-md mx-auto">
                    <p class="text-slate-600 flex items-center justify-center gap-2">
                        <i class="fas fa-map-pin text-indigo-400"></i>
                        {{ $supplier->address }}
                    </p>
                </div>
            @endif

            <!-- ID -->
            <p class="text-slate-400 text-sm mt-4">
                <i class="fas fa-id-card mr-2"></i>Supplier ID: <span class="font-medium text-slate-600">{{ $supplier->id }}</span>
            </p>

            <!-- Buttons -->
            <div class="flex flex-wrap justify-center gap-3 mt-8">
                <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                   class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-edit"></i>
                    Edit Supplier
                </a>

                <a href="{{ route('admin.suppliers.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-xl transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>

            <!-- Timestamps -->
            <div class="mt-8 pt-6 border-t border-slate-200">
                <div class="flex flex-wrap justify-center gap-6 md:gap-8 text-sm">
                    <div class="text-center">
                        <p class="text-slate-400 text-xs uppercase tracking-wider">
                            <i class="fas fa-calendar-plus mr-1"></i>Created At
                        </p>
                        <p class="font-medium text-slate-700 mt-1">
                            {{ $supplier->created_at->format('M d, Y') }}
                            <span class="text-slate-400 font-normal text-xs block">
                                {{ $supplier->created_at->format('h:i A') }}
                            </span>
                        </p>
                    </div>
                    <div class="text-center">
                        <p class="text-slate-400 text-xs uppercase tracking-wider">
                            <i class="fas fa-calendar-edit mr-1"></i>Last Updated
                        </p>
                        <p class="font-medium text-slate-700 mt-1">
                            {{ $supplier->updated_at->format('M d, Y') }}
                            <span class="text-slate-400 font-normal text-xs block">
                                {{ $supplier->updated_at->format('h:i A') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
    @media (max-width: 640px) {
        .w-40 {
            width: 120px;
            height: 120px;
        }
        .text-3xl {
            font-size: 1.5rem;
        }
    }
</style>

@endsection