<?php

namespace App\Mail;

use App\Models\Observasi;
use App\Services\KalkulasiTB40Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HasilTesMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array<string, mixed>|null */
    private ?array $data = null;

    public function __construct(
        public Observasi $observasi,
    ) {}

    /**
     * Rakit data hasil sekali, dipakai untuk body & lampiran PDF.
     *
     * @return array<string, mixed>
     */
    private function data(): array
    {
        return $this->data ??= app(KalkulasiTB40Service::class)->rakitData($this->observasi);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hasil Tes Si Bakat Hebat — '.$this->observasi->peserta->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.hasil',
            with: array_merge($this->data(), [
                'urlHasil' => route('hasil.show', $this->observasi),
            ]),
        );
    }

    /**
     * Lampirkan PDF laporan lengkap.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('hasil.pdf', $this->data());

        return [
            Attachment::fromData(fn () => $pdf->output(), "hasil-sibakathebat-{$this->observasi->id}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
