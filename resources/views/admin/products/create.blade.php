@extends('layouts.admin')

@section('title', 'Create Product')
@section('page_title', 'Create Product')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-plus-circle text-indigo-600 mr-3"></i>Create Product
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Add a new product to inventory
            </p>
        </div>

        <!-- Form -->
        <form id="productForm" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="grid md:grid-cols-2 gap-6">

                <!-- Category -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-tags text-indigo-500 mr-2"></i>Category
                    </label>
                    <select id="category_id"
                            name="category_id"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none @error('category_id') border-rose-400 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-tag text-indigo-500 mr-2"></i>Product Name
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Enter product name"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none @error('name') border-rose-400 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-dollar-sign text-indigo-500 mr-2"></i>Price
                    </label>
                    <input type="number"
                           id="price"
                           name="price"
                           value="{{ old('price') }}"
                           step="0.01"
                           placeholder="0.00"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none @error('price') border-rose-400 @enderror">
                    @error('price')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stock -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-boxes text-indigo-500 mr-2"></i>Stock
                    </label>
                    <input type="number"
                           id="stock"
                           name="stock"
                           value="{{ old('stock') }}"
                           placeholder="0"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none @error('stock') border-rose-400 @enderror">
                    @error('stock')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-image text-indigo-500 mr-2"></i>Product Image
                    </label>
                    <input type="file"
                           id="image"
                           name="image"
                           accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('image') border-rose-400 @enderror">
                    @error('image')
                        <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview -->
                <div class="md:col-span-2" id="previewContainer">
                    <img id="preview"
                         class="w-40 h-40 rounded-xl object-cover border-2 border-slate-200 hidden">
                </div>

            </div>

            <!-- Submit -->
            <div class="mt-8 flex items-center gap-4">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-medium transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Save Product
                </button>

                <a href="{{ route('admin.products.index') }}"
                   class="text-slate-500 hover:text-slate-700 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<script>
    // Image Preview
    document.getElementById('image').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('preview');
        const container = document.getElementById('previewContainer');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Form submit uses standard web POST to admin.products.store
</script>

@endsection