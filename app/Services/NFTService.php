<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NFTService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.dracoscopia.api_url');
        $this->apiKey = config('services.dracoscopia.api_key');
    }

    public function createNFT(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/nft/create', $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('NFT creation failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('NFT creation error', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function getNFT($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/nft/' . $id);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('NFT retrieval error', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function updateNFT($id, array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->put($this->apiUrl . '/nft/' . $id, $data);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('NFT update error', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function deleteNFT($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->delete($this->apiUrl . '/nft/' . $id);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('NFT deletion error', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
} 