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

    {{-- Persentase keislaman (dari 9 soal ibadah, no. 10–18) --}}
    @php $warnaIslam = \App\Support\KategoriWarna::hex($keislaman['warna']); @endphp
    <section class="reveal rounded-3xl bg-surface shadow-sm ring-1 ring-line p-6 sm:p-8">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-ink">Persentase Keislaman</h2>
                <p class="mt-1 text-sm text-ink-soft">
                    Dihitung dari {{ $keislaman['total'] }} soal ibadah: membaca Al-Qur'an, muroja'ah, wudhu,
                    sholat wajib, adab Islami, mengajak beribadah, Ramadhan, dan tayamum.
                </p>
            </div>
            <div class="shrink-0 text-right">
                <p class="text-4xl font-extrabold leading-none" style="color:{{ $warnaIslam }}">{{ $keislaman['persen'] }}%</p>
                <span class="mt-2 inline-block rounded-full px-3 py-1 text-xs font-semibold text-white"
                      style="background:{{ $warnaIslam }}">{{ $keislaman['label'] }}</span>
            </div>
        </div>

        <div class="mt-5 h-3 overflow-hidden rounded-full bg-line">
            <div class="h-full rounded-full transition-all" style="width:{{ $keislaman['persen'] }}%; background:{{ $warnaIslam }}"></div>
        </div>
        <p class="mt-2 text-xs text-ink-soft">
            Skor rata-rata {{ number_format($keislaman['skor'], 2) }} dari 10.
        </p>
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

    {{-- Penutup: ajakan unduh + disclaimer --}}
    <section class="reveal rounded-3xl bg-surface p-6 text-center shadow-sm ring-1 ring-line sm:p-8">
        <h2 class="text-lg font-bold text-ink">Simpan laporan {{ $anak }}</h2>
        <p class="mx-auto mt-1 max-w-md text-sm text-ink-soft">
            Unduh versi PDF-nya untuk arsip sekolah atau dibaca bersama orang tua.
        </p>
        <a href="{{ route('hasil.pdf', $observasi) }}"
           class="mt-4 inline-flex items-center gap-2 rounded-full px-6 py-3 font-semibold text-white shadow-sm hover:opacity-90 transition"
           style="background:{{ $primary }}">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
            Download PDF
        </a>
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
