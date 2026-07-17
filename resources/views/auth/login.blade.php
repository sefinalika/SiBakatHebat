@extends('layouts.app')

@section('title', 'Masuk')

@php
    $inputClass = 'w-full rounded-xl border border-line bg-page px-4 py-2.5 text-ink
                   placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand/40
                   focus:border-brand transition';
@endphp

@section('content')
    <div class="w-full max-w-sm">
        <div class="text-center mb-4">
            <img src="{{ asset('img/logo.png') }}" alt="Si Bakat Hebat"
                 class="mx-auto mb-2 h-24 w-24 rounded-2xl ring-1 ring-line">
            <h1 class="mt-2 text-2xl font-bold text-ink">Masuk</h1>
        </div>

        @if (session('status'))
            <div class="mb-3 rounded-xl border border-brand/30 bg-brand-soft p-3 text-sm text-ink">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-line bg-surface p-5 space-y-4 shadow-sm">
            {{-- Google --}}
            <a href="{{ route('auth.google') }}"
               class="flex w-full items-center justify-center gap-3 rounded-xl border border-line bg-surface px-4 py-2.5
                      font-semibold text-ink hover:bg-page transition">
                <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-1.6 3.9-5.5 3.9-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.1.8 3.9 1.5l2.7-2.6C16.9 2.7 14.7 1.8 12 1.8 6.9 1.8 2.8 5.9 2.8 11S6.9 20.2 12 20.2c5.3 0 8.8-3.7 8.8-8.9 0-.6-.1-1-.2-1.5H12z"/></svg>
                Masuk dengan Google
            </a>

            <div class="flex items-center gap-3 text-xs text-ink-soft">
                <span class="h-px flex-1 bg-line"></span>atau<span class="h-px flex-1 bg-line"></span>
            </div>

            {{-- Username + password --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-ink mb-1">Email atau Nama Pengguna</label>
                    <input type="text" name="username" value="" autocomplete="off" class="{{ $inputClass }}" placeholder="email atau nama pengguna Anda">
                </div>
                <div>
                    <label class="block text-sm font-medium text-ink mb-1">Password</label>
                    <input type="password" name="password" value="" autocomplete="new-password" class="{{ $inputClass }}" placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-ink-soft">
                        <input type="checkbox" name="remember" class="rounded border-line text-brand focus:ring-brand/40"> Ingat saya
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-brand hover:text-brand-dark">
                        Lupa password?
                    </a>
                </div>
                <button type="submit"
                        class="w-full rounded-full bg-brand px-6 py-3 font-semibold text-white hover:bg-brand-dark transition">
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-4 text-center text-sm text-ink-soft">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-brand hover:text-brand-dark">Daftar di sini</a>
        </p>
    </div>
@endsection
