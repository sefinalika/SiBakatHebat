@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
    <div class="w-full max-w-3xl text-center">
        <img src="{{ asset('img/logo.png') }}" alt="Si Bakat Hebat"
             class="mx-auto mb-8 h-20 w-20 rounded-2xl ring-1 ring-line">

        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight text-ink">
            Temukan Bakat Terbaik Anak Bersama
            <span class="block text-brand">Si Bakat Hebat</span>
        </h1>

        <p class="mx-auto mt-6 max-w-xl text-lg leading-relaxed text-ink-soft">
            Sebagai guru atau wali murid, amati anak lewat 76 pertanyaan berdasarkan
            konsep 40 sifat mulia islami (TB-40) — lalu dapatkan laporan bakat,
            karakter, dan potensi kekuatannya.
        </p>

        <div class="mt-9 flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                <a href="{{ route('anak.index') }}"
                   class="rounded-full bg-brand px-8 py-4 font-semibold text-white shadow-sm hover:bg-brand-dark transition">
                    Anak Didik &amp; Mulai Tes &rarr;
                </a>
                <a href="{{ route('riwayat') }}"
                   class="rounded-full border border-line bg-surface px-8 py-4 font-semibold text-ink hover:bg-brand-soft transition">
                    Riwayat Hasil
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-full bg-brand px-8 py-4 font-semibold text-white shadow-sm hover:bg-brand-dark transition">
                    Masuk &amp; Mulai Tes &rarr;
                </a>
                <a href="{{ route('register') }}"
                   class="rounded-full border border-line bg-surface px-8 py-4 font-semibold text-ink hover:bg-brand-soft transition">
                    Daftar Akun Baru
                </a>
            @endauth
        </div>

        <div class="mx-auto mt-12 grid max-w-2xl gap-4 sm:grid-cols-3">
            @foreach ([
                ['76 Pertanyaan', '± 15 menit pengisian'],
                ['40 Karakter TB-40', 'Sifat mulia islami'],
                ['Laporan Lengkap', 'Tampil di web & PDF'],
            ] as [$judul, $ket])
                <div class="rounded-2xl border border-line bg-surface p-5">
                    <p class="font-bold text-ink">{{ $judul }}</p>
                    <p class="mt-1 text-sm text-ink-soft">{{ $ket }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
