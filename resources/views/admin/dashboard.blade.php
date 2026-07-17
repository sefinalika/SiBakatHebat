@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
@endpush

@section('content')
    <div class="w-full max-w-5xl">
        <h1 class="mb-1 text-3xl font-extrabold text-ink">Dashboard {{ auth()->user()->roleLabel() }}</h1>
        <p class="mb-6 text-ink-soft">Rekap peserta &amp; hasil tes Si Bakat Hebat.</p>

        {{-- Kartu ringkasan --}}
        <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <p class="text-sm text-ink-soft">Total Peserta</p>
                <p class="mt-1 text-3xl font-extrabold text-ink">{{ number_format($totalPeserta) }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <p class="text-sm text-ink-soft">Total Tes</p>
                <p class="mt-1 text-3xl font-extrabold text-ink">{{ number_format($totalTes) }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-surface p-5 shadow-sm">
                <p class="text-sm text-ink-soft">Provinsi Teratas</p>
                <p class="mt-1 text-xl font-bold text-brand">{{ $sebaran[0]['provinsi'] ?? '-' }}
                    <span class="text-sm text-ink-soft">({{ $sebaran[0]['jumlah'] ?? 0 }})</span></p>
            </div>
        </div>

        {{-- Daftar hasil tes peserta --}}
        <div class="mb-8 rounded-2xl border border-line bg-surface p-4 shadow-sm sm:p-5">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="font-bold text-ink">
                    Hasil Tes Peserta
                    <span class="ml-1 text-sm font-normal text-ink-soft">{{ number_format($hasilTes->total()) }} data</span>
                </h2>
                <a href="{{ route('admin.export', request()->query()) }}"
                   class="rounded-full bg-accent px-4 py-2 text-sm font-semibold text-ink hover:brightness-95 transition">
                    Unduh CSV
                </a>
            </div>

            {{-- Pencarian & filter --}}
            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4 flex flex-wrap gap-2">
                <input type="text" name="cari" value="{{ $filter['cari'] }}"
                       placeholder="Cari nama anak atau sekolah…"
                       class="min-w-[14rem] flex-1 rounded-xl border border-line bg-page px-4 py-2.5 text-ink
                              placeholder-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/40">
                <select name="provinsi"
                        class="rounded-xl border border-line bg-page px-4 py-2.5 text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/40">
                    <option value="">Semua Provinsi</option>
                    @foreach ($provinsiList as $prov)
                        <option value="{{ $prov }}" @selected($filter['provinsi'] === $prov)>{{ $prov }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-full bg-brand px-5 py-2.5 font-semibold text-white hover:bg-brand-dark transition">
                    Cari
                </button>
                @if ($filter['cari'] !== '' || $filter['provinsi'] !== '')
                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-full border border-line px-5 py-2.5 font-semibold text-ink-soft hover:bg-page transition">
                        Reset
                    </a>
                @endif
            </form>

            <div class="-mx-1 overflow-x-auto px-1">
                <table class="w-full min-w-[44rem] text-sm">
                    <thead class="text-left text-ink-soft">
                        <tr>
                            <th class="py-2 pr-2">#</th>
                            <th class="pr-2">Nama Anak</th>
                            <th class="pr-2">Umur</th>
                            <th class="pr-2">Sekolah</th>
                            <th class="pr-2">Daerah</th>
                            <th class="pr-2">Diisi oleh</th>
                            <th class="pr-2">Tanggal</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-ink">
                        @forelse ($hasilTes as $i => $o)
                            <tr class="border-t border-line">
                                <td class="py-2 pr-2 text-ink-soft">{{ $hasilTes->firstItem() + $i }}</td>
                                <td class="pr-2 font-semibold">{{ $o->peserta?->nama ?? '-' }}</td>
                                <td class="whitespace-nowrap pr-2 text-ink-soft">{{ $o->peserta?->umur() }} th</td>
                                <td class="pr-2 text-ink-soft">
                                    {{ $o->peserta?->nama_sekolah ?? '-' }}@if ($o->peserta?->kelas) ({{ $o->peserta->kelas }})@endif
                                </td>
                                <td class="pr-2 text-ink-soft">{{ $o->peserta?->kota }}, {{ $o->peserta?->provinsi }}</td>
                                <td class="pr-2 text-ink-soft">{{ $o->peserta?->user?->name ?? '-' }}</td>
                                <td class="whitespace-nowrap pr-2 text-ink-soft">{{ $o->tanggal?->format('d-m-Y') }}</td>
                                <td class="text-right">
                                    <a href="{{ route('hasil.show', $o) }}" class="font-semibold text-brand hover:text-brand-dark">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-3 text-ink-soft">
                                @if ($filter['cari'] !== '' || $filter['provinsi'] !== '')
                                    Tidak ada hasil yang cocok dengan pencarian/filter.
                                @else
                                    Belum ada hasil tes.
                                @endif
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($hasilTes->hasPages())
                <div class="mt-4">{{ $hasilTes->links() }}</div>
            @endif
        </div>

        {{-- Grafik --}}
        <div class="mb-8 rounded-2xl border border-line bg-surface p-4 shadow-sm sm:p-5">
            <h2 class="mb-4 font-bold text-ink">Jumlah Peserta per Provinsi</h2>
            <div class="h-64 sm:h-80">
                <canvas id="chartProvinsi"></canvas>
            </div>
        </div>

        {{-- Top kota --}}
        <div class="rounded-2xl border border-line bg-surface p-4 shadow-sm sm:p-5">
            <h2 class="mb-4 font-bold text-ink">10 Kota Terbanyak</h2>
            <div class="-mx-1 overflow-x-auto px-1">
                <table class="w-full min-w-[28rem] text-sm">
                    <thead class="text-left text-ink-soft">
                        <tr><th class="py-2">#</th><th>Kota</th><th>Provinsi</th><th class="text-right">Jumlah</th></tr>
                    </thead>
                    <tbody class="text-ink">
                        @forelse ($topKota as $i => $row)
                            <tr class="border-t border-line">
                                <td class="py-2 text-ink-soft">{{ $i + 1 }}</td>
                                <td class="font-medium">{{ $row['kota'] }}</td>
                                <td class="text-ink-soft">{{ $row['provinsi'] }}</td>
                                <td class="text-right font-bold text-brand">{{ $row['jumlah'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-3 text-ink-soft">Belum ada data peserta.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sebaran = @json($sebaran);
            const labels = sebaran.map(s => s.provinsi);
            const data = sebaran.map(s => s.jumlah);

            const ctx = document.getElementById('chartProvinsi');
            if (ctx && window.Chart) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: data,
                            backgroundColor: 'rgba(34, 166, 232, 0.65)',
                            borderColor: '#22a6e8',
                            borderWidth: 1,
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: '#2b2a6b' } } },
                        scales: {
                            x: { ticks: { color: '#5b6480', maxRotation: 90, minRotation: 60, autoSkip: false, font: { size: 9 } }, grid: { color: '#eef2f8' } },
                            y: { beginAtZero: true, ticks: { color: '#5b6480', precision: 0 }, grid: { color: '#eef2f8' } },
                        },
                    },
                });
            }
        });
    </script>
@endsection
