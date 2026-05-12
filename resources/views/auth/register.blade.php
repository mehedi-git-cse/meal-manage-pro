@extends('layouts.auth')
@section('page-title', 'Create Account')

@section('content')
    <div class="mb-6">
        <h2 class="text-white text-2xl font-bold">Create your account</h2>
        <p class="text-blue-300 text-sm mt-1">Join the meal management system</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}" class="space-y-4" data-loading>
        @csrf

        <div>
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full px-4 py-3 bg-white/10 border {{ $errors->has('name') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                   placeholder="John Doe">
            @error('name')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-3 bg-white/10 border {{ $errors->has('email') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                   placeholder="you@example.com">
            @error('email')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Phone (Optional)</label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                   placeholder="+1 (555) 000-0000">
        </div>

        <div x-data="{ show: false }">
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="w-full px-4 pr-12 py-3 bg-white/10 border {{ $errors->has('password') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                       placeholder="Min 8 chars, upper + number">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 text-blue-400 hover:text-white">
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
                   placeholder="Repeat password">
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all duration-150 text-sm shadow-lg shadow-blue-500/30 mt-2">
            Create Account
        </button>

        <p class="text-center text-sm text-blue-300">
            Already have an account?
            <a href="{{ route('login') }}" class="text-white font-medium hover:underline">Sign in</a>
        </p>
    </form>
@endsection
