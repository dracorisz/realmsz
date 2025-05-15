<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttachmentController extends Controller
{
    private const MAX_FILE_SIZE = 10240; // 10MB
    private const ALLOWED_MIME_TYPES = [
        // '*/*',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];

    public function index(Item $item)
    {
        if (!$item->canView(Auth::user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attachments = $item->attachments()->get();
        
        return response()->json([
            'success' => true,
            'attachments' => $attachments
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Starting attachment upload', ['request' => $request->all()]);

            if (!$request->hasFile('file')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No file was uploaded',
                    'errors' => ['Please select a file to upload']
                ], 422);
            }

            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload',
                    'errors' => ['The uploaded file is invalid']
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'file' => [
                    'required',
                    'file',
                    'max:' . self::MAX_FILE_SIZE,
                ],
                'title' => 'required|string|max:255',
                'item_id' => 'required|exists:items,id'
            ], [
                'file.required' => 'Please select a file to upload',
                'file.file' => 'The uploaded file is invalid',
                'file.max' => 'The file size must not exceed ' . (self::MAX_FILE_SIZE / 1024) . 'MB',
                'title.required' => 'Please provide a title for the file',
                'item_id.required' => 'Please select an item to attach the file to',
                'item_id.exists' => 'The selected item does not exist'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $mimeType = $file->getMimeType();
            if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type',
                    'errors' => ["The file type '$mimeType' is not allowed. Allowed types: " . implode(', ', array_map(function($mime) {
                        return explode('/', $mime)[1];
                    }, self::ALLOWED_MIME_TYPES))]
                ], 422);
            }

            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::random(40) . '.' . $extension;
            
            Log::info('File upload attempt', [
                'original_name' => $originalName,
                'extension' => $extension,
                'mime_type' => $mimeType,
                'allowed_types' => self::ALLOWED_MIME_TYPES
            ]);

            // Store in S3
            $path = $file->storeAs(
                'attachments/' . $request->item_id,
                $fileName,
                's3'
            );

            if (!$path) {
                throw new \Exception('Failed to store file in S3');
            }

            $attachment = Attachment::create([
                'item_id' => $request->item_id,
                'title' => $request->title,
                'file_name' => $originalName,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'file_type' => $file->getMimeType(),
                'url' => config('filesystems.disks.s3.url') . '/' . $path
            ]);

            Log::info('Attachment created successfully', ['attachment' => $attachment]);

            return response()->json([
                'success' => true,
                'message' => 'Attachment uploaded successfully',
                'attachment' => $attachment
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading attachment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Attachment $attachment)
    {
        try {
            if (!$attachment->item->canEdit(Auth::user())) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Delete from S3
            Storage::disk('s3')->delete($attachment->file_path);
            
            // Delete from database
            $attachment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attachment deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Attachment deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download(Attachment $attachment)
    {
        if (!$attachment->item->canView(Auth::user())) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $headers = [
                'Content-Type' => $attachment->file_type,
                'Content-Disposition' => 'attachment; filename="' . $attachment->file_name . '"'
            ];

            $file = Storage::disk('s3')->get($attachment->file_path);
            return response($file, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Attachment download failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to download attachment: ' . $e->getMessage()
            ], 500);
        }
    }
} 