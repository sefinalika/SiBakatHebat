@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
    <div class="w-full max-w-sm">
        <div class="mb-5 text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Si Bakat Hebat"
                 class="mx-auto mb-2 h-24 w-24 rounded-2xl ring-1 ring-line">
            <h1 class="text-2xl font-bold text-ink">Lupa Password</h1>
            <p class="mt-1 text-sm text-ink-soft">
                Masukkan email akun Anda. Kami kirimkan tautan untuk menyetel password baru.
            </p>
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

        <form method="POST" action="{{ route('password.email') }}"
              class="space-y-3 rounded-2xl border border-line bg-surface p-5 shadow-sm">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-ink">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com"
                       class="w-full rounded-xl border border-line bg-page px-4 py-2.5 text-ink placeholder-slate-400
                              focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/40 transition">
            </div>
            <button type="submit"
                    class="w-full rounded-full bg-brand px-6 py-3 font-semibold text-white hover:bg-brand-dark transition">
                Kirim Tautan Reset
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-ink-soft">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-semibold text-brand hover:text-brand-dark">Kembali ke Masuk</a>
        </p>
    </div>
@endsection
