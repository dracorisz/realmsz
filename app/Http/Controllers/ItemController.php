<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IconController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Attachment;
use App\Models\User;
use App\Models\Item;
use Exception;

class ItemController extends Controller
{
    private $slackWebhookUrl;

    public function __construct()
    {
        $this->slackWebhookUrl = env('SLACK_WEBHOOK_URL');
    }

    public function logToSlack($message, $context = [])
    {
        if ($this->slackWebhookUrl) {
            Http::post($this->slackWebhookUrl, [
                'text' => $message . ' ' . json_encode($context),
            ]);
        }
    }

    public function index()
    {
        try {
            $statuses = (new StatusController)->index();
            $priorities = (new PriorityController)->index();
            $categories = (new CategoryController)->index();
            $icons = (new IconController)->index();

            return Inertia::render('App/Plans/Main', [
                'items' => Item::with(['category', 'priority', 'status'])
                    ->where('organization_id', Auth::user()->organization_id)
                    ->orWhere('organization_id', 0)
                    ->orderBy('status_id', 'asc')
                    ->orderBy('date', 'asc')
                    ->get(),
                'statuses' => $statuses,
                'priorities' => $priorities,
                'categories' => $categories,
                'icons' => $icons,
                'users' => User::get(),
            ]);
        } catch (Exception $e) {
            $this->logToSlack('Exception during plans index:', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load plans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(ItemRequest $request)
    {
        try {
            $item = Item::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Item added successfully',
                'item' => $item
            ]);
        } catch (Exception $e) {
            $this->logToSlack('Exception during item creation:', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function duplicate(ItemRequest $request)
    {
        try {
            $item = Item::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Item duplicated successfully',
                'item' => $item
            ]);
        } catch (Exception $e) {
            $this->logToSlack('Exception during item duplication:', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to duplicate item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            Item::destroy($request->id);
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function archive(Item $item)
    {
        try {
            $item = Item::find($item->id);
            $item->archived = $item->archived ? false : true;
            $item->save();

            return response()->json([
                'success' => true,
                'message' => $item->archived ? 'Item archived successfully' : 'Item unarchived successfully',
                'item' => $item
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item archive status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(ItemRequest $request)
    {
        try {
            $item = Item::find($request->id);
            $item->update($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully'
            ]);
        } catch (Exception $e) {
            $this->logToSlack('Exception during item change:', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of an item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:items,id',
            'status_id' => 'nullable|exists:statuses,id',
        ]);

        try {
            $item = Item::findOrFail($validated['id']);           
            $item->status_id = $validated['status_id'];
            $item->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the priority of an item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function priority(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:items,id',
            'priority_id' => 'nullable|exists:priorities,id',
        ]);

        try {
            $item = Item::findOrFail($validated['id']);
           
            $item->priority_id = $validated['priority_id'];
            $item->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Priority updated successfully',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating priority: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the due date of an item
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function date(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:items,id',
            'date' => 'nullable',
            'recurring' => 'boolean',
            'recurring_interval' => 'nullable|string',
        ]);

        try {
            $item = Item::findOrFail($validated['id']);           
            $item->date = $validated['date'];
            
            // Handle recurring settings if passed
            if (isset($validated['recurring'])) {
                $item->recurring = $validated['recurring'];
                $item->recurring_interval = $validated['recurring_interval'];
            }
            
            $item->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Due date updated successfully',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating due date: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function export()
    {
        $fileName = 'items.csv';
        $items = Item::where('archived', 0)->with(['category', 'priority', 'status'])->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('Title', 'Description', 'Date', 'Status', 'Priority');

        $callback = function () use ($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                $row['Title'] = $item->title;
                $row['Description'] = $item->description;
                $row['Date'] = $item->date;
                $row['Status'] = $item->status['name'];
                $row['Priority'] = $item->priority['name'];

                fputcsv($file, array($row['Title'], $row['Description'], $row['Date'], $row['Status'], $row['Priority']));
            }

            fclose($file);
        };

        return response()->streamDownload($callback, 200, $headers);
    }

    public function attachments(Request $request, $item)
    {
        $attachments = \App\Models\Attachment::where('item_id', $item)->get();
        return response()->json($attachments);
    }

    /**
     * Generate a presigned URL for S3 upload
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function presignAttachmentUrl(Request $request)
    {
        try {
            $request->validate([
                'filename' => 'required|string',
                'contentType' => 'required|string',
            ]);

            // If using S3, generate a presigned URL
            if (config('filesystems.default') === 's3') {
                // Create S3 client manually instead of using Storage::disk('s3')->getClient()
                $s3Client = new \Aws\S3\S3Client([
                    'version' => 'latest',
                    'region' => config('filesystems.disks.s3.region'),
                    'credentials' => [
                        'key' => config('filesystems.disks.s3.key'),
                        'secret' => config('filesystems.disks.s3.secret'),
                    ],
                ]);
                
                $bucket = config('filesystems.disks.s3.bucket');
                
                // Generate a unique key for the file
                $key = 'attachments/' . time() . '_' . $request->filename;
                
                // Get presigned URL
                $cmd = $s3Client->getCommand('PutObject', [
                    'Bucket' => $bucket,
                    'Key' => $key,
                    'ContentType' => $request->contentType,
                    'ACL' => 'public-read',
                ]);
                
                $presignedRequest = $s3Client->createPresignedRequest($cmd, '+15 minutes');
                $presignedUrl = (string) $presignedRequest->getUri();
                
                return response()->json([
                    'success' => true,
                    'url' => $presignedUrl,
                    'key' => $key,
                ]);
            }
            
            // If not using S3, just return a success response to continue with regular upload
            return response()->json([
                'success' => true,
                'url' => null,
                'key' => null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating presigned URL: ' . $e->getMessage(), [
                'user' => Auth::user() ? Auth::user()->name : 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate presigned URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created attachment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAttachment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|exists:items,id',
                'file' => 'required_without:key|file|max:10240', // 10MB max
                'key' => 'required_without:file|string',
                'filename' => 'sometimes|string',
                'size' => 'sometimes|integer',
                'type' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $item = Item::findOrFail($request->item_id);
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('attachments', 'public');
                
                $attachment = $item->attachments()->create([
                    'title' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Attachment uploaded successfully',
                    'attachment' => $attachment
                ]);
            } elseif ($request->has('key')) {
                // S3 direct upload - construct URL manually instead of using Storage::disk('s3')->url()
                $bucket = config('filesystems.disks.s3.bucket');
                $region = config('filesystems.disks.s3.region');
                $url = "https://{$bucket}.s3.{$region}.amazonaws.com/{$request->key}";
                
                $attachment = $item->attachments()->create([
                    'title' => $request->filename ?? basename($request->key),
                    'file_path' => $request->key,
                    'file_type' => $request->type ?? 'application/octet-stream',
                    'file_size' => $request->size ?? 0,
                    'url' => $url,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Attachment saved successfully',
                    'attachment' => $attachment
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file or key provided'
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error storing attachment: ' . $e->getMessage(), [
                'user' => Auth::user() ? Auth::user()->name : 'unknown',
                'item_id' => $request->item_id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to store attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyAttachment(Attachment $attachment)
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['message' => 'Attachment deleted successfully']);
    }

    public function assign(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $item = Item::findOrFail($request->item_id);
        $item->assignedUsers()->sync($request->user_ids);

        return response()->json([
            'message' => 'Users assigned successfully',
            'assigned_users' => $item->assignedUsers
        ]);
    }

    public function checklists(Request $request, $item)
    {
        $checklists = \App\Models\Checklist::where('item_id', $item)->get();
        return response()->json($checklists);
    }

    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'item_id' => 'required|exists:items,id',
                'target_position' => 'required|integer|min:0'
            ]);

            $item = Item::findOrFail($request->item_id);
            $items = Item::where('organization_id', Auth::user()->organization_id)
                ->orderBy('order')
                ->get();

            // Remove item from current position
            $currentIndex = $items->search(function($i) use ($item) {
                return $i->id === $item->id;
            });
            if ($currentIndex !== false) {
                $items->splice($currentIndex, 1);
            }

            $items->splice($request->target_position, 0, [$item]);
            DB::transaction(function() use ($items) {
                foreach($items as $index => $item) {
                    $item->order = $index;
                    $item->save();
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Item reordered successfully'
            ]);
        } catch (Exception $e) {
            $this->logToSlack('Exception during item reordering:', ['exception' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download an attachment file
     *
     * @param  \App\Models\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment(Attachment $attachment)
    {
        try {
            $filePath = Storage::disk('public')->path($attachment->file_path);
            if (!file_exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            // Return the file
            return response()->download(
                $filePath, 
                $attachment->title ?? basename($attachment->file_path),
                [
                    'Content-Type' => $attachment->file_type,
                    'Content-Disposition' => 'attachment; filename="' . ($attachment->title ?? basename($attachment->file_path)) . '"',
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error downloading attachment: ' . $e->getMessage(), [
                'user' => Auth::user() ? Auth::user()->name : 'unknown',
                'attachment_id' => $attachment->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to download attachment: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update the assignUsers method to handle user assignments more properly
    public function assignUsers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required|exists:items,id',
                'user_ids' => 'required|array',
                'user_ids.*' => 'exists:users,id',
                'role' => 'sometimes|string|in:viewer,editor,admin'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $item = Item::findOrFail($request->item_id);

            $syncData = [];
            $role = $request->role ?? 'viewer';
            
            foreach ($request->user_ids as $userId) {
                $syncData[$userId] = [
                    'role' => $role,
                    'shared_at' => now(),
                    'permissions' => json_encode([
                        'view' => true, 
                        'edit' => in_array($role, ['editor', 'admin']), 
                        'delete' => $role === 'admin'
                    ])
                ];
            }

            $item->users()->syncWithoutDetaching($syncData);

            return response()->json([
                'success' => true,
                'message' => 'Users assigned successfully',
                'assigned_users' => $item->users()->withPivot(['role', 'shared_at', 'permissions'])->get()
            ]);
        } catch (\Exception $e) {
            Log::error('Error assigning users: ' . $e->getMessage(), [
                'user' => Auth::user() ? Auth::user()->name : 'unknown',
                'item_id' => $request->item_id ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign users: ' . $e->getMessage()
            ], 500);
        }
    }
}
