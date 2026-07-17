<!DOCTYPE html>
<html lang="id">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:#f4f6f8; margin:0; padding:24px; color:#1f2937;">
    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
        <div style="background:linear-gradient(90deg,#10b981,#3b82f6); padding:24px; color:#ffffff;">
            <h1 style="margin:0; font-size:20px;">Si Bakat Hebat</h1>
            <p style="margin:4px 0 0; font-size:13px; opacity:.9;">Hasil Tes Karakter TB-40</p>
        </div>

        <div style="padding:24px;">
            <p>Halo <strong>{{ $observasi->peserta->user->name }}</strong>,</p>
            <p>Berikut ringkasan hasil tes karakter untuk anak didik Anda,
                <strong>{{ $observasi->peserta->nama }}</strong>.</p>

            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px 14px; margin:12px 0;">
                <strong style="color:#047857;">Kesimpulan</strong>
                <p style="margin:6px 0 0; text-align:justify;">{{ $kesimpulan['narasi'] }}</p>
            </div>

            <h3 style="margin:20px 0 8px;">Nilai Ringkas</h3>
            <ul style="margin:0; padding-left:18px;">
                <li>Aqidah: {{ $section['aqidah'] }}</li>
                <li>Ibadah: {{ $section['ibadah'] }}</li>
                <li>Karakter Belajar: {{ $section['karakter_belajar'] }}</li>
                <li>Karakter Bakat (umum): {{ $section['bakat_umum'] }}</li>
            </ul>

            <h3 style="margin:20px 0 8px;">Bakat Kekuatan (Top 6)</h3>
            <ol style="margin:0; padding-left:18px;">
                @foreach ($top as $h)
                    <li>{{ $h->karakter?->nama_karakter }} — {{ $h->karakter?->terjemahan }} ({{ $h->skor }})</li>
                @endforeach
            </ol>

            <h3 style="margin:20px 0 8px;">Gaya Belajar</h3>
            <ol style="margin:0; padding-left:18px;">
                @foreach ($gaya_belajar as $g)
                    <li>{{ $g['label'] }} ({{ $g['arti'] }}) — {{ $g['skor'] }}</li>
                @endforeach
            </ol>

            <div style="margin:28px 0; text-align:center;">
                <a href="{{ $urlHasil }}"
                   style="background:#10b981; color:#fff; text-decoration:none; padding:12px 24px; border-radius:8px; font-weight:bold; display:inline-block;">
                    Lihat Hasil Lengkap
                </a>
            </div>

            <p style="font-size:12px; color:#6b7280;">Email ini dikirim otomatis oleh sistem Si Bakat Hebat.</p>
        </div>
    </div>
</body>
</html>
