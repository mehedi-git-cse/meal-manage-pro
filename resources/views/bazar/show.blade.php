@extends('layouts.app')
@section('page-title', 'Bazar Entry Details')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">Bazar Entry #{{ encryptId($bazarEntry->id) }}</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('bazar.index') }}">Bazar</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>#{{ encryptId($bazarEntry->id) }}</span>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('bazar.edit', $bazarEntry) }}" class="btn-secondary">Edit</a>
            <a href="{{ route('bazar.index') }}" class="btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-3">
                <h3 class="font-semibold text-gray-900 dark:text-white text-lg">{{ $bazarEntry->item_name }}</h3>
                @if($bazarEntry->is_verified)
                    <span class="badge-success">Verified</span>
                @else
                    <span class="badge-warning">Unverified</span>
                @endif
            </div>
            @role('super_admin|manager')
            @if(!$bazarEntry->is_verified)
            <form method="POST" action="{{ route('bazar.verify', $bazarEntry) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn-success btn-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Verify
                </button>
            </form>
            @endif
            @endrole
        </div>

        <div class="card-body space-y-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Purchased By</p>
                    <div class="flex items-center gap-2 mt-1">
                        <img src="{{ $bazarEntry->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $bazarEntry->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $bazarEntry->user->employee_id }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Entry Date</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $bazarEntry->entry_date->format('d M, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Amount</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ config('meal.currency_symbol') }}{{ number_format($bazarEntry->amount, 2) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Quantity</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-1">
                        {{ $bazarEntry->quantity ?? '—' }} {{ $bazarEntry->unit ?? '' }}
                    </p>
                </div>
                @if($bazarEntry->unit_price)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Unit Price</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-1">
                        {{ config('meal.currency_symbol') }}{{ number_format($bazarEntry->unit_price, 2) }}
                    </p>
                </div>
                @endif
                @if($bazarEntry->category)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Category</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-1">
                        {{ $bazarEntry->category->icon ?? '' }} {{ $bazarEntry->category->name }}
                    </p>
                </div>
                @endif
                @if($bazarEntry->vendor)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Vendor</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $bazarEntry->vendor->name }}</p>
                </div>
                @endif
                @if($bazarEntry->receipt_number)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Receipt #</p>
                    <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $bazarEntry->receipt_number }}</p>
                </div>
                @endif
            </div>

            @if($bazarEntry->description)
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-1">Description</p>
                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $bazarEntry->description }}</p>
            </div>
            @endif

            @if($bazarEntry->receipt_image)
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-2">Receipt Image</p>
                <img src="{{ Storage::url($bazarEntry->receipt_image) }}" class="max-h-64 rounded-xl border border-gray-200 dark:border-gray-700" alt="Receipt">
            </div>
            @endif

            @if($bazarEntry->is_verified && $bazarEntry->verifiedBy)
            <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4">
                <div class="flex items-center gap-2 text-emerald-700 dark:text-emerald-400 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verified by <strong>{{ $bazarEntry->verifiedBy->name }}</strong> on {{ $bazarEntry->updated_at->format('d M, Y') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
