<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class BebasPustakaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mahasiswaData;
    public $docxPath;

    /**
     * Create a new message instance.
     */
    public function __construct($mahasiswaData, $docxPath)
    {
        $this->mahasiswaData = $mahasiswaData;
        $this->docxPath = $docxPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Surat Bebas Pustaka - ' . $this->mahasiswaData['nama'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bebas-pustaka',
            with: [
                'nama' => $this->mahasiswaData['nama'],
                'nim' => $this->mahasiswaData['nim'],
                'prodi' => $this->mahasiswaData['prodi'],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->docxPath)
                ->as('Surat_Bebas_Pustaka_' . $this->mahasiswaData['nim'] . '.docx')
                ->withMime('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ];
    }
}
