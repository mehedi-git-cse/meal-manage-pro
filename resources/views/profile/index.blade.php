@extends('layouts.app')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">My Profile</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Profile</span>
            </div>
        </div>
    </div>

    <!-- Profile Info Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Profile Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5" data-loading>
                @csrf @method('PUT')

                <!-- Avatar -->
                <div class="flex items-center gap-5" x-data="{ preview: '{{ auth()->user()->avatar_url }}' }">
                    <img :src="preview" class="w-20 h-20 rounded-full object-cover ring-4 ring-gray-100 dark:ring-gray-700" alt="">
                    <div>
                        <label class="btn-secondary cursor-pointer inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                            Change Photo
                            <input type="file" name="avatar" accept="image/*" class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="text-xs text-gray-400 mt-1">Max 2MB. JPG, PNG</p>
                        @error('avatar')<p class="form-error mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group sm:col-span-2">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" required>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" value="{{ old('department', auth()->user()->department) }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation', auth()->user()->designation) }}" class="form-input">
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Update Profile
                </button>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Change Password</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-5" data-loading>
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-input max-w-md" required>
                    @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" required>
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="stat-card text-center">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->mealEntries()->count() }}</div>
            <div class="text-sm text-gray-400 mt-1">Total Meal Entries</div>
        </div>
        <div class="stat-card text-center">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->bazarEntries()->count() }}</div>
            <div class="text-sm text-gray-400 mt-1">Bazar Entries</div>
        </div>
        <div class="stat-card text-center">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ config('meal.currency_symbol') }}{{ number_format(auth()->user()->bazarEntries()->sum('amount'), 0) }}
            </div>
            <div class="text-sm text-gray-400 mt-1">Bazar Contribution</div>
        </div>
    </div>

    <!-- Account Info -->
    <div class="card">
        <div class="card-body">
            <dl class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-gray-400 text-xs uppercase tracking-wide">Employee ID</dt>
                    <dd class="text-gray-900 dark:text-white font-medium mt-0.5">{{ auth()->user()->employee_id ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs uppercase tracking-wide">Role</dt>
                    <dd class="mt-0.5">
                        @foreach(auth()->user()->roles as $role)
                            <span class="badge text-xs" style="background-color: {{ $role->color ?? '#6b7280' }}20; color: {{ $role->color ?? '#6b7280' }}">
                                {{ $role->display_name ?? $role->name }}
                            </span>
                        @endforeach
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs uppercase tracking-wide">Last Login</dt>
                    <dd class="text-gray-900 dark:text-white font-medium mt-0.5">
                        {{ auth()->user()->last_login_at?->format('d M Y, h:i A') ?? 'N/A' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs uppercase tracking-wide">Member Since</dt>
                    <dd class="text-gray-900 dark:text-white font-medium mt-0.5">{{ auth()->user()->created_at->format('d M Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
