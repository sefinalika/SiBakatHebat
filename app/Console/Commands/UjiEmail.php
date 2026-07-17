<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * Alat diagnosa pengiriman email, dijalankan lewat SSH di hosting:
 *
 *   php artisan mail:uji tujuan@contoh.com
 *
 * Menampilkan konfigurasi yang benar-benar terbaca aplikasi, lalu mencoba
 * mengirim satu email sederhana dan menampilkan error SMTP apa adanya.
 */
class UjiEmail extends Command
{
    protected $signature = 'mail:uji {email : Alamat email tujuan uji coba}';

    protected $description = 'Uji kirim email dari server ini dan tampilkan error SMTP yang sebenarnya';

    public function handle(): int
    {
        $tujuan = $this->argument('email');

        if (! filter_var($tujuan, FILTER_VALIDATE_EMAIL)) {
            $this->error("Alamat email tidak valid: {$tujuan}");

            return self::FAILURE;
        }

        $mailer = config('mail.default');
        $smtp = config('mail.mailers.smtp', []);
        $sandi = (string) ($smtp['password'] ?? '');

        $baris = [
            ['MAIL_MAILER', $mailer],
            ['MAIL_FROM_ADDRESS', config('mail.from.address') ?: '(kosong)'],
        ];

        // Baris SMTP hanya relevan bila mailer aktifnya memang smtp.
        if ($mailer === 'smtp') {
            $baris = array_merge($baris, [
                ['MAIL_HOST', $smtp['host'] ?? '-'],
                ['MAIL_PORT', $smtp['port'] ?? '-'],
                ['MAIL_SCHEME', $smtp['scheme'] ?: '(kosong, STARTTLS otomatis)'],
                ['MAIL_USERNAME', $smtp['username'] ?: '(kosong)'],
                ['MAIL_PASSWORD', $sandi === '' ? 'KOSONG — ini penyebab tersering' : strlen($sandi).' karakter'],
                ['MAIL_TIMEOUT', $smtp['timeout'] ?? '-'],
            ]);
        }

        $this->line('Konfigurasi yang terbaca aplikasi:');
        $this->table(['Pengaturan', 'Nilai'], $baris);

        if ($mailer === 'log') {
            $this->warn('MAIL_MAILER=log — email hanya ditulis ke storage/logs, tidak benar-benar dikirim.');
            $this->warn('Untuk benar-benar mengirim, set MAIL_MAILER=smtp di .env.');
        }

        if ($mailer === 'smtp') {
            if ($sandi === '') {
                $this->warn('MAIL_PASSWORD kosong — pengiriman hampir pasti ditolak Gmail.');
            } elseif (strlen(str_replace(' ', '', $sandi)) === 16) {
                $this->info('Panjang sandi 16 karakter — cocok dengan bentuk App Password Gmail.');
            } else {
                $this->warn('MAIL_PASSWORD bukan 16 karakter — Gmail mewajibkan App Password, bukan password akun.');
            }
        }

        $this->newLine();
        $this->line("Mengirim email uji ke {$tujuan} ...");

        try {
            Mail::raw(
                "Ini email uji dari Si Bakat Hebat.\n\n"
                ."Kalau Anda menerima pesan ini, pengiriman email dari server sudah berfungsi.\n"
                .'Dikirim pada '.now()->format('d-m-Y H:i:s').'.',
                fn ($pesan) => $pesan->to($tujuan)->subject('Uji Kirim Email — Si Bakat Hebat')
            );
        } catch (\Throwable $e) {
            $this->newLine();
            $this->error('GAGAL: '.$e->getMessage());
            $this->newLine();
            $this->line('Petunjuk membaca error di atas:');
            $this->line('- "Username and Password not accepted" → MAIL_PASSWORD bukan App Password Gmail.');
            $this->line('- "Connection could not be established" / "timed out" → hosting memblokir port SMTP.');
            $this->line('  Coba MAIL_PORT=465 dengan MAIL_SCHEME=smtps.');
            $this->line('- "Connection refused" → MAIL_HOST salah.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info("BERHASIL. Email uji terkirim ke {$tujuan} — silakan cek inbox dan folder spam.");

        return self::SUCCESS;
    }
}
