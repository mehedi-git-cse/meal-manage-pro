@extends('layouts.app')
@section('page-title', 'Add Meal Entry')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Add Meal Entry</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('meals.index') }}">Meal Entries</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Add</span>
            </div>
        </div>
        <a href="{{ route('meals.index') }}" class="btn-secondary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900 dark:text-white">Meal Entry Details</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('meals.store') }}" class="space-y-5" data-loading>
                @csrf

                <div class="form-group">
                    <label class="form-label">User <span class="text-red-500">*</span></label>
                    @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                        <select name="user_id" class="form-select {{ $errors->has('user_id') ? 'border-red-500' : '' }}" required>
                            <option value="">Select a user...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} {{ $user->employee_id ? '('.$user->employee_id.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <div class="form-input bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 cursor-not-allowed">
                            {{ auth()->user()->name }}
                        </div>
                    @endif
                    @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="form-label">Meal Date <span class="text-red-500">*</span></label>
                        <input type="date" name="meal_date" value="{{ old('meal_date', $today) }}"
                               class="form-input {{ $errors->has('meal_date') ? 'border-red-500' : '' }}"
                               max="{{ $today }}" required>
                        @error('meal_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meal Type <span class="text-red-500">*</span></label>
                        <select name="meal_type" class="form-select {{ $errors->has('meal_type') ? 'border-red-500' : '' }}" required>
                            @foreach(config('meal.meal_types') as $key => $label)
                                <option value="{{ $key }}" {{ old('meal_type', 'lunch') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('meal_type')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Meal Rate <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="meal_rate" value="{{ old('meal_rate') }}"
                               class="form-input pl-8 {{ $errors->has('meal_rate') ? 'border-red-500' : '' }}"
                               step="0.01" min="0" placeholder="0.00" required>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Rate per meal unit.</p>
                    @error('meal_rate')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Quantity <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-3">
                        @foreach([0.5 => 'Half', 1 => 'Full (1)', 1.5 => 'Full', 2 => 'Double (2)'] as $val => $label)
                        <label class="flex-1">
                            <input type="radio" name="quantity" value="{{ $val }}" class="sr-only peer" {{ old('quantity', 1) == $val ? 'checked' : '' }}>
                            <div class="text-center py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-600 text-sm font-medium cursor-pointer
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:text-blue-700 dark:peer-checked:text-blue-400
                                        hover:border-gray-300 transition-colors">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('quantity')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group" x-data="{ isGuest: {{ old('is_guest') ? 'true' : 'false' }} }">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_guest" value="1" x-model="isGuest"
                                   class="w-4 h-4 rounded text-blue-500 border-gray-300">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">This is a guest meal</span>
                        </label>
                    </div>

                    <div x-show="isGuest" x-transition class="mt-3">
                        <label class="form-label">Guest Name</label>
                        <input type="text" name="guest_name" value="{{ old('guest_name') }}"
                               class="form-input" placeholder="Enter guest's name">
                        @error('guest_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Note (Optional)</label>
                    <textarea name="note" rows="3" class="form-input resize-none"
                              placeholder="Any additional note...">{{ old('note') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Entry
                    </button>
                    <a href="{{ route('meals.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
