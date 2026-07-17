<!DOCTYPE html>
<html lang="id">
@php use App\Support\KategoriWarna; @endphp
<head>
    <meta charset="utf-8">
    <title>Hasil Si Bakat Hebat #{{ $observasi->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color:#1f2937; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        h2 { font-size: 13px; margin: 14px 0 6px; }
        h3 { font-size: 11px; margin: 8px 0 4px; color:#047857; text-transform: uppercase; }
        .meta td { padding: 1px 4px; }
        .bar-table { width: 100%; border-collapse: collapse; }
        .bar-table td { padding: 2px 4px; vertical-align: middle; }
        .track { background:#e5e7eb; height: 9px; width: 100%; }
        .fill { height: 9px; }
        .grid2 td { width: 50%; vertical-align: top; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #cbd5e1; padding: 3px; text-align: left; }
        .dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; }
        .legend span { margin-right: 14px; }
        .swatch { display: inline-block; width: 16px; height: 9px; vertical-align: middle; }
    </style>
</head>
<body>
    <h1>Hasil Tes Karakter — Si Bakat Hebat</h1>

    <table class="meta">
        <tr><td><strong>Nama Anak</strong></td><td>: {{ $observasi->peserta->nama }}</td></tr>
        <tr><td><strong>Diisi oleh</strong></td><td>: {{ $observasi->peserta->user->name }} ({{ $observasi->peserta->user->email }})</td></tr>
        <tr><td><strong>Jenis Kelamin</strong></td><td>: {{ ucfirst($observasi->peserta->jenis_kelamin) }} &nbsp; <strong>Tgl Lahir:</strong> {{ $observasi->peserta->tanggal_lahir?->format('d-m-Y') }} &nbsp; <strong>Umur:</strong> {{ $observasi->peserta->umur() }} tahun</td></tr>
        <tr><td><strong>Sekolah</strong></td><td>: {{ $observasi->peserta->nama_sekolah }}</td></tr>
        <tr><td><strong>Daerah</strong></td><td>: {{ $observasi->peserta->kota }}, {{ $observasi->peserta->provinsi }} &nbsp; <strong>Tanggal Tes:</strong> {{ $observasi->tanggal?->format('d-m-Y') }}</td></tr>
    </table>

    <h2>Kesimpulan</h2>
    <p style="text-align: justify;">{{ $kesimpulan['narasi'] }}</p>
    <p style="margin-top:4px;">
        <strong>Dimensi Dominan:</strong> Kinerja {{ $kesimpulan['dimensi_dominan'][0]['label'] ?? '-' }} &nbsp;|&nbsp;
        <strong>Gaya Belajar:</strong> {{ $kesimpulan['gaya_dominan']['label'] ?? '-' }} ({{ $kesimpulan['gaya_dominan']['arti'] ?? '' }}) &nbsp;|&nbsp;
        <strong>Bahasa Hati:</strong> {{ $kesimpulan['hati_dominan']['label'] ?? '-' }}
    </p>

    <h2>Persentase Keislaman</h2>
    @php $warnaIslam = \App\Support\KategoriWarna::hex($keislaman['warna']); @endphp
    <p>
        <strong style="font-size:15px; color:{{ $warnaIslam }};">{{ $keislaman['persen'] }}%</strong>
        &nbsp;&mdash;&nbsp; <strong>{{ $keislaman['label'] }}</strong>
        &nbsp;(rata-rata {{ number_format($keislaman['skor'], 2) }} dari 10)
    </p>
    <table style="width:100%; border-collapse:collapse; margin-top:2px;">
        <tr>
            <td style="background:#e5e7eb; height:10px; padding:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <tr><td style="width:{{ $keislaman['persen'] }}%; background:{{ $warnaIslam }}; height:10px; padding:0;"></td>
                        <td style="padding:0;"></td></tr>
                </table>
            </td>
        </tr>
    </table>
    <p style="margin-top:4px; font-size:10px; color:#6b7280;">
        Dihitung dari {{ $keislaman['total'] }} soal ibadah (no. 10&ndash;18): membaca Al-Qur'an, muroja'ah, wudhu,
        sholat wajib, adab Islami, mengajak beribadah, Ramadhan, dan tayamum.
    </p>

    <h2>Nilai Ringkas</h2>
    <p>
        Aqidah: {{ $section['aqidah'] }} &nbsp;|&nbsp; Ibadah: {{ $section['ibadah'] }} &nbsp;|&nbsp;
        Karakter Belajar: {{ $section['karakter_belajar'] }} &nbsp;|&nbsp; Bakat Umum: {{ $section['bakat_umum'] }}
    </p>

    <h2>Peta Karakter (40 Karakter TB-40)</h2>
    @foreach ($grupDimensi as $dimensi => $items)
        <h3>{{ $dimensi }}</h3>
        <table class="bar-table">
            @foreach ($items->chunk(2) as $pair)
                <tr>
                    @foreach ($pair as $h)
                        <td style="width:22%;">{{ $h->karakter?->nama_karakter }}</td>
                        <td style="width:22%;">
                            <div class="track"><div class="fill" style="width: {{ $h->skor * 10 }}%; background: {{ KategoriWarna::hex($h->kategori) }};"></div></div>
                        </td>
                        <td style="width:6%; text-align:right; color: {{ KategoriWarna::hex($h->kategori) }};"><strong>{{ $h->skor }}</strong></td>
                    @endforeach
                    @if ($pair->count() < 2)<td colspan="3"></td>@endif
                </tr>
            @endforeach
        </table>
    @endforeach

    <p class="legend" style="margin-top:8px;"><strong>Makna Warna:</strong>
        @foreach (KategoriWarna::legenda() as $l)
            <span><span class="swatch" style="background: {{ $l['hex'] }};"></span> {{ $l['label'] }}</span>
        @endforeach
    </p>

    <table class="grid2" style="width:100%;"><tr>
        <td>
            <h2>Bakat Kekuatan (Top 6)</h2>
            @foreach ($top as $h)
                <p style="margin:0 0 6px;">
                    <span class="dot" style="background: {{ KategoriWarna::hex($h->kategori) }};"></span>
                    <strong>{{ $h->karakter?->nama_karakter }}</strong> — {{ $h->karakter?->terjemahan }} ({{ $h->skor }})
                    @if ($h->karakter?->profesi)<br><span style="color:#6b7280;">Profesi: {{ $h->karakter->profesi }}</span>@endif
                    @if ($h->karakter?->jurusan)<br><span style="color:#6b7280;">Jurusan: {{ $h->karakter->jurusan }}</span>@endif
                </p>
            @endforeach
        </td>
        <td>
            <h2>Bakat Kelemahan (Bottom 6)</h2>
            @foreach ($bottom as $h)
                <p style="margin:0 0 6px;">
                    <span class="dot" style="background: {{ KategoriWarna::hex($h->kategori) }};"></span>
                    <strong>{{ $h->karakter?->nama_karakter }}</strong> — {{ $h->karakter?->terjemahan }} ({{ $h->skor }})
                    @if ($h->karakter?->jurusan)<br><span style="color:#6b7280;">Jurusan disarankan: {{ $h->karakter->jurusan }}</span>@endif
                </p>
            @endforeach
        </td>
    </tr></table>

    @php $terkuat = $top->first(); $terlemah = $bottom->first(); @endphp
    @if (($terkuat?->karakter?->sifat_tercela_berlebihan) || ($terlemah?->karakter?->sifat_tercela_melalaikan))
        <h2>Potensi Sifat Tercela &amp; Solusi</h2>
        @if ($terkuat?->karakter?->sifat_tercela_berlebihan)
            <p style="margin:0 0 4px;"><strong>Dari bakat terkuat ({{ $terkuat->karakter->nama_karakter }}) jika berlebihan:</strong>
                {{ $terkuat->karakter->sifat_tercela_berlebihan }}. {{ $terkuat->karakter->cara_memperbaiki_berlebihan }}</p>
        @endif
        @if ($terlemah?->karakter?->sifat_tercela_melalaikan)
            <p style="margin:0;"><strong>Dari bakat terlemah ({{ $terlemah->karakter->nama_karakter }}) jika diabaikan:</strong>
                {{ $terlemah->karakter->sifat_tercela_melalaikan }}. {{ $terlemah->karakter->cara_memperbaiki_melalaikan }}</p>
        @endif
    @endif

    <table class="grid2" style="width:100%;"><tr>
        <td>
            <h2>Gaya Belajar</h2>
            <ol>
                @foreach ($gaya_belajar as $g)<li>{{ $g['label'] }} ({{ $g['arti'] }}) — {{ $g['skor'] }}</li>@endforeach
            </ol>
        </td>
        <td>
            <h2>Bahasa Hati</h2>
            <ol>
                @foreach ($bahasa_hati as $b)<li>{{ $b['label'] }} — {{ $b['skor'] }}</li>@endforeach
            </ol>
        </td>
    </tr></table>
</body>
</html>
