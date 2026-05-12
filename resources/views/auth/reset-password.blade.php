@extends('layouts.auth')
@section('page-title', 'Reset Password')

@section('content')
    <div class="mb-6">
        <h2 class="text-white text-2xl font-bold">Set new password</h2>
        <p class="text-blue-300 text-sm mt-1">Choose a strong password for your account</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5" data-loading>
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $email ?? '') }}" required autofocus
                   class="w-full px-4 py-3 bg-white/10 border {{ $errors->has('email') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                   placeholder="you@example.com">
            @error('email')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <div x-data="{ show: false }">
            <label class="block text-sm font-medium text-blue-200 mb-1.5">New Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="w-full px-4 py-3 bg-white/10 border {{ $errors->has('password') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm pr-11"
                       placeholder="Min 8 chars">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-blue-400 hover:text-white">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Confirm Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                   placeholder="Repeat new password">
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all text-sm shadow-lg shadow-blue-500/30">
            Reset Password
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-blue-400 hover:text-white text-sm">Back to login</a>
        </div>
    </form>
@endsection
