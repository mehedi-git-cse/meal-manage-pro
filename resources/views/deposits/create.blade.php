@extends('layouts.app')
@section('page-title', 'Add Deposit')

@section('content')
<div class="max-w-xl mx-auto animate-fade-in space-y-5">

    <div class="page-header">
        <div>
            <h2 class="page-title">Add Deposit</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('deposits.index') }}">Deposits</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Add</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('deposits.store') }}" class="space-y-5">
                @csrf

                @if($isAdmin)
                <div class="form-group">
                    <label class="form-label">User <span class="text-red-500">*</span></label>
                    <select name="user_id" class="form-select @error('user_id') border-red-400 @enderror" required>
                        <option value="">Select User</option>
                        @foreach($users as $u)
                            <option value="{{ encryptId($u->id) }}" {{ old('user_id') == encryptId($u->id) ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Amount ({{ config('meal.currency_symbol') }}) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">{{ config('meal.currency_symbol') }}</span>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                               class="form-input pl-8 @error('amount') border-red-400 @enderror"
                               placeholder="0.00" step="0.01" min="1" required>
                    </div>
                    @error('amount')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Deposit Date <span class="text-red-500">*</span></label>
                    <input type="date" name="deposit_date" value="{{ old('deposit_date', date('Y-m-d')) }}"
                           class="form-input @error('deposit_date') border-red-400 @enderror" required>
                    @error('deposit_date')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Note <span class="text-gray-400 text-xs">(optional)</span></label>
                    <input type="text" name="note" value="{{ old('note') }}"
                           class="form-input @error('note') border-red-400 @enderror"
                           placeholder="e.g. Monthly contribution">
                    @error('note')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1">Save Deposit</button>
                    <a href="{{ route('deposits.index') }}" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
