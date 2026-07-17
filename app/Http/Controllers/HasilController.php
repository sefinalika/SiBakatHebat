<?php

namespace App\Http\Controllers;

use App\Models\Observasi;
use App\Services\KalkulasiTB40Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class HasilController extends Controller
{
    public function __construct(
        private readonly KalkulasiTB40Service $kalkulasi,
    ) {}

    /**
     * Hanya pemilik (atau admin) yang boleh melihat hasil.
     */
    private function pastikanBolehAkses(Request $request, Observasi $observasi): void
    {
        $user = $request->user();
        $pemilikId = $observasi->peserta->user_id;

        if (! $user || ($user->id !== $pemilikId && ! $user->canViewDashboard())) {
            abort(403, 'Anda tidak berhak melihat hasil ini.');
        }
    }

    /**
     * Rakit seluruh data hasil yang dibutuhkan view/PDF/API.
     *
     * @return array<string, mixed>
     */
    private function rakitHasil(Observasi $observasi): array
    {
        return $this->kalkulasi->rakitData($observasi);
    }

    /**
     * GET /riwayat — Daftar riwayat tes milik user yang login.
     */
    public function riwayat(Request $request): View
    {
        $daftar = Observasi::query()
            ->whereHas('hasilObservasi')
            ->whereHas('peserta', fn ($q) => $q->where('user_id', $request->user()->id))
            ->with('peserta')
            ->latest('id')
            ->get()
            ->groupBy(fn (Observasi $o) => $o->peserta->nama);

        return view('riwayat', ['daftar' => $daftar]);
    }

    /**
     * GET /hasil/{observasi} — Tampilkan hasil.
     */
    public function tampil(Request $request, Observasi $observasi): View
    {
        $this->pastikanBolehAkses($request, $observasi);

        return view('hasil.laporan', $this->kalkulasi->laporan($observasi));
    }

    /**
     * GET /hasil/{observasi}/pdf — Generate PDF via DomPDF.
     */
    public function exportPdf(Request $request, Observasi $observasi): Response
    {
        $this->pastikanBolehAkses($request, $observasi);

        $pdf = Pdf::loadView('hasil.pdf', $this->rakitHasil($observasi));

        return $pdf->download("hasil-sibakathebat-{$observasi->id}.pdf");
    }

    /**
     * GET /api/hasil/{observasi} — JSON semua data hasil (untuk Chart.js).
     */
    public function apiHasil(Request $request, Observasi $observasi): JsonResponse
    {
        $this->pastikanBolehAkses($request, $observasi);

        $data = $this->rakitHasil($observasi);
        $observasi = $data['observasi'];

        return response()->json([
            'observasi' => [
                'id' => $observasi->id,
                'tanggal' => $observasi->tanggal?->toDateString(),
            ],
            'peserta' => $observasi->peserta,
            'section' => $data['section'],
            'keislaman' => $data['keislaman'],
            'karakter' => $observasi->hasilObservasi->map(fn ($h) => [
                'karakter_id' => $h->karakter_id,
                'kode' => $h->karakter?->kode,
                'nama_karakter' => $h->karakter?->nama_karakter,
                'nama_arab' => $h->karakter?->nama_arab,
                'terjemahan' => $h->karakter?->terjemahan,
                'dimensi' => $h->karakter?->dimensi,
                'urut_grafik' => $h->karakter?->urut_grafik,
                'skor' => (float) $h->skor,
                'kategori' => $h->kategori,
            ])->values(),
            'gaya_belajar' => $data['gaya_belajar'],
            'bahasa_hati' => $data['bahasa_hati'],
            'top' => $data['top']->map(fn ($h) => [
                'karakter_id' => $h->karakter_id,
                'nama_karakter' => $h->karakter?->nama_karakter,
                'nama_arab' => $h->karakter?->nama_arab,
                'terjemahan' => $h->karakter?->terjemahan,
                'skor' => (float) $h->skor,
                'kategori' => $h->kategori,
            ])->values(),
            'bottom' => $data['bottom']->map(fn ($h) => [
                'karakter_id' => $h->karakter_id,
                'nama_karakter' => $h->karakter?->nama_karakter,
                'nama_arab' => $h->karakter?->nama_arab,
                'terjemahan' => $h->karakter?->terjemahan,
                'skor' => (float) $h->skor,
                'kategori' => $h->kategori,
            ])->values(),
        ]);
    }
}
