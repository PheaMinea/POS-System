@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    @php
        $profileImage = null;

        if ($user?->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->image)) {
            $profileImage = asset('storage/' . $user->image);
        } elseif ($user?->avatar) {
            $profileImage = $user->avatar;
        }
    @endphp

    <section class="bg-slate-50 py-12 md:py-16">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-10 text-white md:px-10">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                        @if($profileImage)
                            <img
                                src="{{ $profileImage }}"
                                alt="{{ $user->name ?? 'User' }}"
                                class="h-24 w-24 rounded-full border-4 border-white/30 object-cover shadow-lg"
                            >
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white/30 bg-white/20 text-4xl font-bold shadow-lg">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <div>
                            <p class="text-sm font-medium uppercase tracking-wide text-blue-100">
                                My Profile
                            </p>
                            <h1 class="mt-1 text-3xl font-bold">
                                {{ $user->name ?? 'User' }}
                            </h1>
                            <p class="mt-2 text-blue-100">
                                {{ $user->email ?? 'No email address' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2 md:p-10">
                    <div class="rounded-xl border border-slate-200 p-5">
                        <div class="mb-4 flex items-center gap-3">
                            <i class="fas fa-id-card text-blue-600"></i>
                            <h2 class="text-lg font-semibold text-slate-900">
                                Account Information
                            </h2>
                        </div>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Name</dt>
                                <dd class="mt-1 text-slate-900">{{ $user->name ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-slate-900">{{ $user->email ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Role</dt>
                                <dd class="mt-1 capitalize text-slate-900">{{ $user->role ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-5">
                        <div class="mb-4 flex items-center gap-3">
                            <i class="fas fa-address-book text-blue-600"></i>
                            <h2 class="text-lg font-semibold text-slate-900">
                                Contact Details
                            </h2>
                        </div>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Phone</dt>
                                <dd class="mt-1 text-slate-900">{{ $user->phone ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Address</dt>
                                <dd class="mt-1 text-slate-900">{{ $user->address ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Joined</dt>
                                <dd class="mt-1 text-slate-900">
                                    {{ optional($user->created_at)->format('M d, Y') ?? '-' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
