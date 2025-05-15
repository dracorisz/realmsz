<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Response;

class ImageController extends Controller
{
    private const MAX_FILE_SIZE = 10240; // 10MB
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    private const MIN_DIMENSION = 256;
    private const MAX_DIMENSION = 2048;

    /**
     * Generate an image using AI
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generate(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'prompt' => 'required|string|max:1000',
                'width' => 'required|integer|min:256|max:2048',
                'height' => 'required|integer|min:256|max:2048',
                'style' => 'required|string|in:realistic,anime,fantasy,cyberpunk,landscape',
                'negative_prompt' => 'nullable|string|max:1000',
            ]);

            $apiKey = config('services.dezgo.api_key');
            if (!$apiKey) {
                Log::error('Dezgo API key not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'Image generation service is not properly configured'
                ], 500);
            }

            $result = $this->generateImageWithAI(
                $validated['prompt'],
                $validated['width'],
                $validated['height'],
                $validated['style'],
                $validated['negative_prompt'] ?? ''
            );

            if (!$result['success']) {
                return response()->json($result, 500);
            }

            // Create image record in database
            $image = Image::create([
                'title' => $validated['prompt'],
                'image_url' => $result['image_url'],
                'organization_id' => Auth::user()->organization_id
            ]);

            return response()->json([
                'success' => true,
                'image_url' => $result['image_url'],
                'image' => $image
            ]);
        } catch (\Exception $e) {
            Log::error('Image generation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request)
    {
        try {
            Log::info('Starting image upload', ['request' => $request->all()]);

            $request->validate([
                'image' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,gif,webp',
                    'max:' . self::MAX_FILE_SIZE
                ],
                'title' => 'required|string|max:255',
                'item_id' => 'nullable|exists:items,id'
            ]);

            $file = $request->file('image');
            Log::info('File received', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);

            $filename = 'uploads/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store in S3
            try {
                $path = Storage::disk('s3')->put($filename, file_get_contents($file));
                Log::info('File uploaded to S3', ['path' => $filename, 'success' => $path]);
                
                if (!$path) {
                    throw new \Exception('Failed to upload file to S3');
                }
            } catch (\Exception $e) {
                Log::error('S3 upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Failed to upload file to storage: ' . $e->getMessage());
            }

            // Get the S3 URL
            $s3Url = config('filesystems.disks.s3.url') . '/' . $filename;
            Log::info('Generated S3 URL', ['url' => $s3Url]);

            // Create image record in database
            $image = Image::create([
                'title' => $request->title,
                'image_url' => $s3Url,
                'organization_id' => Auth::user()->organization_id
            ]);

            Log::info('Image record created', ['image' => $image]);

            return response()->json([
                'success' => true,
                'image_url' => $s3Url ? $s3Url : 'https://realmsz-images-bucket.s3.us-east-1.amazonaws.com/uploads/02ef2963-fadd-492c-929f-0b8e69441caf.png',
                'image' => $image
            ]);
        } catch (\Exception $e) {
            Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOrganizationImages(Request $request)
    {
        try {
            $images = Image::where('organization_id', Auth::user()->organization_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'images' => $images
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch images: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch images'
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $image = Image::where('organization_id', Auth::user()->organization_id)
                ->findOrFail($id);
            
            // Extract the path from the S3 URL
            $path = parse_url($image->image_url, PHP_URL_PATH);
            if ($path) {
                $path = ltrim($path, '/');
                
                // Delete from S3
                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
            }

            // Delete from database
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Image deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'required|string', // S3 URL
                // 'type' => 'required|string|in:generated,uploaded'
            ]);

            $image = Image::create([
                'title' => $validated['title'],
                'image' => $validated['image'],
                // 'type' => $validated['type'],
                'organization_id' => Auth::user()->organization_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image saved successfully',
                'image' => $image
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save image record: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save image record'
            ], 500);
        }
    }

    private function generateImageWithAI($prompt, $width, $height, $style, $negativePrompt)
    {
        try {
            $apiKey = config('services.dezgo.api_key');
            $apiUrl = config('services.dezgo.api_url', 'https://api.dezgo.com');

            $style = strtolower($style);
            
            $response = Http::withHeaders([
                'X-Dezgo-Key' => $apiKey,
                'Content-Type' => 'application/json'
            ])->post("$apiUrl/text2image", [
                'prompt' => $prompt,
                'width' => $width,
                'height' => $height,
                'style' => $style,
                'negative_prompt' => $negativePrompt,
            ]);

            if (!$response->successful()) {
                Log::error('Dezgo API error: ' . $response->body(), [
                    'status' => $response->status(),
                    'headers' => $response->headers()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to generate image: ' . $response->body()
                ];
            }

            $imageData = $response->body();
            if (empty($imageData)) {
                Log::error('Empty response from Dezgo API');
                return [
                    'success' => false,
                    'message' => 'No image data received from the API'
                ];
            }

            // Generate a unique filename
            $filename = 'generated/' . Str::uuid() . '.png';
            
            // Store in S3
            try {
                $result = Storage::disk('s3')->put($filename, $imageData);
                if (!$result) {
                    throw new \Exception('Failed to upload file to S3');
                }

                // Get the full S3 URL
                $imageUrl = config('filesystems.disks.s3.url') . '/' . $filename;
                
                Log::info('Image generated and uploaded to S3', [
                    'filename' => $filename,
                    'url' => $imageUrl
                ]);

                return [
                    'success' => true,
                    'image_url' => $imageUrl
                ];
            } catch (\Exception $e) {
                Log::error('S3 upload failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw new \Exception('Failed to upload generated image to storage: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error in generateImageWithAI: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error generating image: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update an item's image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateItemImage(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'item_id' => 'required|exists:items,id',
                'image_url' => 'required|string'
            ]);

            $item = Item::findOrFail($validated['item_id']);
            
            // Update the item's image
            $item->image = $validated['image_url'];
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Item image updated successfully',
                'data' => [
                    'item' => $item
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update item image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item image: ' . $e->getMessage()
            ], 500);
        }
    }
} 