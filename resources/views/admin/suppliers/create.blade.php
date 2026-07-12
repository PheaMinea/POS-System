@extends('layouts.admin')

@section('title', 'Create Supplier')
@section('page_title', 'Create Supplier')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-plus-circle text-indigo-600 mr-3"></i>Create Supplier
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Add a new supplier to the system
            </p>
        </div>

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
              action="{{ route('admin.suppliers.store') }}"
              enctype="multipart/form-data">

            @csrf

            <div class="grid md:grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-user text-indigo-500 mr-2"></i>Supplier Name
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Enter supplier name"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none"
                           required>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-phone text-indigo-500 mr-2"></i>Phone Number
                    </label>
                    <input type="text"
                           name="phone"
                           value="{{ old('phone') }}"
                           placeholder="Enter phone number"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none"
                           required>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-map-pin text-indigo-500 mr-2"></i>Address
                    </label>
                    <textarea name="address"
                              rows="4"
                              placeholder="Enter supplier address"
                              class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">{{ old('address') }}</textarea>
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-image text-indigo-500 mr-2"></i>Supplier Image
                    </label>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
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
                    Save Supplier
                </button>

                <a href="{{ route('admin.suppliers.index') }}"
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
</script>

@endsection