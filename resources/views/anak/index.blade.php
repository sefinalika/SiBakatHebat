@extends('layouts.app')

@section('title', 'Anak Didik')

@section('content')
    <div class="w-full max-w-3xl">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-ink">Anak Didik</h1>
                <p class="mt-1 text-ink-soft">
                    Daftarkan anak yang akan Anda amati. Satu akun boleh punya banyak anak,
                    dan tiap anak boleh dites berkali-kali.
                </p>
            </div>
            <a href="{{ route('anak.create') }}"
               class="shrink-0 rounded-full bg-brand px-5 py-3 font-semibold text-white hover:bg-brand-dark transition">
                + Tambah Anak
            </a>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-brand/30 bg-brand-soft p-4 text-sm text-ink">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @if ($daftar->isEmpty())
            <div class="mt-8 rounded-2xl border border-dashed border-line bg-surface p-10 text-center">
                <p class="font-semibold text-ink">Belum ada anak didik.</p>
                <p class="mt-1 text-sm text-ink-soft">
                    Tambahkan anak pertama Anda, lalu mulai tesnya (76 pertanyaan, &plusmn; 15 menit).
                </p>
                <a href="{{ route('anak.create') }}"
                   class="mt-5 inline-block rounded-full bg-brand px-6 py-3 font-semibold text-white hover:bg-brand-dark transition">
                    + Tambah Anak Pertama
                </a>
            </div>
        @else
            <div class="mt-8 space-y-3">
                @foreach ($daftar as $anak)
                    @php $terakhir = $anak->observasi->first(); @endphp
                    <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="text-lg font-bold text-ink">{{ $anak->nama }}</p>
                                <p class="mt-0.5 text-sm text-ink-soft">
                                    {{ ucfirst($anak->jenis_kelamin) }} &bull; {{ $anak->umur() }} tahun &bull;
                                    {{ $anak->nama_sekolah }}@if ($anak->kelas) ({{ $anak->kelas }})@endif
                                </p>
                                <p class="text-sm text-ink-soft">{{ $anak->kota }}, {{ $anak->provinsi }}</p>

                                @if ($anak->tes_selesai_count)
                                    <span class="mt-2 inline-block rounded-full bg-accent-soft px-3 py-1 text-xs font-semibold text-ink">
                                        {{ $anak->tes_selesai_count }} tes selesai &bull;
                                        terakhir {{ $terakhir?->tanggal?->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="mt-2 inline-block rounded-full bg-page px-3 py-1 text-xs font-medium text-ink-soft">
                                        Belum pernah dites
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                @if ($terakhir)
                                    <a href="{{ route('hasil.show', $terakhir) }}"
                                       class="rounded-full border border-line px-4 py-2 text-sm font-semibold text-ink hover:bg-brand-soft transition">
                                        Lihat Hasil
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('anak.mulai', $anak) }}">
                                    @csrf
                                    <button type="submit"
                                            class="rounded-full bg-brand px-4 py-2 text-sm font-semibold text-white hover:bg-brand-dark transition">
                                        {{ $anak->tes_selesai_count ? 'Tes Ulang' : 'Mulai Tes' }}
                                    </button>
                                </form>

                                <a href="{{ route('anak.edit', $anak) }}"
                                   class="rounded-full border border-line px-3 py-2 text-sm text-ink-soft hover:bg-page transition">
                                    Ubah
                                </a>

                                @if (! $anak->tes_selesai_count)
                                    <form method="POST" action="{{ route('anak.destroy', $anak) }}"
                                          onsubmit="return confirm('Hapus {{ $anak->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="rounded-full border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <a href="{{ route('riwayat') }}" class="mt-6 inline-block text-sm font-semibold text-brand hover:text-brand-dark transition">
                Lihat seluruh riwayat tes &rarr;
            </a>
        @endif
    </div>
@endsection
