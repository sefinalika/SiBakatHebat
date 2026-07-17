<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnakRequest;
use App\Models\Observasi;
use App\Models\Peserta;
use App\Support\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Daftar anak didik milik satu akun (guru / wali murid).
 * Satu akun boleh mendaftarkan banyak anak, dan tiap anak boleh dites berkali-kali.
 */
class AnakController extends Controller
{
    /**
     * Pastikan anak ini memang milik akun yang sedang login.
     */
    private function pastikanMilikSendiri(Request $request, Peserta $anak): void
    {
        if ($anak->user_id !== $request->user()->id) {
            abort(403, 'Anak ini bukan milik akun Anda.');
        }
    }

    /**
     * GET /anak — Daftar anak didik + jumlah tes masing-masing.
     */
    public function index(Request $request): View
    {
        $daftar = $request->user()->peserta()
            ->withCount(['observasi as tes_selesai_count' => fn ($q) => $q->whereHas('hasilObservasi')])
            ->with(['observasi' => fn ($q) => $q->whereHas('hasilObservasi')->latest('id')->limit(1)])
            ->orderBy('nama')
            ->get();

        return view('anak.index', ['daftar' => $daftar]);
    }

    /**
     * GET /anak/tambah — Form anak baru.
     */
    public function create(): View
    {
        return view('anak.form', [
            'anak' => new Peserta,
            'wilayah' => Wilayah::all(),
            'provinsiList' => Wilayah::provinsiList(),
        ]);
    }

    /**
     * POST /anak — Simpan anak baru.
     */
    public function store(AnakRequest $request): RedirectResponse
    {
        $anak = Peserta::create($request->validated() + ['user_id' => $request->user()->id]);

        return redirect()->route('anak.index')
            ->with('status', "Anak didik \"{$anak->nama}\" berhasil ditambahkan. Silakan mulai tesnya.");
    }

    /**
     * GET /anak/{anak}/ubah — Form ubah data anak.
     */
    public function edit(Request $request, Peserta $anak): View
    {
        $this->pastikanMilikSendiri($request, $anak);

        return view('anak.form', [
            'anak' => $anak,
            'wilayah' => Wilayah::all(),
            'provinsiList' => Wilayah::provinsiList(),
        ]);
    }

    /**
     * PUT /anak/{anak} — Simpan perubahan data anak.
     */
    public function update(AnakRequest $request, Peserta $anak): RedirectResponse
    {
        $this->pastikanMilikSendiri($request, $anak);

        $anak->update($request->validated());

        return redirect()->route('anak.index')
            ->with('status', "Data \"{$anak->nama}\" berhasil diperbarui.");
    }

    /**
     * DELETE /anak/{anak} — Hapus anak. Ditolak bila sudah punya hasil tes,
     * supaya laporan yang sudah terbit tidak ikut hilang.
     */
    public function destroy(Request $request, Peserta $anak): RedirectResponse
    {
        $this->pastikanMilikSendiri($request, $anak);

        if ($anak->observasi()->whereHas('hasilObservasi')->exists()) {
            return redirect()->route('anak.index')
                ->withErrors(['anak' => "\"{$anak->nama}\" sudah punya hasil tes, jadi tidak bisa dihapus."]);
        }

        $nama = $anak->nama;
        $anak->observasi()->delete(); // observasi kosong (belum selesai)
        $anak->delete();

        return redirect()->route('anak.index')->with('status', "Anak didik \"{$nama}\" dihapus.");
    }

    /**
     * POST /anak/{anak}/mulai — Mulai tes baru untuk anak ini.
     */
    public function mulaiTes(Request $request, Peserta $anak): RedirectResponse
    {
        $this->pastikanMilikSendiri($request, $anak);

        $observasi = Observasi::create([
            'peserta_id' => $anak->id,
            'tanggal' => now()->toDateString(),
        ]);

        $request->session()->put('observasi_id', $observasi->id);

        return redirect()->route('soal.show');
    }
}
