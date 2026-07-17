<?php

namespace App\Http\Controllers;

use App\Models\Observasi;
use App\Models\Peserta;
use App\Support\Wilayah;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * GET /admin — Dashboard super admin.
     */
    public function index(Request $request): View
    {
        $hasilTes = $this->queryHasil($request)
            ->paginate(20)
            ->withQueryString();

        return view('admin.dashboard', [
            'totalPeserta' => Peserta::count(),
            'totalTes' => Observasi::whereHas('hasilObservasi')->count(),
            'sebaran' => $this->sebaranProvinsi(),
            'topKota' => $this->topKota(),
            'hasilTes' => $hasilTes,
            'provinsiList' => Wilayah::provinsiList(),
            'filter' => [
                'cari' => (string) $request->query('cari', ''),
                'provinsi' => (string) $request->query('provinsi', ''),
            ],
        ]);
    }

    /**
     * GET /admin/export — Unduh data hasil tes (sesuai filter) sebagai CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $rows = $this->queryHasil($request)->cursor();

        $kolom = ['Nama Anak', 'Jenis Kelamin', 'Umur', 'Sekolah', 'Kelas', 'Kota', 'Provinsi', 'Diisi Oleh', 'Email Pengisi', 'Tanggal Tes', 'Kode'];

        return response()->streamDownload(function () use ($rows, $kolom) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF"); // BOM: supaya Excel membaca UTF-8 dengan benar
            fputcsv($out, $kolom);

            foreach ($rows as $o) {
                fputcsv($out, [
                    $o->peserta?->nama,
                    $o->peserta?->jenis_kelamin,
                    $o->peserta?->umur(),
                    $o->peserta?->nama_sekolah,
                    $o->peserta?->kelas,
                    $o->peserta?->kota,
                    $o->peserta?->provinsi,
                    $o->peserta?->user?->name,
                    $o->peserta?->user?->email,
                    $o->tanggal?->format('Y-m-d'),
                    'TB40-'.str_pad((string) $o->id, 5, '0', STR_PAD_LEFT),
                ]);
            }

            fclose($out);
        }, 'hasil-tes-sibakathebat-'.now()->format('Ymd-His').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * GET /api/admin/sebaran — JSON sebaran 34 provinsi (untuk Chart.js).
     */
    public function apiSebaran(): JsonResponse
    {
        return response()->json([
            'sebaran' => $this->sebaranProvinsi(),
            'top_kota' => $this->topKota(),
        ]);
    }

    /**
     * Hasil tes yang sudah selesai, dengan pencarian & filter provinsi.
     * Dipakai bersama oleh tabel dashboard dan export CSV.
     */
    private function queryHasil(Request $request): Builder
    {
        $cari = trim((string) $request->query('cari', ''));
        $provinsi = trim((string) $request->query('provinsi', ''));

        return Observasi::query()
            ->whereHas('hasilObservasi')
            ->with(['peserta.user'])
            ->when($cari !== '', fn (Builder $q) => $q->whereHas('peserta',
                fn (Builder $p) => $p->where('nama', 'like', "%{$cari}%")
                    ->orWhere('nama_sekolah', 'like', "%{$cari}%")
            ))
            ->when($provinsi !== '', fn (Builder $q) => $q->whereHas('peserta',
                fn (Builder $p) => $p->where('provinsi', $provinsi)
            ))
            ->latest('id');
    }

    /**
     * Jumlah peserta per provinsi, mencakup seluruh 34 provinsi (0 bila kosong),
     * diurutkan dari terbanyak.
     *
     * @return array<int, array{provinsi:string, jumlah:int}>
     */
    private function sebaranProvinsi(): array
    {
        $hitung = Peserta::query()
            ->selectRaw('provinsi, COUNT(*) as jumlah')
            ->groupBy('provinsi')
            ->pluck('jumlah', 'provinsi');

        $hasil = [];
        foreach (Wilayah::provinsiList() as $provinsi) {
            $hasil[] = ['provinsi' => $provinsi, 'jumlah' => (int) ($hitung[$provinsi] ?? 0)];
        }

        usort($hasil, fn ($a, $b) => $b['jumlah'] <=> $a['jumlah']);

        return $hasil;
    }

    /**
     * Kota dengan peserta terbanyak (top 10).
     *
     * @return array<int, array{kota:string, provinsi:string, jumlah:int}>
     */
    private function topKota(): array
    {
        return Peserta::query()
            ->selectRaw('kota, provinsi, COUNT(*) as jumlah')
            ->groupBy('kota', 'provinsi')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get()
            ->map(fn ($r) => ['kota' => $r->kota, 'provinsi' => $r->provinsi, 'jumlah' => (int) $r->jumlah])
            ->all();
    }
}
