<?php

namespace App\Services\Frontend;

use Illuminate\Support\Facades\Http;

class MahasiswaService
{
    public function findByEmail(string $email): ?array
    {
        $baseUrl = config('services.mahasiswa_api.url');
        $collection = config('services.mahasiswa_api.collection');
        $timeout = (int) config('services.mahasiswa_api.timeout', 30);
        $apiKey = config('services.mahasiswa_api.key');

        $request = Http::timeout($timeout);
        if (!empty($apiKey)) {
            $request = $request->withHeaders([
                'apikey' => $apiKey,
            ]);
        }

        $response = $request->get($baseUrl, [
            'collection' => $collection,
            'email' => $email,
        ]);

        if (!$response->successful()) {
            return null;
        }

        $payload = $response->json();
        if (empty($payload)) {
            return null;
        }

        if (isset($payload['data'])) {
            $payload = $payload['data'];
        }

        if (isset($payload['items'])) {
            $payload = $payload['items'];
        }

        if (is_array($payload) && array_is_list($payload)) {
            $payload = $payload[0] ?? null;
        }

        if (!is_array($payload)) {
            return null;
        }
        if (empty($payload['nim']) || empty($payload['nama'])) {
            return null;
        }

        return [
            'nim' => $payload['nim'],
            'nama' => $payload['nama'],
            'email' => $payload['email'] ?? $email,
            'prodi' => $payload['prodi'] ?? null,
        ];
    }
}
