<?php

namespace App\Jobs;

use App\Mail\TurnitinMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTurnitinEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dosenData;
    public $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($dosenData, $filePath)
    {
        $this->dosenData = $dosenData;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->dosenData['email'])
                ->send(new TurnitinMail($this->dosenData, $this->filePath));
            
            Log::info('Email turnitin berhasil dikirim ke: ' . $this->dosenData['email']);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email turnitin: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Job SendTurnitinEmail gagal: ' . $exception->getMessage());
    }
}
