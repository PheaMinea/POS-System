@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page_title', 'Edit Category')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-edit text-amber-500 mr-3"></i>Edit Category
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Update category information
            </p>
        </div>

        <!-- Form -->
        <form method="POST"
              action="{{ route('admin.categories.update', $category) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 rounded-xl p-4">
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">

                <!-- Name -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-tag text-indigo-500 mr-2"></i>Category Name
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $category->name) }}"
                           placeholder="Enter category name"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none"
                           required>
                </div>

                <!-- Image -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-image text-indigo-500 mr-2"></i>Category Image
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- Current Image -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">Current Image</label>
                    <img id="preview"
                         src="{{ $category->image ? asset('storage/'.$category->image) : '' }}"
                         class="w-32 h-32 rounded-xl object-cover border-2 border-slate-200 {{ $category->image ? '' : 'hidden' }}"
                         alt="Category Image Preview">
                </div>

            </div>

            <!-- Submit -->
            <div class="mt-8 flex items-center gap-4">
                <button type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-xl font-medium transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update Category
                </button>

                <a href="{{ route('admin.categories.index') }}"
                   class="text-slate-500 hover:text-slate-700 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<!-- Script -->
<script>
    document.getElementById('image').addEventListener('change', function() {
        const file = this.files[0];
        const preview = document.getElementById('preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection