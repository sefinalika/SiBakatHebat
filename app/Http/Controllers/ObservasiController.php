<?php

namespace App\Http\Controllers;

use App\Http\Requests\JawabanRequest;
use App\Mail\HasilTesMail;
use App\Models\Observasi;
use App\Models\Soal;
use App\Services\KalkulasiTB40Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ObservasiController extends Controller
{
    public function __construct(
        private readonly KalkulasiTB40Service $kalkulasi,
    ) {}

    /**
     * GET /soal — Halaman soal. Guard: butuh observasi_id di session.
     */
    public function showSoal(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('observasi_id')) {
            return redirect()->route('anak.index')
                ->with('status', 'Pilih anak didik lalu tekan "Mulai Tes".');
        }

        $labelBagian = [
            'aqidah' => 'Aqidah',
            'ibadah' => 'Ibadah',
            'karakter_belajar' => 'Karakter Belajar',
            'karakter_bakat' => 'Karakter Bakat',
        ];

        $soalList = Soal::orderBy('nomor_soal')
            ->get(['nomor_soal', 'teks', 'bagian'])
            ->map(fn ($s) => [
                'nomor' => $s->nomor_soal,
                'teks' => $s->teks,
                'bagian' => $labelBagian[$s->bagian] ?? $s->bagian,
            ])
            ->values();

        return view('observasi.soal', [
            'jumlah_soal' => $soalList->count(),
            'soalList' => $soalList,
        ]);
    }

    /**
     * POST /soal — Simpan 76 jawaban, proses hasil, kirim email, redirect ke hasil.
     */
    public function storeSoal(JawabanRequest $request): RedirectResponse
    {
        $observasiId = $request->session()->get('observasi_id');

        if (! $observasiId) {
            return redirect()->route('anak.index');
        }

        $observasi = Observasi::with('peserta')->findOrFail($observasiId);

        // Observasi ini harus milik akun yang sedang login.
        if ($observasi->peserta?->user_id !== $request->user()->id) {
            abort(403, 'Tes ini bukan milik akun Anda.');
        }

        DB::transaction(function () use ($request, $observasi) {
            $observasi->jawaban()->delete();

            $now = now();
            $rows = [];
            foreach ($request->validated()['jawaban'] as $nomorSoal => $nilai) {
                $rows[] = [
                    'observasi_id' => $observasi->id,
                    'nomor_soal' => (int) $nomorSoal,
                    'nilai' => (int) $nilai,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $observasi->jawaban()->insert($rows);

            $this->kalkulasi->prosesHasil($observasi);
        });

        // Kirim hasil ke email user yang login.
        $this->kirimHasilKeEmail($request, $observasi);

        $request->session()->forget('observasi_id');

        return redirect()->route('hasil.show', $observasi);
    }

    /**
     * Kirim hasil ke email guru/wali. Di hosting tanpa queue worker, gunakan send() langsung.
     */
    private function kirimHasilKeEmail(Request $request, Observasi $observasi): void
    {
        $email = $request->user()?->email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new HasilTesMail($observasi));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email hasil tes.', [
                'observasi_id' => $observasi->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            report($e);
        }
    }
}
