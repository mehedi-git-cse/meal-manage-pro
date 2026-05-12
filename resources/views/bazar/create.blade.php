@extends('layouts.app')
@section('page-title', isset($bazarEntry) ? 'Edit Bazar Entry' : 'Add Bazar Entry')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">

    @php $editing = isset($bazarEntry); @endphp

    <div class="page-header">
        <div>
            <h2 class="page-title">{{ $editing ? 'Edit Bazar Entry' : 'Add Bazar Entry' }}</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('bazar.index') }}">Bazar</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>{{ $editing ? 'Edit' : 'Add' }}</span>
            </div>
        </div>
        <a href="{{ route('bazar.index') }}" class="btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST"
                  action="{{ $editing ? route('bazar.update', $bazarEntry) : route('bazar.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-5" data-loading>
                @csrf
                @if($editing) @method('PUT') @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="form-group">
                        <label class="form-label">Purchased By <span class="text-red-500">*</span></label>
                        <select name="user_id" class="form-select {{ $errors->has('user_id') ? 'border-red-500' : '' }}" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $bazarEntry->user_id ?? auth()->id()) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Entry Date <span class="text-red-500">*</span></label>
                        <input type="date" name="entry_date" value="{{ old('entry_date', $bazarEntry->entry_date?->format('Y-m-d') ?? today()->format('Y-m-d')) }}"
                               max="{{ today()->format('Y-m-d') }}" class="form-input" required>
                        @error('entry_date')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="form-label">Item Name <span class="text-red-500">*</span></label>
                        <input type="text" name="item_name" value="{{ old('item_name', $bazarEntry->item_name ?? '') }}"
                               class="form-input {{ $errors->has('item_name') ? 'border-red-500' : '' }}"
                               placeholder="e.g., Rice, Vegetables, Fish..." required>
                        @error('item_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $bazarEntry->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->icon ?? '' }} {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">No Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id', $bazarEntry->vendor_id ?? '') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Total Amount ({{ config('meal.currency_symbol') }}) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', $bazarEntry->amount ?? '') }}"
                               class="form-input {{ $errors->has('amount') ? 'border-red-500' : '' }}"
                               step="0.01" min="0.01" placeholder="0.00" required>
                        @error('amount')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <div class="flex gap-2">
                            <input type="number" name="quantity" value="{{ old('quantity', $bazarEntry->quantity ?? 1) }}"
                                   class="form-input w-24" step="0.1" min="0">
                            <input type="text" name="unit" value="{{ old('unit', $bazarEntry->unit ?? '') }}"
                                   class="form-input flex-1" placeholder="kg, L, piece...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Price ({{ config('meal.currency_symbol') }})</label>
                        <input type="number" name="unit_price" value="{{ old('unit_price', $bazarEntry->unit_price ?? '') }}"
                               class="form-input" step="0.01" min="0" placeholder="Optional">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Receipt Number</label>
                        <input type="text" name="receipt_number" value="{{ old('receipt_number', $bazarEntry->receipt_number ?? '') }}"
                               class="form-input" placeholder="Optional">
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-input resize-none"
                                  placeholder="Additional details...">{{ old('description', $bazarEntry->description ?? '') }}</textarea>
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="form-label">Receipt Image</label>
                        @if($editing && $bazarEntry->receipt_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($bazarEntry->receipt_image) }}" class="h-20 rounded-lg" alt="Receipt">
                        </div>
                        @endif
                        <input type="file" name="receipt_image" accept="image/*" class="form-input text-sm">
                        <p class="text-xs text-gray-400 mt-1">Max 4MB. JPG, PNG, WEBP</p>
                        @error('receipt_image')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $editing ? 'Update Entry' : 'Save Entry' }}
                    </button>
                    <a href="{{ route('bazar.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
