<?php

namespace App\Jobs;

use App\Mail\BebasPustakaMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendBebasPustakaEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mahasiswaData;
    public $pdfPath;

    /**
     * Create a new job instance.
     */
    public function __construct($mahasiswaData, $pdfPath)
    {
        $this->mahasiswaData = $mahasiswaData;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->mahasiswaData['email'])
                ->send(new BebasPustakaMail($this->mahasiswaData, $this->pdfPath));
            
            Log::info('Email bebas pustaka berhasil dikirim ke: ' . $this->mahasiswaData['email']);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email bebas pustaka: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job SendBebasPustakaEmail gagal: ' . $exception->getMessage());
    }
}
