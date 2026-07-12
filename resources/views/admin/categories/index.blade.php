@extends('layouts.admin')

@section('title', 'Categories Management')
@section('page_title', 'Categories')

@section('content')

<div class="bg-white rounded-2xl shadow-sm">

    <!-- Header -->
    <div class="p-6 border-b border-slate-200 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-tags text-indigo-600 mr-3"></i>Categories
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Manage product categories
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Search -->
            <div class="relative hidden sm:block">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text"
                       placeholder="Search categories..."
                       class="pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none w-48 md:w-56">
            </div>

            <a href="{{ route('admin.categories.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
                <i class="fas fa-plus-circle"></i>
                Add Category
            </a>
        </div>
    </div>

    <!-- Body -->
    <div class="p-6">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="ml-auto text-emerald-400 hover:text-emerald-600" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Stats Summary -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-indigo-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ $categories->count() }}</p>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Categories</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-emerald-600">
                    {{ $categories->filter(function($c) { return $c->image; })->count() }}
                </p>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">With Images</p>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-amber-600">
                    {{ $categories->filter(function($c) { return !$c->image; })->count() }}
                </p>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">No Image</p>
            </div>
            <div class="bg-rose-50 rounded-xl p-4 text-center">
                <p class="text-2xl font-bold text-rose-600">
                    {{ $categories->where('products_count', '>', 0)->count() ?? 0 }}
                </p>
                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">With Products</p>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Image</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Products</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Created</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($categories as $index => $category)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="py-4 text-sm text-slate-400 font-medium">
                            {{ $loop->iteration }}
                        </td>

                        <td class="py-4">
                            @if($category->image)
                                <img src="/storage/{{ $category->image }}"
                                     alt="{{ $category->name }}"
                                     class="w-14 h-14 rounded-xl object-cover border-2 border-slate-200 group-hover:border-indigo-300 transition">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center text-slate-500">
                                    <i class="fas fa-image text-2xl opacity-50"></i>
                                </div>
                            @endif
                        </td>

                        <td class="py-4 font-medium text-slate-700">
                            <i class="fas fa-folder-open text-indigo-400 mr-2"></i>{{ $category->name }}
                        </td>

                        <td class="py-4">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $category->products_count ?? 0 }}
                            </span>
                        </td>

                        <td class="py-4 text-sm text-slate-500">
                            {{ $category->created_at->format('M d, Y') }}
                        </td>

                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                    <span class="hidden sm:inline">View</span>
                                </a>

                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-edit"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        <td colspan="6" class="text-center py-16 text-slate-400">
                            <i class="fas fa-folder-open text-5xl block mb-4 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No Categories Found</p>
                            <p class="text-sm mt-1">Start by adding your first category</p>
                            <a href="{{ route('admin.categories.create') }}"
                               class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl transition">
                                <i class="fas fa-plus-circle mr-2"></i>Add Category
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($categories, 'links') && $categories->hasPages())
            <div class="mt-6 pt-4 border-t border-slate-200">
                {{ $categories->links() }}
            </div>
        @endif

        <!-- Footer Info -->
        @php
            $categoryFirst = method_exists($categories, 'firstItem') ? $categories->firstItem() : ($categories->count() ? 1 : 0);
            $categoryLast = method_exists($categories, 'lastItem') ? $categories->lastItem() : $categories->count();
            $categoryTotal = method_exists($categories, 'total') ? $categories->total() : $categories->count();
        @endphp
        <div class="mt-4 text-sm text-slate-400 flex justify-between items-center">
            <span>
                <i class="fas fa-info-circle mr-1"></i>
                Showing {{ $categoryFirst }} to {{ $categoryLast }} of {{ $categoryTotal }} categories
            </span>
            <span>
                <i class="fas fa-clock mr-1"></i>
                Last updated: {{ now()->format('M d, Y h:i A') }}
            </span>
        </div>

    </div>

</div>

<!-- Extra Styles -->
<style>
    /* Scrollbar */
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

    /* Table row animation */
    tbody tr {
        transition: all 0.2s ease;
    }

    /* Status badge */
    .badge-count {
        font-variant-numeric: tabular-nums;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        .table-responsive td,
        .table-responsive th {
            padding: 0.75rem 0.5rem;
        }
        .table-responsive .action-buttons {
            flex-direction: column;
            gap: 0.25rem;
        }
        .table-responsive .action-buttons a,
        .table-responsive .action-buttons button {
            width: 100%;
            justify-content: center;
        }
    }
</style>

@endsection