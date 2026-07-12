@extends('layouts.admin')

@section('title', 'Suppliers Management')
@section('page_title', 'Suppliers')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-truck text-indigo-600 mr-3"></i>Suppliers
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Manage supplier information
            </p>
        </div>

        <a href="{{ route('admin.suppliers.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
            <i class="fas fa-plus-circle"></i>
            Add Supplier
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-indigo-600">{{ $suppliers->count() }}</p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Suppliers</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-emerald-600">
                {{ $suppliers->filter(function($s) { return $s->image; })->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">With Images</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-amber-600">
                {{ $suppliers->filter(function($s) { return !$s->image; })->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">No Image</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-sky-600">
                {{ $suppliers->where('products_count', '>', 0)->count() ?? 0 }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">With Products</p>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       placeholder="Search suppliers..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Supplier</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Phone</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Address</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($suppliers as $index => $supplier)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="p-4 text-sm text-slate-400 font-medium">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($supplier->image)
                                    <img src="{{ asset('storage/'.$supplier->image) }}"
                                         alt="{{ $supplier->name }}"
                                         class="w-12 h-12 rounded-full object-cover border-2 border-slate-200 group-hover:border-indigo-300 transition">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h4 class="font-semibold text-slate-800">{{ $supplier->name }}</h4>
                                    <p class="text-sm text-slate-400">
                                        <i class="fas fa-id-card mr-1"></i>ID: {{ $supplier->id }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">
                            <span class="flex items-center gap-2 text-slate-600">
                                <i class="fas fa-phone text-indigo-400"></i>
                                {{ $supplier->phone }}
                            </span>
                        </td>

                        <td class="p-4 text-slate-600 max-w-[200px] truncate">
                            <i class="fas fa-map-pin text-indigo-400 mr-2"></i>
                            {{ Str::limit($supplier->address, 30) }}
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.suppliers.show', $supplier) }}"
                                   class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                    <span class="hidden sm:inline">View</span>
                                </a>

                                <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                   class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-edit"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                        <i class="fas fa-trash"></i>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-16 text-slate-400">
                            <i class="fas fa-truck text-5xl block mb-4 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No Suppliers Found</p>
                            <p class="text-sm mt-1">Start by adding your first supplier</p>
                            <a href="{{ route('admin.suppliers.create') }}"
                               class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl transition">
                                <i class="fas fa-plus-circle mr-2"></i>Add Supplier
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($suppliers, 'links') && $suppliers->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>

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
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

@endsection