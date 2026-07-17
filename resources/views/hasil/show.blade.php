@extends('layouts.app')

@section('title', 'Hasil Tes')

@php use App\Support\KategoriWarna; @endphp

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
@endpush

@section('content')
    @php
        $urut = $observasi->hasilObservasi->sortBy('karakter_id')->values();
        $chartLabels = $urut->map(fn ($h) => $h->karakter?->terjemahan ?? ('K'.$h->karakter_id));
        $chartData = $urut->map(fn ($h) => (float) $h->skor);
        $chartColors = $urut->map(fn ($h) => KategoriWarna::hex($h->kategori));
    @endphp

    <div class="w-full max-w-4xl space-y-6">
        <div class="text-center">
            <p class="text-emerald-400 text-xs font-bold tracking-[0.2em] uppercase">Si Bakat Hebat</p>
            <h1 class="mt-2 text-3xl font-bold text-white">Hasil Tes Karakter</h1>
        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5 text-sm text-slate-300 grid sm:grid-cols-2 gap-x-6 gap-y-1">
            <p><span class="text-slate-400">Nama Anak:</span> {{ $observasi->peserta->nama }}</p>
            <p><span class="text-slate-400">Email:</span> {{ $observasi->peserta->user->email }}</p>
            <p><span class="text-slate-400">Jenis Kelamin:</span> {{ ucfirst($observasi->peserta->jenis_kelamin) }}</p>
            <p><span class="text-slate-400">Tanggal Lahir:</span> {{ $observasi->peserta->tanggal_lahir?->format('d-m-Y') }}</p>
            <p><span class="text-slate-400">Umur:</span> {{ $observasi->peserta->umur() }} tahun</p>
            <p><span class="text-slate-400">Sekolah:</span> {{ $observasi->peserta->nama_sekolah }}</p>
            <p><span class="text-slate-400">Daerah:</span> {{ $observasi->peserta->kota }}, {{ $observasi->peserta->provinsi }}</p>
        </div>

        {{-- Kesimpulan --}}
        <div class="rounded-2xl border border-emerald-400/30 bg-gradient-to-br from-emerald-500/10 to-blue-500/5 p-5">
            <h2 class="text-emerald-300 font-semibold mb-2 flex items-center gap-2">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.6 4.4L18 8l-4.4 1.6L12 14l-1.6-4.4L6 8l4.4-1.6L12 2z"/></svg>
                Kesimpulan
            </h2>
            <p class="text-slate-200 leading-relaxed">{{ $kesimpulan['narasi'] }}</p>
            <div class="mt-4 grid sm:grid-cols-3 gap-3 text-sm">
                <div class="rounded-xl bg-white/5 p-3">
                    <p class="text-slate-400 text-xs">Dimensi Dominan</p>
                    <p class="text-white font-semibold">Kinerja {{ $kesimpulan['dimensi_dominan'][0]['label'] ?? '-' }}</p>
                </div>
                <div class="rounded-xl bg-white/5 p-3">
                    <p class="text-slate-400 text-xs">Gaya Belajar</p>
                    <p class="text-white font-semibold">{{ $kesimpulan['gaya_dominan']['label'] ?? '-' }} <span class="text-slate-400 font-normal">({{ $kesimpulan['gaya_dominan']['arti'] ?? '' }})</span></p>
                </div>
                <div class="rounded-xl bg-white/5 p-3">
                    <p class="text-slate-400 text-xs">Bahasa Hati</p>
                    <p class="text-white font-semibold">{{ $kesimpulan['hati_dominan']['label'] ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Nilai ringkas --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach (['Aqidah' => $section['aqidah'], 'Ibadah' => $section['ibadah'], 'Karakter Belajar' => $section['karakter_belajar'], 'Bakat Umum' => $section['bakat_umum']] as $label => $nilai)
                <div class="rounded-xl border border-white/10 bg-white/[0.03] p-4 text-center">
                    <p class="text-slate-400 text-xs">{{ $label }}</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $nilai }}</p>
                </div>
            @endforeach
        </div>

        {{-- Grafik Karakter (Chart.js) --}}
        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 sm:p-5">
            <h2 class="text-white font-semibold mb-4">Grafik 40 Karakter TB-40</h2>
            <div class="h-[560px]">
                <canvas id="chartKarakter"></canvas>
            </div>
        </div>

        {{-- Peta Karakter (bar berwarna, dikelompokkan per dimensi) --}}
        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5 space-y-6">
            <h2 class="text-white font-semibold">Peta Karakter</h2>
            @foreach ($grupDimensi as $dimensi => $items)
                <div>
                    <h3 class="text-emerald-300 text-sm font-semibold uppercase tracking-wide mb-3">{{ $dimensi }}</h3>
                    <div class="grid sm:grid-cols-2 gap-x-6 gap-y-2.5">
                        @foreach ($items as $h)
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-slate-300 w-24 sm:w-32 shrink-0 truncate" title="{{ $h->karakter?->terjemahan }}">
                                    {{ $h->karakter?->nama_arab ?: $h->karakter?->nama_karakter }}
                                </span>
                                <div class="flex-1 h-3 rounded-full bg-white/10 overflow-hidden">
                                    <div class="h-full rounded-full" style="width: {{ $h->skor * 10 }}%; background: {{ KategoriWarna::hex($h->kategori) }}"></div>
                                </div>
                                <span class="text-xs font-semibold w-7 text-right" style="color: {{ KategoriWarna::hex($h->kategori) }}">{{ $h->skor }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- Legenda warna --}}
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide mb-2">Makna Warna-warna</p>
                <div class="flex flex-wrap gap-x-5 gap-y-2 text-xs">
                    @foreach (KategoriWarna::legenda() as $l)
                        <span class="flex items-center gap-2 text-slate-300">
                            <span class="h-3 w-5 rounded" style="background: {{ $l['hex'] }}"></span>{{ $l['label'] }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kekuatan / Kelemahan (detail) --}}
        <div class="grid sm:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                <h2 class="text-emerald-300 font-semibold mb-4 uppercase text-sm tracking-wide">Bakat Kekuatan (Top 6)</h2>
                <div class="space-y-4">
                    @foreach ($top as $h)
                        <div class="border-l-2 pl-3" style="border-color: {{ KategoriWarna::hex($h->kategori) }}">
                            <div class="flex items-baseline justify-between">
                                <p class="font-semibold text-white">{{ $h->karakter?->nama_karakter }}
                                    <span class="text-slate-400 font-normal text-sm">— {{ $h->karakter?->terjemahan }}</span></p>
                                <span class="text-sm font-bold" style="color: {{ KategoriWarna::hex($h->kategori) }}">{{ $h->skor }}</span>
                            </div>
                            @if ($h->karakter?->definisi)<p class="text-xs text-slate-400 mt-1">{{ $h->karakter->definisi }}</p>@endif
                            @if ($h->karakter?->profesi)<p class="text-xs text-slate-500 mt-1"><span class="text-slate-400">Profesi:</span> {{ $h->karakter->profesi }}</p>@endif
                            @if ($h->karakter?->jurusan)<p class="text-xs text-slate-500"><span class="text-slate-400">Jurusan:</span> {{ $h->karakter->jurusan }}</p>@endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                <h2 class="text-amber-300 font-semibold mb-4 uppercase text-sm tracking-wide">Bakat Kelemahan (Bottom 6)</h2>
                <div class="space-y-4">
                    @foreach ($bottom as $h)
                        <div class="border-l-2 pl-3" style="border-color: {{ KategoriWarna::hex($h->kategori) }}">
                            <div class="flex items-baseline justify-between">
                                <p class="font-semibold text-white">{{ $h->karakter?->nama_karakter }}
                                    <span class="text-slate-400 font-normal text-sm">— {{ $h->karakter?->terjemahan }}</span></p>
                                <span class="text-sm font-bold" style="color: {{ KategoriWarna::hex($h->kategori) }}">{{ $h->skor }}</span>
                            </div>
                            @if ($h->karakter?->definisi)<p class="text-xs text-slate-400 mt-1">{{ $h->karakter->definisi }}</p>@endif
                            @if ($h->karakter?->jurusan)<p class="text-xs text-slate-500 mt-1"><span class="text-slate-400">Jurusan disarankan:</span> {{ $h->karakter->jurusan }}</p>@endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Potensi Sifat Tercela & Solusi (PRD 3.5 G) --}}
        @php $terkuat = $top->first(); $terlemah = $bottom->first(); @endphp
        @if (($terkuat?->karakter?->sifat_tercela_berlebihan) || ($terlemah?->karakter?->sifat_tercela_melalaikan))
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                <h2 class="text-white font-semibold mb-4">Potensi Sifat Tercela &amp; Solusi</h2>
                <div class="grid sm:grid-cols-2 gap-5 text-sm">
                    @if ($terkuat?->karakter?->sifat_tercela_berlebihan)
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Dari bakat terkuat ({{ $terkuat->karakter->nama_karakter }}) jika berlebihan</p>
                            <p class="text-rose-300 font-medium">{{ $terkuat->karakter->sifat_tercela_berlebihan }}</p>
                            <p class="text-slate-400 mt-1">{{ $terkuat->karakter->cara_memperbaiki_berlebihan }}</p>
                        </div>
                    @endif
                    @if ($terlemah?->karakter?->sifat_tercela_melalaikan)
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-wide mb-1">Dari bakat terlemah ({{ $terlemah->karakter->nama_karakter }}) jika diabaikan</p>
                            <p class="text-amber-300 font-medium">{{ $terlemah->karakter->sifat_tercela_melalaikan }}</p>
                            <p class="text-slate-400 mt-1">{{ $terlemah->karakter->cara_memperbaiki_melalaikan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Gaya belajar / Bahasa hati --}}
        <div class="grid sm:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                <h2 class="text-blue-300 font-semibold mb-3">Gaya Belajar (Kinerja Akal)</h2>
                <ol class="list-decimal list-inside space-y-1 text-sm text-slate-200">
                    @foreach ($gaya_belajar as $g)
                        <li>{{ $g['label'] }} ({{ $g['arti'] }}) — {{ $g['skor'] }}</li>
                    @endforeach
                </ol>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                <h2 class="text-pink-300 font-semibold mb-3">Bahasa Hati (Kinerja Hati)</h2>
                <ol class="list-decimal list-inside space-y-1 text-sm text-slate-200">
                    @foreach ($bahasa_hati as $b)
                        <li>{{ $b['label'] }} — {{ $b['skor'] }}</li>
                    @endforeach
                </ol>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 justify-center pt-2">
            <a href="{{ route('hasil.pdf', $observasi) }}"
               class="rounded-xl bg-gradient-to-r from-emerald-500 to-blue-500 px-6 py-3 font-semibold text-white">Unduh PDF</a>
            <a href="{{ route('landing') }}"
               class="rounded-xl border border-white/15 px-6 py-3 font-semibold text-slate-200 hover:bg-white/5">Beranda</a>
        </div>
        <p class="text-center text-xs text-slate-500">Salinan hasil ini juga dikirim ke email Anda.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('chartKarakter');
            if (ctx && window.Chart) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Skor',
                            data: @json($chartData),
                            backgroundColor: @json($chartColors),
                            borderWidth: 0,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { min: 0, max: 10, ticks: { color: '#94a3b8', stepSize: 1 }, grid: { color: 'rgba(255,255,255,0.05)' } },
                            y: { ticks: { color: '#cbd5e1', font: { size: 10 } }, grid: { display: false } },
                        },
                    },
                });
            }
        });
    </script>
@endsection
