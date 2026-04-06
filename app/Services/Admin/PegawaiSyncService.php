<?php

namespace App\Services\Admin;

use App\Models\Dimension\DmPegawai;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PegawaiSyncService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected int $timeout;

    public function __construct()
    {
        $this->apiUrl = config('services.pegawai_api.url');
        $this->apiKey = config('services.pegawai_api.key');
        $this->timeout = config('services.pegawai_api.timeout');
    }

    public function syncPegawai(): array
    {
        try {
            Log::info('Starting pegawai sync from API');

            // Call external API
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'apikey' => $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->apiUrl);

            // Check if request successful
            if (!$response->successful()) {
                Log::error('Pegawai API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => 'API request failed with status: ' . $response->status(),
                    'synced' => 0,
                    'failed' => 0,
                ];
            }

            // Parse JSON response
            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== 200) {
                Log::error('Pegawai API returned error', ['response' => $data]);
                
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Unknown API error',
                    'synced' => 0,
                    'failed' => 0,
                ];
            }

            $items = $data['items'] ?? [];

            if (empty($items)) {
                Log::warning('Pegawai API returned empty items');
                
                return [
                    'success' => true,
                    'message' => 'No data to sync',
                    'synced' => 0,
                    'failed' => 0,
                ];
            }

            // Sync data
            $synced = 0;
            $failed = 0;

            foreach ($items as $item) {
                try {
                    // Validate required fields
                    if (empty($item['nip'])) {
                        Log::warning('Skipping item with empty NIP', ['item' => $item]);
                        $failed++;
                        continue;
                    }

                    // Prepare data for upsert
                    $pegawaiData = [
                        'nip' => $item['nip'],
                        'nama' => $item['nama'] ?? '',
                        'inisial' => $item['inisial'] ?? '',
                        'homebase' => $item['homebase'] ?? null,
                        'email' => $item['email'] ?? null,
                    ];

                    // Upsert pegawai
                    DmPegawai::upsertByNip($pegawaiData);
                    
                    $synced++;
                } catch (\Exception $e) {
                    Log::error('Failed to sync pegawai item', [
                        'nip' => $item['nip'] ?? 'unknown',
                        'error' => $e->getMessage()
                    ]);
                    $failed++;
                }
            }

            Log::info('Pegawai sync completed', [
                'total_items' => count($items),
                'synced' => $synced,
                'failed' => $failed
            ]);

            return [
                'success' => true,
                'message' => "Berhasil sinkronisasi {$synced} data pegawai" . ($failed > 0 ? ", {$failed} gagal" : ""),
                'synced' => $synced,
                'failed' => $failed,
                'total' => count($items),
            ];

        } catch (\Exception $e) {
            Log::error('Pegawai sync exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'synced' => 0,
                'failed' => 0,
            ];
        }
    }
}
 