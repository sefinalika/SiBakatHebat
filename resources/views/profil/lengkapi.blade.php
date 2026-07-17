@extends('layouts.app')

@section('title', 'Lengkapi Profil')

@section('content')
    <div class="w-full max-w-md text-center">
        <img src="{{ asset('img/logo.png') }}" alt="Si Bakat Hebat"
             class="mx-auto mb-4 h-14 w-14 rounded-2xl ring-1 ring-line">

        <p class="text-brand text-[11px] font-bold tracking-[0.25em] uppercase">Si Bakat Hebat</p>
        <h1 class="mt-1 text-3xl font-extrabold text-ink">Lengkapi Profil</h1>
        <p class="mt-3 leading-relaxed text-ink-soft">
            Halo <span class="font-semibold text-ink">{{ auth()->user()->name }}</span>,
            tentukan nama pengguna Anda sebagai guru / wali murid.
            Data anak didik diisi setelah ini.
        </p>

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-left text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profil.simpan') }}"
              class="mt-8 space-y-3 rounded-2xl border border-line bg-surface p-6 text-left shadow-sm">
            @csrf

            <div>
                <input type="text" name="username" value="{{ old('username', $usernameDefault) }}"
                       placeholder="Nama Pengguna" maxlength="50" aria-label="Nama Pengguna"
                       class="w-full rounded-xl border border-line bg-page px-4 py-3 text-ink
                              placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand/40
                              focus:border-brand transition">
                <p class="mt-1 ml-1 text-xs text-ink-soft">Nama Anda — hanya huruf dan spasi.</p>
            </div>

            <button type="submit"
                    class="group mt-2 w-full rounded-full bg-brand px-6 py-3.5 font-semibold text-white hover:bg-brand-dark transition">
                Simpan &amp; Lanjut
                <span class="inline-block transition-transform group-hover:translate-x-1">&rarr;</span>
            </button>
        </form>
    </div>
@endsection
