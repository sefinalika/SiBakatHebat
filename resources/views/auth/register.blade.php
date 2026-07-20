@extends('layouts.app')

@section('title', 'Daftar')

@php
    $inputClass = 'w-full rounded-xl border border-line bg-page px-4 py-2.5 text-ink
                   placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand/40
                   focus:border-brand transition';
    $labelClass = 'block text-sm font-medium text-ink mb-1';
@endphp

@section('content')
    <div class="w-full max-w-lg">
        <div class="text-center mb-5">
            <img src="{{ asset('img/logo.png') }}" alt="Si Bakat Hebat"
                 class="mx-auto mb-2 h-24 w-24 rounded-2xl ring-1 ring-line">
            <h1 class="text-2xl font-bold text-ink">Daftar Akun</h1>
            <p class="mt-1 text-sm text-ink-soft">Untuk guru dan wali murid.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
            <a href="{{ route('auth.google') }}"
               class="flex w-full items-center justify-center gap-3 rounded-xl border border-line bg-surface px-4 py-2.5
                      font-semibold text-ink hover:bg-page transition">
                <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.3-1.6 3.9-5.5 3.9-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.1.8 3.9 1.5l2.7-2.6C16.9 2.7 14.7 1.8 12 1.8 6.9 1.8 2.8 5.9 2.8 11S6.9 20.2 12 20.2c5.3 0 8.8-3.7 8.8-8.9 0-.6-.1-1-.2-1.5H12z"/></svg>
                Daftar dengan Google
            </a>

            <div class="flex items-center gap-3 text-xs text-ink-soft my-4">
                <span class="h-px flex-1 bg-line"></span>atau isi formulir<span class="h-px flex-1 bg-line"></span>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                {{-- Akun guru / wali murid --}}
                <p class="text-brand text-xs font-semibold uppercase tracking-wide">Akun Guru / Wali Murid</p>
                <div>
                    <label class="{{ $labelClass }}">Nama Anda <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="cth: Budi Santoso" class="{{ $inputClass }}">
                    <p class="mt-1 text-xs text-ink-soft">Dipakai untuk login. Hanya huruf dan spasi (tanpa angka/karakter lain).</p>
                </div>
                <div>
                    <label class="{{ $labelClass }}">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" class="{{ $inputClass }}">
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="{{ $labelClass }}">Password <span class="text-red-500">*</span></label>
                        <x-password-input name="password" :input-class="$inputClass" placeholder="Minimal 8 karakter" />
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Ulangi Password <span class="text-red-500">*</span></label>
                        <x-password-input name="password_confirmation" :input-class="$inputClass" placeholder="Ulangi password" />
                    </div>
                </div>

                <p class="rounded-xl bg-brand-soft px-4 py-3 text-xs text-ink-soft">
                    Data anak didik (nama, sekolah, tanggal lahir) diisi setelah Anda masuk —
                    satu akun boleh mendaftarkan banyak anak.
                </p>

                <button type="submit"
                        class="w-full rounded-full bg-brand px-6 py-3.5 font-semibold text-white hover:bg-brand-dark transition">
                    Daftar
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-sm text-ink-soft">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-brand hover:text-brand-dark">Masuk di sini</a>
        </p>
    </div>
@endsection
