@extends('layouts.app')

@section('title', 'Soal Tes')

@push('head')
<style>
    .slider-sbh { -webkit-appearance: none; appearance: none; height: 10px; border-radius: 9999px; outline: none; }
    .slider-sbh::-webkit-slider-thumb {
        -webkit-appearance: none; appearance: none; width: 26px; height: 26px; border-radius: 9999px;
        background: #ffffff; border: 4px solid #22a6e8; cursor: pointer;
        box-shadow: 0 1px 4px rgba(43, 42, 107, .25);
    }
    .slider-sbh::-moz-range-thumb {
        width: 22px; height: 22px; border-radius: 9999px; background: #ffffff; border: 4px solid #22a6e8; cursor: pointer;
    }
</style>
@endpush

@section('content')
    <div class="w-full max-w-xl" x-data="soalWizard()" x-cloak>
        <form method="POST" action="{{ route('soal.store') }}" x-ref="form">
            @csrf
            <template x-for="s in soal" :key="s.nomor">
                <input type="hidden" :name="`jawaban[${s.nomor}]`" :value="answers[s.nomor]">
            </template>
        </form>

        {{-- Progress --}}
        <div class="mb-6">
            <div class="mb-1.5 flex justify-between text-sm text-ink-soft">
                <span>Pertanyaan <span class="font-semibold text-ink" x-text="current + 1"></span> / <span x-text="total"></span></span>
                <span class="font-semibold text-brand" x-text="percent + '%'"></span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-line">
                <div class="h-full rounded-full bg-brand transition-all duration-300" :style="`width: ${percent}%`"></div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Kategori --}}
        <div class="mb-3">
            <span class="inline-block rounded-full bg-brand-soft px-3 py-1 text-xs font-bold uppercase tracking-wide text-brand-dark"
                  x-text="q.bagian"></span>
        </div>

        {{-- Kartu soal --}}
        <div class="rounded-2xl border border-line bg-surface p-6 sm:p-8 shadow-sm">
            <p class="text-xl sm:text-2xl font-bold leading-snug text-ink" x-text="q.teks"></p>

            <div class="mt-8">
                <div class="mb-2 flex justify-between text-xs font-medium text-ink-soft">
                    <span>Tidak Sesuai</span>
                    <span>Sesuai</span>
                </div>
                <input type="range" min="1" max="10" step="1" class="slider-sbh w-full"
                       x-model.number="answers[q.nomor]"
                       :style="`background: linear-gradient(90deg,#22a6e8 ${(answers[q.nomor]-1)/9*100}%, #e4eaf3 ${(answers[q.nomor]-1)/9*100}%)`">

                <div class="mt-6 flex items-center justify-between rounded-xl bg-page px-4 py-3">
                    <span class="font-medium text-ink-soft">Jawaban Anda</span>
                    <span class="text-3xl font-extrabold text-brand" x-text="answers[q.nomor]"></span>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <div class="mt-5 flex gap-3">
            <button type="button" x-show="current > 0" @click="prev()"
                    class="rounded-full border border-line bg-surface px-5 py-3.5 font-semibold text-ink hover:bg-brand-soft transition">
                &larr; Sebelumnya
            </button>
            <button type="button" @click="advance()"
                    class="group flex-1 rounded-full px-6 py-3.5 font-semibold text-white transition"
                    :class="isLast ? 'bg-accent text-ink hover:brightness-95' : 'bg-brand hover:bg-brand-dark'">
                <span x-text="isLast ? 'Lihat Hasil & Kirim ke Email' : 'Selanjutnya'"></span>
                <span class="inline-block transition-transform group-hover:translate-x-1" x-show="!isLast">&rarr;</span>
            </button>
        </div>
    </div>

    <script>
        function soalWizard() {
            return {
                soal: @json($soalList),
                answers: {},
                current: 0,
                init() {
                    this.soal.forEach(s => { this.answers[s.nomor] = 5; }); // default tengah (skala 1-10)
                },
                get total() { return this.soal.length; },
                get q() { return this.soal[this.current]; },
                get isLast() { return this.current === this.total - 1; },
                get percent() { return Math.round(this.current / this.total * 100); },
                next() { if (this.current < this.total - 1) this.current++; window.scrollTo({ top: 0, behavior: 'smooth' }); },
                prev() { if (this.current > 0) this.current--; window.scrollTo({ top: 0, behavior: 'smooth' }); },
                advance() { this.isLast ? this.$refs.form.submit() : this.next(); },
            };
        }
    </script>
@endsection
