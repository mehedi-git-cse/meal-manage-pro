@extends('layouts.app')
@section('page-title', 'Edit Meal Entry')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Edit Meal Entry</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('meals.index') }}">Meal Entries</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Edit</span>
            </div>
        </div>
        <a href="{{ route('meals.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Edit Entry</h3>
            <span class="badge {{ $mealEntry->meal_type_badge_color }}">{{ $mealEntry->meal_type_label }}</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('meals.update', $mealEntry) }}" class="space-y-5" data-loading>
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">User <span class="text-red-500">*</span></label>
                    <select name="user_id" class="form-select {{ $errors->has('user_id') ? 'border-red-500' : '' }}" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $mealEntry->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->employee_id ? '('.$user->employee_id.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="form-label">Meal Date <span class="text-red-500">*</span></label>
                        <input type="date" name="meal_date" value="{{ old('meal_date', $mealEntry->meal_date->format('Y-m-d')) }}"
                               class="form-input {{ $errors->has('meal_date') ? 'border-red-500' : '' }}"
                               max="{{ today()->format('Y-m-d') }}" required>
                        @error('meal_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meal Type <span class="text-red-500">*</span></label>
                        <select name="meal_type" class="form-select" required>
                            @foreach(config('meal.meal_types') as $key => $label)
                                <option value="{{ $key }}" {{ old('meal_type', $mealEntry->meal_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('meal_type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-3">
                        @foreach([0.5 => 'Half', 1 => 'Full (1)', 1.5 => 'Full', 2 => 'Double (2)'] as $val => $label)
                        <label class="flex-1">
                            <input type="radio" name="quantity" value="{{ $val }}" class="sr-only peer" {{ old('quantity', $mealEntry->quantity) == $val ? 'checked' : '' }}>
                            <div class="text-center py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-600 text-sm font-medium cursor-pointer
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:text-blue-700
                                        hover:border-gray-300 transition-colors">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('quantity')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Meal Rate <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium select-none">{{ config('meal.currency_symbol') }}</span>
                        <input type="number" name="meal_rate" value="{{ old('meal_rate', $mealEntry->meal_rate) }}"
                               class="form-input pl-10 {{ $errors->has('meal_rate') ? 'border-red-500' : '' }}"
                               step="0.01" min="0" placeholder="0.00" required>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Rate per meal unit.</p>
                    @error('meal_rate')<p class="form-error">{{ $message }}</p>@enderror
                </div>
           
                @role('super_admin|manager')
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ old('status', $mealEntry->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $mealEntry->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $mealEntry->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                @endrole

                <div class="form-group" x-data="{ isGuest: {{ old('is_guest', $mealEntry->is_guest) ? 'true' : 'false' }} }">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_guest" value="1" x-model="isGuest"
                               class="w-4 h-4 rounded text-blue-500 border-gray-300">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Guest meal</span>
                    </label>
                    <div x-show="isGuest" x-transition class="mt-3">
                        <input type="text" name="guest_name" value="{{ old('guest_name', $mealEntry->guest_name) }}"
                               class="form-input" placeholder="Guest name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Note</label>
                    <textarea name="note" rows="3" class="form-input resize-none">{{ old('note', $mealEntry->note) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">Update Entry</button>
                    <a href="{{ route('meals.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
