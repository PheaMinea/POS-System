@extends('layouts.admin')

@section('title', 'Users Management')
@section('page_title', 'Users Management')

@section('content')

<div class="bg-white rounded-2xl shadow-sm">

    <!-- Header -->
    <div class="p-6 border-b border-slate-200 flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-users text-indigo-600 mr-3"></i>Users Management
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Manage system users and their roles
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            Add User
        </a>
    </div>

    <!-- Body -->
    <div class="p-6">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">User</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Role</th>
                        <th class="text-left py-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                @if($user->image)
                                    <img src="{{ asset('storage/'.$user->image) }}"
                                         class="w-12 h-12 rounded-full object-cover border-2 border-slate-200"
                                         alt="{{ $user->name }}">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h4 class="font-semibold text-slate-800">{{ $user->name }}</h4>
                                    <p class="text-slate-400 text-sm">
                                        <i class="fas fa-id-card mr-1"></i>ID: {{ $user->id }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 text-slate-600">
                            <i class="fas fa-envelope text-slate-400 mr-2"></i>{{ $user->email }}
                        </td>

                        <td class="py-4">
                            @if($user->role == 'admin')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-crown mr-1"></i>Admin
                                </span>
                            @else
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-user mr-1"></i>Cashier
                                </span>
                            @endif
                        </td>

                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition text-sm font-medium flex items-center gap-2">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>

                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-lg transition text-sm font-medium flex items-center gap-2">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-slate-400">
                            <i class="fas fa-users-slash text-4xl block mb-3 opacity-30"></i>
                            <p class="text-lg font-medium">No Users Found</p>
                            <p class="text-sm">Start by adding your first user</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($users, 'links'))
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>

<!-- Extra Styles -->
<style>
    .table-row-hover:hover {
        background-color: #f8fafc;
    }

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