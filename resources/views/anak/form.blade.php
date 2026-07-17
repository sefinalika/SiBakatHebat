@extends('layouts.app')

@section('title', $anak->exists ? 'Ubah Data Anak' : 'Tambah Anak')

@php
    $inputClass = 'w-full rounded-xl border border-line bg-page px-4 py-3 text-ink
                   placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand/40
                   focus:border-brand transition';
@endphp

@section('content')
    <div class="w-full max-w-md">
        <h1 class="text-center text-3xl font-extrabold text-ink">
            {{ $anak->exists ? 'Ubah Data Anak' : 'Tambah Anak Didik' }}
        </h1>
        <p class="mt-2 text-center text-ink-soft">
            Isi data anak yang akan Anda amati. Anda mengisi soal sebagai
            <span class="font-semibold text-ink">guru / wali murid</span> anak ini.
        </p>

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" x-data="wilayahForm()"
              class="mt-8 space-y-3 rounded-2xl border border-line bg-surface p-6 shadow-sm"
              action="{{ $anak->exists ? route('anak.update', $anak) : route('anak.store') }}">
            @csrf
            @if ($anak->exists) @method('PUT') @endif

            {{-- Nama anak --}}
            <input type="text" name="nama" value="{{ old('nama', $anak->nama) }}"
                   placeholder="Nama Anak" maxlength="100" class="{{ $inputClass }}" aria-label="Nama Anak">

            {{-- Jenis kelamin --}}
            <div class="grid grid-cols-2 gap-3">
                @foreach (['laki-laki' => 'Laki-laki', 'perempuan' => 'Perempuan'] as $val => $teks)
                    <label class="cursor-pointer">
                        <input type="radio" name="jenis_kelamin" value="{{ $val }}" class="peer sr-only"
                               @checked(old('jenis_kelamin', $anak->jenis_kelamin) === $val)>
                        <span class="block rounded-xl border border-line bg-page px-4 py-3 text-center font-medium text-ink-soft
                                     peer-checked:border-brand peer-checked:bg-brand-soft peer-checked:text-ink transition">{{ $teks }}</span>
                    </label>
                @endforeach
            </div>

            {{-- Tanggal lahir --}}
            <div>
                <input type="date" name="tanggal_lahir" max="{{ date('Y-m-d') }}" x-model="tanggalLahir"
                       value="{{ old('tanggal_lahir', $anak->tanggal_lahir?->format('Y-m-d')) }}"
                       class="{{ $inputClass }}" aria-label="Tanggal Lahir">
                <p class="mt-1 ml-1 text-xs text-ink-soft">
                    Tanggal lahir anak<span x-show="umur !== null" x-cloak class="font-semibold text-brand"> — Umur: <span x-text="umur"></span> tahun</span>
                </p>
            </div>

            {{-- Sekolah & kelas --}}
            <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $anak->nama_sekolah) }}"
                   placeholder="Nama Sekolah" class="{{ $inputClass }}">
            <input type="text" name="kelas" value="{{ old('kelas', $anak->kelas) }}"
                   placeholder="Kelas (opsional, mis. 4B)" maxlength="50" class="{{ $inputClass }}">

            {{-- Provinsi & kota --}}
            <select name="provinsi" x-model="provinsi" class="{{ $inputClass }}">
                <option value="">Pilih Provinsi</option>
                @foreach ($provinsiList as $prov)
                    <option value="{{ $prov }}">{{ $prov }}</option>
                @endforeach
            </select>

            <select name="kota" x-model="kota" class="{{ $inputClass }}" :disabled="!provinsi">
                <option value="" x-text="provinsi ? 'Pilih Kota / Kabupaten' : 'Pilih provinsi dulu'"></option>
                <template x-for="k in kotaList" :key="k">
                    <option :value="k" x-text="k"></option>
                </template>
            </select>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('anak.index') }}"
                   class="rounded-full border border-line px-5 py-3.5 font-semibold text-ink-soft hover:bg-page transition">
                    Batal
                </a>
                <button type="submit"
                        class="group flex-1 rounded-full bg-brand px-6 py-3.5 font-semibold text-white hover:bg-brand-dark transition">
                    {{ $anak->exists ? 'Simpan Perubahan' : 'Simpan Anak' }}
                    <span class="inline-block transition-transform group-hover:translate-x-1">&rarr;</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function wilayahForm() {
            return {
                wilayah: @json($wilayah),
                provinsi: @js(old('provinsi', $anak->provinsi ?? '')),
                kota: @js(old('kota', $anak->kota ?? '')),
                tanggalLahir: @js(old('tanggal_lahir', $anak->tanggal_lahir?->format('Y-m-d') ?? '')),
                get kotaList() {
                    return this.wilayah[this.provinsi] || [];
                },
                get umur() {
                    if (!this.tanggalLahir) return null;
                    const b = new Date(this.tanggalLahir);
                    if (isNaN(b.getTime())) return null;
                    const t = new Date();
                    let a = t.getFullYear() - b.getFullYear();
                    const m = t.getMonth() - b.getMonth();
                    if (m < 0 || (m === 0 && t.getDate() < b.getDate())) a--;
                    return a >= 0 ? a : null;
                },
            };
        }
    </script>
@endsection
