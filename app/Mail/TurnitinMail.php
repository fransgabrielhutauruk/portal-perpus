<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class TurnitinMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dosenData;
    public $filePath;

    /**
     * Create a new message instance.
     */
    public function __construct($dosenData, $filePath)
    {
        $this->dosenData = $dosenData;
        $this->filePath = $filePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hasil Cek Turnitin - ' . $this->dosenData['nama'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.turnitin',
            with: [
                'nama' => $this->dosenData['nama'],
                'judul_dokumen' => $this->dosenData['judul_dokumen'],
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
            Attachment::fromPath($this->filePath)
                ->as('Hasil_Turnitin_' . $this->dosenData['nip'] . '.' . pathinfo($this->filePath, PATHINFO_EXTENSION))
                ->withMime(mime_content_type($this->filePath)),
        ];
    }
}
