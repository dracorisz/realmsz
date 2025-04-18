<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ContentSyncService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.dracoscopia.api_url');
        $this->apiKey = config('services.dracoscopia.api_key');
    }

    public function syncContent()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/content');

            if ($response->successful()) {
                $content = $response->json();
                $this->processContent($content);
                return true;
            }

            Log::error('Content sync failed', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Content sync error', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function processContent($content)
    {
        foreach ($content as $item) {
            // Process images
            if (isset($item['images'])) {
                $this->processImages($item['images']);
            }

            // Process text content
            if (isset($item['text'])) {
                $this->processText($item['text']);
            }

            // Process metadata
            if (isset($item['metadata'])) {
                $this->processMetadata($item['metadata']);
            }
        }
    }

    protected function processImages($images)
    {
        foreach ($images as $image) {
            try {
                $imageContent = Http::get($image['url'])->body();
                $filename = basename($image['url']);
                Storage::put('public/images/' . $filename, $imageContent);
            } catch (\Exception $e) {
                Log::error('Image processing error', [
                    'error' => $e->getMessage(),
                    'image' => $image,
                ]);
            }
        }
    }

    protected function processText($text)
    {
        // Process and store text content
        // This could involve creating/updating database records
        // or generating static files
    }

    protected function processMetadata($metadata)
    {
        // Process and store metadata
        // This could involve updating SEO information,
        // content relationships, or other metadata
    }

    public function getContent($type, $limit = 10)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->apiUrl . '/content/' . $type, [
                'limit' => $limit,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Content retrieval error', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }
} 