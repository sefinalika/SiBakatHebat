@extends('layouts.app')

@section('title', 'Riwayat Hasil')

@section('content')
    <div class="w-full max-w-2xl">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-extrabold text-ink">Riwayat Hasil Tes</h1>
            <p class="mt-2 text-ink-soft">Seluruh tes yang Anda selesaikan, dikelompokkan per anak didik.</p>
        </div>

        @if ($daftar->isEmpty())
            <div class="rounded-2xl border border-dashed border-line bg-surface p-10 text-center">
                <p class="font-semibold text-ink">Belum ada tes yang selesai.</p>
                <a href="{{ route('anak.index') }}"
                   class="mt-4 inline-block rounded-full bg-brand px-6 py-3 font-semibold text-white hover:bg-brand-dark transition">
                    Ke Daftar Anak Didik &rarr;
                </a>
            </div>
        @else
            <div class="space-y-8">
                @foreach ($daftar as $namaAnak => $tesAnak)
                    @php $total = $tesAnak->count(); @endphp
                    <div>
                        <h2 class="mb-3 px-1 font-bold text-ink">
                            {{ $namaAnak }}
                            <span class="ml-1 rounded-full bg-accent-soft px-2.5 py-0.5 text-xs font-semibold">{{ $total }} tes</span>
                        </h2>

                        <div class="space-y-3">
                            @foreach ($tesAnak as $o)
                                <div class="flex items-center justify-between gap-3 rounded-2xl border border-line bg-surface p-4 shadow-sm">
                                    <div>
                                        <p class="font-semibold text-ink">Tes ke-{{ $total - $loop->index }}
                                            <span class="font-normal text-ink-soft">— Tes Karakter TB-40</span></p>
                                        <p class="text-sm text-ink-soft">
                                            {{ $o->tanggal?->translatedFormat('d F Y') ?? $o->created_at?->format('d-m-Y') }}
                                            &middot; <span class="font-mono text-xs">TB40-{{ str_pad((string) $o->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </p>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-2">
                                        <a href="{{ route('hasil.show', $o) }}"
                                           class="rounded-full bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark transition">Lihat</a>
                                        <a href="{{ route('hasil.pdf', $o) }}"
                                           class="rounded-full border border-line px-4 py-2 text-sm font-semibold text-ink hover:bg-brand-soft transition">PDF</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <p class="mt-8 text-center">
            <a href="{{ route('anak.index') }}" class="text-sm font-semibold text-brand hover:text-brand-dark transition">&larr; Kembali ke Anak Didik</a>
        </p>
    </div>
@endsection
