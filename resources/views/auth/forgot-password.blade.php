@extends('layouts.auth')
@section('page-title', 'Forgot Password')

@section('content')
    <div class="mb-6">
        <a href="{{ route('login') }}" class="text-blue-400 hover:text-white text-sm flex items-center gap-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to login
        </a>
        <h2 class="text-white text-2xl font-bold">Reset your password</h2>
        <p class="text-blue-300 text-sm mt-1">Enter your email and we'll send you a reset link</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-xl text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5" data-loading>
        @csrf

        <div>
            <label class="block text-sm font-medium text-blue-200 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full px-4 py-3 bg-white/10 border {{ $errors->has('email') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white placeholder-blue-300/60 focus:outline-none focus:ring-2 focus:ring-blue-500 transition text-sm"
                   placeholder="you@example.com">
            @error('email')<p class="mt-1 text-red-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition-all text-sm shadow-lg shadow-blue-500/30">
            Send Reset Link
        </button>
    </form>
@endsection
