<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function generate(Request $request)
    {
        try {
            $request->validate([
                'prompt' => 'required|string',
                'item_id' => 'required|exists:plans,id',
            ]);

            if (!config('services.dezgo.api_key')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image generation service is not configured'
                ], 500);
            }

            $imageUrl = $this->generateImageWithAI($request->prompt);
            
            if (!$imageUrl) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate image'
                ], 500);
            }

            // Save to S3
            $imageContent = file_get_contents($imageUrl);
            if (!$imageContent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to download generated image'
                ], 500);
            }

            $filename = 'generated/' . Str::uuid() . '.png';
            if (!Storage::disk('s3')->put($filename, $imageContent)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save image to storage'
                ], 500);
            }
            
            $s3Url = config('filesystems.disks.s3.url') . '/' . $filename;

            // Save the image URL to the plan
            // $plan = Plan::find($request->item_id);
            // $plan->image = $s3Url;
            // $plan->save();

            return response()->json([
                'success' => true,
                'image_url' => $s3Url
            ]);
        } catch (\Exception $e) {
            // Log::error('Image generation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while generating the image'
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:10240', // 10MB max
                'item_id' => 'required|exists:plans,id',
            ]);

            if (!config('filesystems.disks.s3.key')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Storage service is not configured'
                ], 500);
            }

            $image = $request->file('image');
            Log::info($image);
            $filename = 'uploads/' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            try {
                $path = Storage::disk('s3')->put($filename, file_get_contents($image));
                if (!$path) {
                    throw new \Exception('Failed to save image to storage');
                }
            } catch (\Exception $e) {
                // \Illuminate\Support\Facades\Log::error('S3 Upload Error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image to storage: ' . $e->getMessage()
                ], 500);
            }
            // $s3Url = config('filesystems.disks.s3.url') . '/' . $filename;

            // Save the image URL to the plan
            // $plan = Plan::find($request->item_id);
            // if (!$plan) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Plan not found'
            //     ], 404);
            // }

            // $plan->image = $s3Url;
            // if (!$plan->save()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Failed to save image URL to plan'
            //     ], 500);
            // }

            return response()->json([
                'success' => true,
                // 'image_url' => $s3Url
            ]);
        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Image upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOrganizationImages(Request $request)
    {
        $organization = Auth::user()->organization;
        
        $images = Plan::where('organization_id', $organization->id)
            ->whereNotNull('image')
            ->select('id', 'title', 'image', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'images' => $images
        ]);
    }

    private function generateImageWithAI($prompt)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://api.dezgo.com/text2image_sdxl', [
                'headers' => [
                    'X-Dezgo-Key' => config('services.dezgo.api_key'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'prompt' => $prompt,
                    'width' => 1024,
                    'height' => 1024,
                    'style' => 'realistic',
                    'negative_prompt' => 'blurry, low quality, distorted, ugly, deformed',
                    'num_images' => 1,
                    'guidance_scale' => 7.5,
                    'steps' => 30
                ],
                'timeout' => 60 // Increase timeout for image generation
            ]);

            if ($response->getStatusCode() !== 200) {
                // \Illuminate\Support\Facades\Log::error('Dezgo API error: ' . $response->getBody());
                return null;
            }

            $data = json_decode($response->getBody(), true);
            
            if (!isset($data['image_url'])) {
                // \Illuminate\Support\Facades\Log::error('Invalid response from Dezgo API: ' . json_encode($data));
                return null;
            }

            return $data['image_url'];
        } catch (\Exception $e) {
            // \Illuminate\Support\Facades\Log::error('Dezgo API call failed: ' . $e->getMessage());
            return null;
        }
    }
} 