@extends('layouts.app')
@section('page-title', 'Settings')

@section('content')
<div class="space-y-5 animate-fade-in">

    <div class="page-header">
        <div>
            <h2 class="page-title">System Settings</h2>
            <div class="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Settings</span>
            </div>
        </div>
        <form method="POST" action="{{ route('settings.clearCache') }}">
            @csrf @method('DELETE')
            <button type="submit" class="btn-secondary" onclick="return confirm('Clear all cache?')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Clear Cache
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-5" data-loading>
        @csrf @method('PUT')

        @foreach($settings as $group => $groupSettings)
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-gray-900 dark:text-white capitalize">
                    @switch($group)
                        @case('general') 🏢 General @break
                        @case('meal') 🍽 Meal @break
                        @case('email') 📧 Email @break
                        @case('system') ⚙️ System @break
                        @default {{ ucfirst($group) }}
                    @endswitch
                </h3>
            </div>
            <div class="card-body space-y-4">
                @foreach($groupSettings as $setting)
                <div class="form-group">
                    <label class="form-label">{{ $setting->label ?? ucwords(str_replace('_', ' ', $setting->key)) }}</label>

                    @if($setting->type === 'boolean')
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="settings[{{ $setting->key }}]" value="0">
                                <input type="checkbox" name="settings[{{ $setting->key }}]" value="1"
                                       class="sr-only peer" {{ $setting->value ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $setting->value ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                    @elseif($setting->type === 'integer')
                        <input type="number" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}"
                               class="form-input max-w-xs" step="1" min="0">
                    @else
                        <input type="text" name="settings[{{ $setting->key }}]" value="{{ old('settings.'.$setting->key, $setting->value) }}"
                               class="form-input max-w-md">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="btn-primary px-8">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save All Settings
            </button>
        </div>
    </form>
</div>
@endsection
