@extends('layouts.app')

@section('title', 'Setel Password Baru')

@php
    $inputClass = 'w-full rounded-xl border border-line bg-page px-4 py-2.5 text-ink placeholder-slate-400
                   focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/40 transition';
@endphp

@section('content')
    <div class="w-full max-w-sm">
        <div class="mb-5 text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Si Bakat Hebat"
                 class="mx-auto mb-2 h-24 w-24 rounded-2xl ring-1 ring-line">
            <h1 class="text-2xl font-bold text-ink">Setel Password Baru</h1>
            <p class="mt-1 text-sm text-ink-soft">Pilih password baru untuk akun Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-3 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}"
              class="space-y-3 rounded-2xl border border-line bg-surface p-5 shadow-sm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="mb-1 block text-sm font-medium text-ink">Email</label>
                <input type="email" name="email" value="{{ old('email', $email) }}" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-ink">Password Baru</label>
                <x-password-input name="password" :input-class="$inputClass" placeholder="Minimal 8 karakter" />
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-ink">Ulangi Password Baru</label>
                <x-password-input name="password_confirmation" :input-class="$inputClass" placeholder="Ulangi password" />
            </div>

            <button type="submit"
                    class="w-full rounded-full bg-brand px-6 py-3 font-semibold text-white hover:bg-brand-dark transition">
                Simpan Password Baru
            </button>
        </form>
    </div>
@endsection
