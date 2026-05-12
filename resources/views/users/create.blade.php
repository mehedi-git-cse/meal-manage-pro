@extends('layouts.app')
@section('page-title', isset($user) ? 'Edit User' : 'Add User')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">

    @php $editing = isset($user); @endphp

    <div class="page-header">
        <div>
            <h2 class="page-title">{{ $editing ? 'Edit User' : 'Add New User' }}</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('users.index') }}">Users</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>{{ $editing ? 'Edit' : 'Add' }}</span>
            </div>
        </div>
        <a href="{{ route('users.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST"
                  action="{{ $editing ? route('users.update', $user) : route('users.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-5" data-loading>
                @csrf
                @if($editing) @method('PUT') @endif

                <!-- Avatar -->
                <div class="flex items-center gap-5">
                    <div class="relative" x-data="{ preview: '{{ $editing ? $user->avatar_url : '' }}' }">
                        <img :src="preview || 'https://ui-avatars.com/api/?name=New+User&background=3b82f6&color=fff&size=80'"
                             class="w-20 h-20 rounded-full object-cover ring-4 ring-gray-100 dark:ring-gray-700" alt="Avatar">
                        <label class="absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center cursor-pointer shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <input type="file" name="avatar" accept="image/*" class="hidden"
                                   @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Profile Photo</p>
                        <p class="text-xs text-gray-400 mt-0.5">JPG, PNG or GIF. Max 2MB</p>
                        @error('avatar')<p class="form-error mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group sm:col-span-2">
                        <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                               class="form-input {{ $errors->has('name') ? 'border-red-500' : '' }}" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
                               class="form-input {{ $errors->has('email') ? 'border-red-500' : '' }}" required>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                               class="form-input" placeholder="+880...">
                        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Employee ID</label>
                        <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id ?? '') }}"
                               class="form-input {{ $errors->has('employee_id') ? 'border-red-500' : '' }}">
                        @error('employee_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" value="{{ old('department', $user->department ?? '') }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation', $user->designation ?? '') }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role <span class="text-red-500">*</span></label>
                        <select name="role" class="form-select {{ $errors->has('role') ? 'border-red-500' : '' }}" required>
                            <option value="">Select role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $editing ? $user->roles->first()?->name : '') == $role->name ? 'selected' : '' }}>
                                    {{ $role->display_name ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('status', $user->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password {{ $editing ? '(leave blank to keep)' : '' }} {{ !$editing ? '<span class="text-red-500">*</span>' : '' }}</label>
                        <input type="password" name="password"
                               class="form-input {{ $errors->has('password') ? 'border-red-500' : '' }}"
                               {{ !$editing ? 'required' : '' }}
                               placeholder="{{ $editing ? '••••••••' : 'Min 8 chars, mixed case + number' }}">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="meal_active" id="mealActive" value="1"
                           class="w-4 h-4 rounded text-blue-500 border-gray-300"
                           {{ old('meal_active', $user->meal_active ?? true) ? 'checked' : '' }}>
                    <label for="mealActive" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                        Meal subscription active
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $editing ? 'Update User' : 'Create User' }}
                    </button>
                    <a href="{{ route('users.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
