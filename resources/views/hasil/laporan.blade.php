<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Si Bakat Hebat — Hasil Tes {{ $observasi->peserta->nama }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    {{-- Varian "pro": html2canvas biasa gagal membaca warna oklch bawaan Tailwind v4. --}}
    <script defer src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.11/dist/html2canvas-pro.min.js"></script>
    <style>
        @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .reveal { animation: fadeUp .5s ease both; }
        /* Bar progres baca di tepi atas layar. */
        #bacaProgres { position: fixed; top: 0; left: 0; height: 4px; width: 0; z-index: 50; transition: width .1s linear; }
    </style>
</head>
@php
    $primary = '#22a6e8';   // biru langit — aksen utama
    $ink = '#2b2a6b';       // navy — teks judul
    $anak = $observasi->peserta->nama;
    $radar = $observasi->hasilObservasi->sortBy('karakter_id')->values();
    $radarLabels = $radar->map(fn ($h) => $h->karakter?->terjemahan ?? ('K'.$h->karakter_id));
    $radarData = $radar->map(fn ($h) => (float) $h->skor);
@endphp
<body class="min-h-screen bg-page text-ink-soft antialiased">

<div id="bacaProgres" style="background:{{ $primary }}"></div>

<div class="mx-auto max-w-5xl px-4 sm:px-6 py-8 space-y-6">

    {{-- ══ HEADER ══ --}}
    <header class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="h-12 w-12 rounded-2xl flex items-center justify-center text-white shrink-0" style="background:{{ $primary }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest" style="color:{{ $primary }}">Laporan Hasil Tes</p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-ink">{{ $anak }}</h1>
                    <p class="mt-1 text-sm text-ink-soft">
                        {{ $observasi->tanggal?->translatedFormat('d F Y') }} &middot; Kode: <span class="font-mono">{{ $kode }}</span>
                        &middot; Diisi oleh {{ $observasi->peserta->user->name }}
                    </p>
                </div>
            </div>
            <a href="{{ route('hasil.pdf', $observasi) }}"
               class="inline-flex items-center justify-center gap-2 rounded-full px-5 py-3 font-semibold text-white shadow-sm hover:opacity-90 transition"
               style="background:{{ $primary }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download PDF
            </a>
        </div>
    </header>

    {{-- ════════════ BABAK 1 — SIAPA ANANDA (paling umum) ════════════ --}}
    <p class="reveal px-1 pt-2 text-xs font-bold uppercase tracking-[0.2em]" style="color:{{ $primary }}">
        Babak 1 dari 4 &bull; Mengenal {{ $anak }}
    </p>

    {{-- Ringkasan 4 kartu --}}
    @php
        $ringkasCards = [
            ['label' => 'Tipe Bakat Utama', 'nilai' => 'Kinerja '.$ringkasan['bakat_utama'], 'icon' => 'M12 3l1.9 5.2L19 10l-5.1 1.8L12 17l-1.9-5.2L5 10l5.1-1.8L12 3z'],
            ['label' => 'Tipe Kepribadian', 'nilai' => $ringkasan['tipe_kepribadian'], 'icon' => 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.5a7.5 7.5 0 0 1 15 0v.75H4.5v-.75Z'],
            ['label' => 'Gaya Belajar', 'nilai' => $ringkasan['gaya_belajar'], 'icon' => 'M12 6.75A5.25 5.25 0 0 1 17.25 12 5.25 5.25 0 0 1 12 17.25 5.25 5.25 0 0 1 6.75 12 5.25 5.25 0 0 1 12 6.75Z'],
            ['label' => 'Karakter Dominan', 'nilai' => ucfirst($ringkasan['karakter_dominan']), 'icon' => 'm11.48 3.5 2.3 4.66 5.14.75-3.72 3.62.88 5.12-4.6-2.42-4.6 2.42.88-5.12L4.08 8.9l5.14-.75L11.48 3.5Z'],
        ];
    @endphp
    <section class="reveal grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($ringkasCards as $c)
            <div class="rounded-3xl bg-surface shadow-sm ring-1 ring-line p-5">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl mb-3" style="background:rgba(34,166,232,.12); color:{{ $primary }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/></svg>
                </span>
                <p class="text-xs text-ink-soft">{{ $c['label'] }}</p>
                <p class="mt-0.5 text-lg font-bold text-ink leading-tight">{{ $c['nilai'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- Kesimpulan naratif — jawaban utamanya, diberikan di awal --}}
    <section class="reveal rounded-3xl p-6 sm:p-8 text-white shadow-sm" style="background:linear-gradient(135deg,{{ $primary }},{{ $ink }})">
        <h2 class="mb-2 flex items-center gap-2 text-lg font-bold">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
            Kesimpulan
        </h2>
        <p class="leading-relaxed text-white/95">{{ $kesimpulan['narasi'] }}</p>
    </section>

    <p class="reveal px-1 text-center text-sm text-ink-soft">
        Itu gambaran besarnya. <span class="font-semibold text-ink">Tapi dari mana kesimpulan ini datang?</span>
        Mari lihat kekuatan dan kelemahannya satu per satu. &darr;
    </p>

    {{-- ════════════ BABAK 2 — KEKUATAN & KELEMAHAN ════════════ --}}
    <p class="reveal px-1 pt-4 text-xs font-bold uppercase tracking-[0.2em]" style="color:{{ $primary }}">
        Babak 2 dari 4 &bull; Kekuatan &amp; Kelemahannya
    </p>

    {{-- Karakter terkuat --}}
    <section class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <h2 class="mb-5 text-lg font-bold text-ink">Karakter Terkuat</h2>
        <div class="space-y-5">
            @foreach ($top->take(5) as $h)
                @php $p = min(100, (int) round((float) $h->skor * 10)); @endphp
                <div>
                    <div class="flex items-baseline justify-between gap-3">
                        <p class="font-semibold text-ink">{{ $h->karakter?->nama_karakter }}
                            <span class="text-sm font-normal text-ink-soft">— {{ $h->karakter?->terjemahan }}</span></p>
                        <span class="text-sm font-bold" style="color:{{ $primary }}">{{ $p }}%</span>
                    </div>
                    <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-line">
                        <div class="h-full rounded-full" style="width:{{ $p }}%; background:{{ $primary }}"></div>
                    </div>
                    @if ($h->karakter?->definisi)
                        <p class="mt-1.5 text-sm text-ink-soft">{{ $h->karakter->definisi }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Perlu dikembangkan --}}
    <section class="reveal">
        <h2 class="mb-4 px-1 text-lg font-bold text-ink">Karakter yang Perlu Dikembangkan</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($bottom->take(4) as $h)
                <div class="rounded-3xl bg-amber-50 p-5 ring-1 ring-amber-200">
                    <div class="mb-1 flex items-center gap-2">
                        <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                        <p class="font-semibold text-amber-900">{{ $h->karakter?->nama_karakter }}
                            <span class="text-sm font-normal text-amber-700/80">— {{ $h->karakter?->terjemahan }}</span></p>
                    </div>
                    @if ($h->karakter?->definisi)<p class="text-sm text-amber-800/90">{{ $h->karakter->definisi }}</p>@endif
                    @if ($h->karakter?->cara_memperbaiki_melalaikan)
                        <p class="mt-2 text-sm text-ink-soft"><span class="font-semibold text-amber-700">Rekomendasi:</span> {{ $h->karakter->cara_memperbaiki_melalaikan }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Peta lengkap 40 karakter — sekarang pembaca sudah punya bekal membacanya --}}
    <section class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <h2 class="mb-1 text-lg font-bold text-ink">Peta Lengkap 40 Karakter</h2>
        <p class="mb-4 text-sm text-ink-soft">
            Lima karakter terkuat dan empat yang perlu dikembangkan di atas diambil dari peta ini —
            sebaran skor seluruh 40 karakter TB-40 (skala 1–10).
        </p>
        <div class="h-[420px] sm:h-[520px]">
            <canvas id="radarKarakter"></canvas>
        </div>
    </section>

    <p class="reveal px-1 text-center text-sm text-ink-soft">
        Sekarang kita tahu <span class="font-semibold text-ink">apa</span> kekuatannya.
        <span class="font-semibold text-ink">Lalu bagaimana cara {{ $anak }} berpikir, belajar, dan beribadah?</span> &darr;
    </p>

    {{-- ════════════ BABAK 3 — CARA KERJA DIRINYA ════════════ --}}
    <p class="reveal px-1 pt-4 text-xs font-bold uppercase tracking-[0.2em]" style="color:{{ $primary }}">
        Babak 3 dari 4 &bull; Cara Kerja Dirinya
    </p>

    {{-- Analisis kepribadian --}}
    @php
        $aspek = [
            ['Cara Berpikir', $analisis['berpikir'], 'M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z'],
            ['Cara Bekerja', $analisis['bekerja'], 'M20.25 14.15v4.073a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-4.072M15.75 9.75 12 6m0 0L8.25 9.75M12 6v9'],
            ['Cara Berinteraksi', $analisis['berinteraksi'], 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z'],
            ['Cara Mengambil Keputusan', $analisis['keputusan'], 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ['Potensi Diri', $analisis['potensi'], 'M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.63 8.42m5.96 5.95a14.926 14.926 0 0 1-5.841 2.58m-.119-8.53a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84'],
        ];
    @endphp
    <section class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <h2 class="mb-5 text-lg font-bold text-ink">Analisis Kepribadian</h2>
        <div class="grid gap-5 sm:grid-cols-2">
            @foreach ($aspek as [$judul, $isi, $icon])
                <div class="flex gap-3">
                    <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl" style="background:rgba(34,166,232,.12); color:{{ $primary }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </span>
                    <div>
                        <p class="font-semibold text-ink">{{ $judul }}</p>
                        <p class="mt-1 text-sm leading-relaxed text-ink-soft">{{ $isi }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Gaya belajar --}}
    <section class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <h2 class="mb-1 text-lg font-bold text-ink">Gaya Belajar</h2>
        <p class="mb-5 text-sm text-ink-soft">Gaya belajar dominan {{ $anak }}:
            <span class="font-semibold" style="color:{{ $primary }}">{{ $gaya_lengkap[0]['tipe'] ?? '-' }}</span>
            ({{ $gaya_lengkap[0]['arti'] ?? '' }}).</p>
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach ($gaya_lengkap as $i => $g)
                <div @class([
                    'rounded-3xl p-5 ring-1',
                    'ring-2 text-white' => $i === 0,
                    'bg-page ring-line' => $i !== 0,
                ]) @style(['background:'.$primary => $i === 0])>
                    <div class="flex items-center justify-between">
                        <p @class(['font-bold', 'text-white' => $i===0, 'text-ink' => $i!==0])>{{ $g['tipe'] }}</p>
                        <span @class(['text-sm font-bold', 'text-white/90' => $i===0]) @style(['color:'.$primary => $i!==0])>{{ $g['persen'] }}%</span>
                    </div>
                    <p @class(['text-xs mt-0.5', 'text-white/80' => $i===0, 'text-ink-soft' => $i!==0])>{{ $g['arti'] }}</p>
                    @if ($i === 0)
                        <ul class="mt-3 space-y-1.5 text-sm text-white/90">
                            @foreach ($g['tips'] as $tip)
                                <li class="flex gap-2"><span>•</span><span>{{ $tip }}</span></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- Persentase keislaman: donat komposisi 4 poin, panjang cincin = capaian --}}
    @php
        $warnaIslam = \App\Support\KategoriWarna::hex($keislaman['warna']);
        // Slot warna kategorikal urutan tetap — sudah lolos cek keterbacaan buta warna.
        $hexKomponen = ['#2a78d6', '#008300', '#e87ba4'];
        $poinTercapai = array_sum(array_column($keislaman['komponen'], 'skor'));
        $sisaPoin = round(max(0, $keislaman['maks_poin'] - $poinTercapai), 2);
        // Ikon tiap poin: kompas (prinsip), masjid (kewajiban), dua orang (perilaku).
        $ikonKomponen = [
            'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z M14.8 9.2l-1.9 4.7-4.7 1.9 1.9-4.7 4.7-1.9Z',
            'M3.5 20.5h17 M5.5 20.5v-6.2a6.5 6.5 0 0 1 13 0v6.2 M12 3.2c1.4 1.5 2.1 2.5 2.1 3.4a2.1 2.1 0 1 1-4.2 0c0-.9.7-1.9 2.1-3.4Z M10 20.5v-3a2 2 0 1 1 4 0v3',
            'M15.5 8.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z M4.5 19.5a7.5 7.5 0 0 1 15 0 M18.5 10.5a2.5 2.5 0 1 0 0-5',
        ];
    @endphp
    <section class="reveal overflow-hidden rounded-3xl bg-surface shadow-sm ring-1 ring-line">
        {{-- Papan infografis: kualitas iman + 3 poin penyusun --}}
        <div class="relative overflow-hidden px-6 py-8 sm:px-8"
             style="background:
                radial-gradient(60% 80% at 12% 20%, #fdeef1 0%, rgba(253,238,241,0) 70%),
                radial-gradient(55% 75% at 88% 15%, #e7f5fd 0%, rgba(231,245,253,0) 70%),
                radial-gradient(70% 90% at 50% 110%, #eef0fb 0%, rgba(238,240,251,0) 70%),
                #ffffff;">
            <h2 class="text-center text-lg font-bold text-ink">Persentase Keislaman</h2>
            <p class="mt-1 text-center text-xs font-semibold uppercase tracking-widest text-ink-soft">Karakter Iman {{ $anak }}</p>

            {{-- Hati + kualitas iman. Teks dibatasi lebarnya agar label terpanjang
                 ("Perlu Bimbingan") tetap berada di dalam lengkung hati. --}}
            <div class="relative mx-auto mt-4 flex h-52 w-72 items-center justify-center">
                <svg viewBox="0 0 288 208" class="absolute inset-0 h-full w-full" aria-hidden="true">
                    <defs>
                        <linearGradient id="gradHati" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#f0abcb"/>
                            <stop offset="100%" stop-color="#8b7fd4"/>
                        </linearGradient>
                    </defs>
                    <path d="M144 196C144 196 20 126 20 68C20 40 42 20 70 20C94 20 124 36 144 62C164 36 194 20 218 20C246 20 268 40 268 68C268 126 144 196 144 196Z"
                          fill="none" stroke="url(#gradHati)" stroke-width="6" stroke-linejoin="round"/>
                    <path d="M144 172C144 172 38 116 38 68C38 46 56 32 78 32C98 32 126 48 144 72C162 48 190 32 210 32C232 32 250 46 250 68C250 116 144 172 144 172Z"
                          fill="none" stroke="url(#gradHati)" stroke-width="3" stroke-linejoin="round" opacity="0.55"/>
                </svg>
                {{-- Label pakai warna teks gelap, bukan warna kategori: sebagian warna
                     kategori (kuning/abu) terlalu tipis kontrasnya di atas latar terang. --}}
                <div class="relative -mt-4 w-40 text-center">
                    <p class="text-xs font-semibold text-ink-soft">Kualitas Iman</p>
                    <p class="mt-0.5 text-xl font-extrabold uppercase leading-tight text-ink">{{ $keislaman['label'] }}</p>
                    <span class="mt-1.5 inline-block h-1.5 w-8 rounded-full" style="background:{{ $warnaIslam }}"></span>
                </div>
            </div>

            {{-- Tiga poin penyusun --}}
            <ul class="mx-auto mt-2 flex max-w-lg flex-wrap items-start justify-center gap-x-6 gap-y-5">
                @foreach ($keislaman['komponen'] as $i => $k)
                    <li class="flex w-24 flex-col items-center text-center sm:w-28">
                        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-surface shadow-sm ring-2"
                              style="--tw-ring-color:{{ $hexKomponen[$i] }}">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7"
                                 stroke="{{ $hexKomponen[$i] }}" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $ikonKomponen[$i] }}"/>
                            </svg>
                        </span>
                        <p class="mt-2 text-sm font-bold leading-tight text-ink">{{ $k['label'] }}</p>
                        <p class="text-xs text-ink-soft">({{ $k['aspek'] }})</p>
                        <p class="mt-1 text-sm font-bold tabular-nums" style="color:{{ $hexKomponen[$i] }}">{{ number_format($k['skor'], 1) }}<span class="text-ink-soft">/10</span></p>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Rincian angka --}}
        <div class="border-t border-line p-6 sm:p-8">
        <h3 class="text-sm font-bold text-ink">Rincian nilai</h3>
        <p class="mt-1 text-sm text-ink-soft">
            Seberapa penuh cincin menunjukkan capaian {{ $anak }}, dan tiap warna menunjukkan
            sumbangan masing-masing poin.
        </p>

        <div class="mt-5 grid items-center gap-6 sm:grid-cols-[minmax(0,15rem)_1fr]">
            {{-- Donat --}}
            <div class="relative mx-auto h-56 w-56">
                <canvas id="donatKeislaman" aria-label="Diagram donat persentase keislaman"></canvas>
                <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center text-center">
                    <p class="text-3xl font-extrabold leading-none" style="color:{{ $warnaIslam }}">{{ $keislaman['persen'] }}%</p>
                    <p class="mt-1 text-xs font-semibold text-ink-soft">{{ $keislaman['label'] }}</p>
                </div>
            </div>

            {{-- Legenda + nilai tiap poin --}}
            <div>
                <ul class="space-y-2.5">
                    @foreach ($keislaman['komponen'] as $i => $k)
                        <li class="flex items-center justify-between gap-3 text-sm">
                            <span class="flex min-w-0 items-center gap-2.5">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full" style="background:{{ $hexKomponen[$i] }}"></span>
                                <span class="truncate font-medium text-ink">{{ $k['label'] }}</span>
                                <span class="shrink-0 text-xs text-ink-soft">({{ $k['sumber'] }})</span>
                            </span>
                            <span class="shrink-0 font-semibold tabular-nums text-ink">{{ number_format($k['skor'], 1) }}<span class="font-normal text-ink-soft">/10</span></span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 flex items-center justify-between gap-3 border-t border-line pt-3 text-sm">
                    <span class="font-semibold text-ink">Rata-rata keislaman</span>
                    <span class="font-bold tabular-nums" style="color:{{ $warnaIslam }}">{{ number_format($keislaman['skor'], 2) }}<span class="font-normal text-ink-soft">/10</span></span>
                </div>
            </div>
        </div>

        {{-- Catatan kesimpulan --}}
        <div class="mt-6 rounded-2xl bg-brand-soft p-4">
            <p class="text-sm font-semibold text-ink">Catatan</p>
            <p class="mt-1 text-sm leading-relaxed text-ink-soft">
                Poin keislaman diambil dari <strong class="text-ink">aqidah</strong> (prinsip),
                <strong class="text-ink">ibadah</strong> (kewajiban), serta
                <strong class="text-ink">adab dan akhlaq</strong> (perilaku). Rata-rata ketiganya adalah
                {{ number_format($keislaman['skor'], 2) }} dari 10, sehingga keislaman {{ $anak }}
                masuk kategori <strong style="color:{{ $warnaIslam }}">{{ $keislaman['label'] }}</strong>.
            </p>
        </div>
        </div>
    </section>

    <p class="reveal px-1 text-center text-sm text-ink-soft">
        Semua ini bermuara pada satu pertanyaan:
        <span class="font-semibold text-ink">apa langkah nyata yang bisa diambil untuk {{ $anak }}?</span> &darr;
    </p>

    {{-- ════════════ BABAK 4 — LANGKAH SELANJUTNYA (payoff) ════════════ --}}
    <p class="reveal px-1 pt-4 text-xs font-bold uppercase tracking-[0.2em]" style="color:{{ $primary }}">
        Babak 4 dari 4 &bull; Langkah Selanjutnya
    </p>

    {{-- Rekomendasi jurusan & profesi --}}
    @foreach ([['Rekomendasi Jurusan', $rek_jurusan, 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5'], ['Rekomendasi Profesi', $rek_profesi, 'M20.25 14.15v4.073a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V14.15M18 18.75h-2.25m-7.5 0H6M12 4.5v9m0-9a3.75 3.75 0 0 0-3.75 3.75M12 4.5a3.75 3.75 0 0 1 3.75 3.75M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6a2.25 2.25 0 0 1 2.25-2.25h3.75a2.25 2.25 0 0 1 2.25 2.25v3.776']] as [$judul, $items, $icon])
        <section class="reveal">
            <h2 class="mb-4 flex items-center gap-2 px-1 text-lg font-bold text-ink">
                <svg class="h-5 w-5" style="color:{{ $primary }}" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $judul }}
            </h2>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($items as $it)
                    <div class="rounded-3xl bg-surface p-5 shadow-sm ring-1 ring-line">
                        <div class="flex items-start justify-between gap-2">
                            <p class="font-semibold text-ink">{{ $it['nama'] }}</p>
                            <span class="shrink-0 text-sm font-bold" style="color:{{ $primary }}">{{ $it['kecocokan'] }}%</span>
                        </div>
                        <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-line">
                            <div class="h-full rounded-full" style="width:{{ $it['kecocokan'] }}%; background:{{ $primary }}"></div>
                        </div>
                        <p class="mt-2 text-sm text-ink-soft">{{ $it['alasan'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-ink-soft">Belum ada rekomendasi (data karakter belum lengkap).</p>
                @endforelse
            </div>
        </section>
    @endforeach

    {{-- Saran pengembangan --}}
    <section class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <h2 class="mb-5 text-lg font-bold text-ink">Saran Pengembangan</h2>
        <div class="grid gap-6 md:grid-cols-3">
            <div>
                <p class="mb-2 font-semibold text-ink">Soft Skill yang Perlu Dilatih</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($saran['soft_skill'] as $s)
                        <span class="rounded-full px-3 py-1 text-sm font-medium" style="background:rgba(34,166,232,.12); color:{{ $primary }}">{{ ucfirst($s) }}</span>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="mb-2 font-semibold text-ink">Aktivitas yang Disarankan</p>
                <ul class="space-y-1.5 text-sm text-ink-soft">
                    @foreach ($saran['aktivitas'] as $a)
                        <li class="flex gap-2"><span style="color:{{ $primary }}">✓</span><span>{{ $a }}</span></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="mb-2 font-semibold text-ink">Cara Mengembangkan Potensi</p>
                <ul class="space-y-1.5 text-sm text-ink-soft">
                    @foreach ($saran['cara'] as $c)
                        <li><span class="font-medium text-ink">{{ ucfirst($c['karakter']) }}:</span> {{ $c['saran'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    {{-- Infografis ringkas seluruh hasil — dirancang agar bisa diunduh jadi gambar --}}
    @php
        $gayaTop = $gaya_lengkap[0] ?? null;
        $hexGaya = ['#2a78d6', '#008300', '#e87ba4'];
        $hexHati = ['#4a3aa7', '#e87ba4', '#eda100'];
    @endphp
    <section class="reveal space-y-3">
        <div id="infografis" class="overflow-hidden rounded-3xl ring-1 ring-line"
             style="background:
                radial-gradient(45% 55% at 8% 12%, #fdeef1 0%, rgba(253,238,241,0) 70%),
                radial-gradient(45% 55% at 92% 10%, #e7f5fd 0%, rgba(231,245,253,0) 70%),
                radial-gradient(60% 60% at 50% 105%, #eef0fb 0%, rgba(238,240,251,0) 70%),
                #ffffff;">
            <div class="p-6 sm:p-8">
                {{-- Judul --}}
                <div class="text-center">
                    <p class="text-sm font-bold text-ink-soft sm:text-base">Profil Karakter &amp; Potensi Belajar</p>
                    <p class="text-2xl font-extrabold leading-tight text-ink sm:text-3xl">{{ $anak }}</p>
                </div>

                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    {{-- Kolom kiri: iman & bahasa hati --}}
                    <div class="space-y-5">
                        <h3 class="text-base font-extrabold leading-tight text-ink">Karakter Iman<br class="hidden sm:block"> &amp; Bahasa Hati</h3>

                        {{-- Kualitas iman --}}
                        <div class="rounded-2xl bg-surface/70 p-4 ring-1 ring-line">
                            <p class="text-xs font-semibold text-ink-soft">Kualitas Iman</p>
                            <p class="text-xl font-extrabold uppercase leading-tight text-ink">{{ $keislaman['label'] }}</p>
                            <span class="mt-1 inline-block h-1.5 w-8 rounded-full" style="background:{{ $warnaIslam }}"></span>
                            <ul class="mt-3 flex flex-wrap gap-x-4 gap-y-2">
                                @foreach ($keislaman['komponen'] as $i => $k)
                                    <li class="flex items-center gap-1.5 text-xs">
                                        <span class="h-2 w-2 rounded-full" style="background:{{ $hexKomponen[$i] }}"></span>
                                        <span class="font-semibold text-ink">{{ $k['label'] }}</span>
                                        <span class="text-ink-soft">{{ number_format($k['skor'], 1) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Hirarki bahasa hati --}}
                        <div>
                            <p class="text-sm font-bold text-ink">Hirarki Bahasa Hati</p>
                            <ol class="mt-2 space-y-1.5">
                                @foreach ($bahasa_hati as $i => $h)
                                    <li class="flex items-center justify-between gap-3 rounded-xl px-3 py-2 text-sm text-white"
                                        style="background:{{ $hexHati[$i] ?? '#5b6480' }}">
                                        <span class="font-semibold">{{ $i + 1 }}. Bahasa {{ $h['label'] }}</span>
                                        <span class="text-xs font-bold tabular-nums">{{ number_format($h['skor'], 1) }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>

                        {{-- Pemicu motivasi --}}
                        @if (! empty($pemicu_motivasi))
                            <div>
                                <p class="text-sm font-bold text-ink">Pemicu Motivasi Efektif</p>
                                <p class="text-xs text-ink-soft">Sesuai bahasa hati {{ $bahasa_hati[0]['label'] ?? '-' }}</p>
                                <ul class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach ($pemicu_motivasi as $m)
                                        <li class="rounded-full bg-accent-soft px-3 py-1 text-xs font-semibold text-ink">{{ $m }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- Kolom kanan: karakter & gaya belajar --}}
                    <div class="space-y-5">
                        <h3 class="text-base font-extrabold leading-tight text-ink md:text-right">Karakter<br class="hidden sm:block"> &amp; Gaya Belajar</h3>

                        @if ($gayaTop)
                            <div class="rounded-2xl bg-surface/70 p-4 ring-1 ring-line">
                                <p class="text-xs font-semibold text-ink-soft">Dominasi Gaya Belajar</p>
                                <p class="text-xl font-extrabold leading-tight text-ink">{{ $gayaTop['tipe'] }}</p>
                                <p class="text-sm font-semibold" style="color:{{ $hexGaya[0] }}">{{ $gayaTop['label_arab'] }}</p>
                                <p class="mt-1.5 text-xs leading-relaxed text-ink-soft">{{ ucfirst($gayaTop['arti']) }}.</p>
                            </div>

                            @if ($gayaTop['lingkungan'])
                                <div class="rounded-2xl bg-brand-soft p-4">
                                    <p class="text-sm font-bold text-ink">Lingkungan Belajar Ideal</p>
                                    <p class="mt-1 text-xs leading-relaxed text-ink-soft">{{ $gayaTop['lingkungan'] }}</p>
                                </div>
                            @endif
                        @endif

                        <div>
                            <p class="text-sm font-bold text-ink">Peringkat Gaya Belajar</p>
                            <ol class="mt-2 space-y-2">
                                @foreach ($gaya_lengkap as $i => $g)
                                    <li class="flex items-start gap-2.5">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
                                              style="background:{{ $hexGaya[$i] ?? '#5b6480' }}">{{ $i + 1 }}</span>
                                        <span class="min-w-0">
                                            <span class="block text-sm font-bold leading-tight text-ink">{{ $g['label_arab'] }} ({{ $g['tipe'] }})</span>
                                            <span class="block text-xs text-ink-soft">{{ ucfirst($g['arti']) }} &middot; {{ number_format($g['skor'], 1) }}/10</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>

                <p class="mt-6 border-t border-line pt-3 text-center text-[11px] text-ink-soft">
                    Si Bakat Hebat &middot; Tes Karakter TB-40 &middot; {{ $observasi->tanggal?->format('d-m-Y') }}
                </p>
            </div>
        </div>

        <div class="text-center">
            <button type="button" id="unduhInfografis"
                    class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-5 py-2.5 text-sm font-semibold text-ink transition hover:bg-brand-soft disabled:cursor-not-allowed disabled:opacity-60">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                <span id="labelUnduhInfografis">Unduh infografis (PNG)</span>
            </button>
            <p id="gagalInfografis" class="mt-2 hidden text-xs font-medium text-red-600">
                Gagal membuat gambar. Silakan coba lagi, atau gunakan tombol Download PDF di bawah.
            </p>
        </div>
    </section>

    {{-- Penutup: ajakan unduh + kirim email + disclaimer --}}
    <section id="simpan-laporan" class="reveal rounded-3xl bg-surface p-6 text-center shadow-sm ring-1 ring-line sm:p-8">
        <h2 class="text-lg font-bold text-ink">Simpan laporan {{ $anak }}</h2>
        <p class="mx-auto mt-1 max-w-md text-sm text-ink-soft">
            Unduh versi PDF-nya untuk arsip sekolah, atau kirim langsung ke email orang tua.
        </p>
        <a href="{{ route('hasil.pdf', $observasi) }}"
           class="mt-4 inline-flex items-center gap-2 rounded-full px-6 py-3 font-semibold text-white shadow-sm hover:opacity-90 transition"
           style="background:{{ $primary }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Download PDF
        </a>

        {{-- Kirim laporan PDF ke email --}}
        <div class="mx-auto mt-6 max-w-md border-t border-line pt-6">
            @if (session('kirim_sukses'))
                <p class="mb-4 rounded-xl bg-brand-soft px-4 py-3 text-sm font-medium text-ink">
                    {{ session('kirim_sukses') }}
                </p>
            @endif

            <p class="text-sm font-semibold text-ink">Kirim laporan PDF lewat email</p>
            <p class="mt-1 text-xs text-ink-soft">
                Laporan lengkap dikirim sebagai lampiran PDF. Pengiriman butuh beberapa detik.
            </p>

            <form method="POST" action="{{ route('hasil.kirim-email', $observasi) }}"
                  x-data="{ mengirim: false }" @submit="mengirim = true"
                  class="mt-3 flex flex-col gap-2 sm:flex-row">
                @csrf
                <label for="email-tujuan" class="sr-only">Alamat email tujuan</label>
                <input type="email" name="email" id="email-tujuan" required
                       value="{{ old('email', $observasi->peserta->user->email) }}"
                       placeholder="nama@email.com"
                       class="w-full flex-1 rounded-full border border-line bg-page px-4 py-2.5 text-sm text-ink placeholder:text-ink-soft/60 focus:border-brand focus:outline-none">
                <button type="submit" x-bind:disabled="mengirim"
                        class="inline-flex items-center justify-center gap-2 rounded-full border border-line px-5 py-2.5 text-sm font-semibold text-ink transition hover:bg-brand-soft disabled:cursor-not-allowed disabled:opacity-60">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                    <span x-text="mengirim ? 'Mengirim…' : 'Kirim'">Kirim</span>
                </button>
            </form>

            @error('email')
                <p class="mt-2 text-left text-xs font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <footer class="reveal rounded-3xl bg-line p-5 text-center">
        <p class="mx-auto max-w-2xl text-xs leading-relaxed text-ink-soft">
            <strong>Disclaimer:</strong> Hasil tes ini merupakan alat bantu untuk mengenali potensi anak, dan
            <strong>bukan satu-satunya dasar</strong> dalam menentukan jurusan maupun profesi. Gunakan sebagai bahan
            pertimbangan bersama minat, usaha, bimbingan guru, dan orang tua.
        </p>
        <p class="mt-3">
            <a href="{{ route('anak.index') }}" class="text-sm font-semibold hover:underline" style="color:{{ $primary }}">&larr; Kembali ke Anak Didik</a>
        </p>
    </footer>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reveal bertahap saat elemen masuk layar (bukan sekaligus saat halaman dibuka).
        const reveals = document.querySelectorAll('.reveal');
        reveals.forEach(el => { el.style.animationPlayState = 'paused'; });

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.style.animationPlayState = 'running';
                        io.unobserve(e.target);
                    }
                });
            }, { rootMargin: '0px 0px -10% 0px' });
            reveals.forEach(el => io.observe(el));
        } else {
            reveals.forEach(el => { el.style.animationPlayState = 'running'; });
        }

        // Bar progres baca.
        const bar = document.getElementById('bacaProgres');
        const onScroll = () => {
            const h = document.documentElement.scrollHeight - window.innerHeight;
            const p = h > 0 ? (window.scrollY / h) * 100 : 0;
            bar.style.width = Math.min(100, Math.max(0, p)) + '%';
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        // Unduh infografis jadi PNG. Animasi "reveal" dimatikan sementara supaya
        // elemen tidak tertangkap dalam keadaan setengah transparan.
        const tombolUnduh = document.getElementById('unduhInfografis');
        const papan = document.getElementById('infografis');
        if (tombolUnduh && papan) {
            const label = document.getElementById('labelUnduhInfografis');
            const gagal = document.getElementById('gagalInfografis');
            const teksAwal = label.textContent;

            tombolUnduh.addEventListener('click', async () => {
                if (typeof html2canvas !== 'function') {
                    gagal.classList.remove('hidden');
                    return;
                }
                gagal.classList.add('hidden');
                tombolUnduh.disabled = true;
                label.textContent = 'Menyiapkan gambar…';

                try {
                    const canvas = await html2canvas(papan, {
                        scale: Math.min(2, window.devicePixelRatio || 1) * 1.5,
                        backgroundColor: '#ffffff',
                        useCORS: true,
                        logging: false,
                    });
                    const tautan = document.createElement('a');
                    tautan.download = @json('infografis-'.\Illuminate\Support\Str::slug($anak).'-'.$observasi->id.'.png');
                    tautan.href = canvas.toDataURL('image/png');
                    tautan.click();
                } catch (e) {
                    gagal.classList.remove('hidden');
                } finally {
                    tombolUnduh.disabled = false;
                    label.textContent = teksAwal;
                }
            });
        }

        // Donat keislaman: cincin penuh = poin maksimal, jadi panjang bagian
        // berwarna menunjukkan capaian, bukan sekadar perbandingan antar poin.
        const donat = document.getElementById('donatKeislaman');
        if (donat && window.Chart) {
            new Chart(donat, {
                type: 'doughnut',
                data: {
                    labels: @json(array_column($keislaman['komponen'], 'label')).concat(['Belum tercapai']),
                    datasets: [{
                        data: @json(array_map(fn ($k) => $k['skor'], $keislaman['komponen'])).concat([{{ $sisaPoin }}]),
                        backgroundColor: @json($hexKomponen).concat(['#e4eaf3']),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (item) => item.dataIndex === {{ count($keislaman['komponen']) }}
                                    ? 'Belum tercapai: ' + item.parsed.toFixed(2) + ' poin'
                                    : item.label + ': ' + item.parsed.toFixed(2) + ' dari 10',
                            },
                        },
                    },
                },
            });
        }

        const ctx = document.getElementById('radarKarakter');
        if (ctx && window.Chart) {
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: @json($radarLabels),
                    datasets: [{
                        label: 'Skor',
                        data: @json($radarData),
                        fill: true,
                        backgroundColor: 'rgba(34,166,232,0.15)',
                        borderColor: '{{ $primary }}',
                        borderWidth: 2,
                        pointBackgroundColor: '{{ $primary }}',
                        pointRadius: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: true } },
                    scales: {
                        r: {
                            min: 0, max: 10,
                            ticks: { stepSize: 2, backdropColor: 'transparent', color: '#5b6480', font: { size: 9 } },
                            grid: { color: 'rgba(148,163,184,0.25)' },
                            angleLines: { color: 'rgba(148,163,184,0.25)' },
                            pointLabels: { color: '#5b6480', font: { size: 9 } },
                        },
                    },
                },
            });
        }
    });
</script>
</body>
</html>
