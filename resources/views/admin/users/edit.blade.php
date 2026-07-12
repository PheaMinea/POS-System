@extends('layouts.admin')

@section('title', 'Edit User')
@section('page_title', 'Edit User')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-user-edit text-amber-500 mr-3"></i>Edit User
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Update user information
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
              action="{{ route('admin.users.update', $user) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-user text-indigo-500 mr-2"></i>Full Name
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Enter full name"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-envelope text-indigo-500 mr-2"></i>Email Address
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ old('email', $user->email) }}"
                           placeholder="Enter email address"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Role -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-user-tag text-indigo-500 mr-2"></i>Role
                    </label>
                    <select name="role"
                            class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                            <i class="fas fa-crown mr-2"></i>Admin
                        </option>
                        <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>
                            <i class="fas fa-user mr-2"></i>Cashier
                        </option>
                    </select>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-key text-indigo-500 mr-2"></i>New Password
                    </label>
                    <input type="password"
                           name="password"
                           autocomplete="new-password"
                           placeholder="Leave blank to keep current"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-check-circle text-indigo-500 mr-2"></i>Confirm New Password
                    </label>
                    <input type="password"
                           name="password_confirmation"
                           autocomplete="new-password"
                           placeholder="Confirm new password"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                </div>

                <!-- Profile Image -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-slate-700">
                        <i class="fas fa-image text-indigo-500 mr-2"></i>Profile Image
                    </label>
                    <input type="file"
                           name="image"
                           accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <!-- Current Image -->
                <div class="flex items-end">
                    @if($user->image)
                        <div class="flex flex-col gap-2">
                            <span class="text-sm font-semibold text-slate-700">Current Image</span>
                            <img src="{{ asset('storage/'.$user->image) }}"
                                 class="w-24 h-24 rounded-xl object-cover border-2 border-slate-200"
                                 alt="{{ $user->name }}">
                        </div>
                    @endif
                </div>

            </div>

            <!-- Submit -->
            <div class="mt-8 flex items-center gap-4">
                <button type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-xl font-medium transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update User
                </button>

                <a href="{{ route('admin.users.index') }}"
                   class="text-slate-500 hover:text-slate-700 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection