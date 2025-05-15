<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\GeminiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\FocusController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
  Route::prefix('items')->name('items.')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
    Route::post('/store', [ItemController::class, 'store'])->name('store');
    Route::post('/destroy', [ItemController::class, 'destroy'])->name('destroy');
    Route::post('/duplicate', [ItemController::class, 'duplicate'])->name('duplicate');
    Route::post('/update', [ItemController::class, 'update'])->name('update');
    Route::post('/export', [ItemController::class, 'export'])->name('export');  
    Route::post('/status', [ItemController::class, 'status'])->name('status');
    Route::post('/priority', [ItemController::class, 'priority'])->name('priority');
    Route::post('/date', [ItemController::class, 'date'])->name('date');
    Route::post('/archive/{item}', [ItemController::class, 'archive'])->name('archive');
    
    // User assignments
    Route::post('/assign', [ItemController::class, 'assignUsers'])->name('assign');
    
    // Attachments
    Route::get('/{item}/attachments', [ItemController::class, 'attachments'])->name('attachments');
    Route::post('/attachments/store', [ItemController::class, 'storeAttachment'])->name('attachments.store');
    Route::post('/attachments/presign', [ItemController::class, 'presignAttachmentUrl'])->name('attachments.presign');
    Route::delete('/attachments/{attachment}', [ItemController::class, 'destroyAttachment'])->name('attachments.destroy');
    Route::get('/attachments/{attachment}/download', [ItemController::class, 'downloadAttachment'])->name('attachments.download');
  });

  // Priority routes
  Route::prefix('priority')->name('priority.')->group(function () {
    Route::post('/store', [PriorityController::class, 'store'])->name('store');
    Route::get('/index', [PriorityController::class, 'index'])->name('index');
    Route::post('/destroy', [PriorityController::class, 'destroy'])->name('destroy');
    Route::post('/update', [PriorityController::class, 'update'])->name('update');
  });

  // Category routes
  Route::prefix('category')->name('category.')->group(function () {
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::get('/index', [CategoryController::class, 'index'])->name('index');
    Route::post('/destroy', [CategoryController::class, 'destroy'])->name('destroy');
    Route::post('/update', [CategoryController::class, 'update'])->name('update');
  });

  // Status routes
  Route::prefix('status')->name('status.')->group(function () {
    Route::post('/store', [StatusController::class, 'store'])->name('store');
    Route::get('/index', [StatusController::class, 'index'])->name('index');
    Route::post('/destroy', [StatusController::class, 'destroy'])->name('destroy');
    Route::post('/update', [StatusController::class, 'update'])->name('update');
  });

  // Checklist routes
  Route::prefix('checklists')->name('checklists.')->group(function () {
    Route::get('/{item}', [ChecklistController::class, 'index'])->name('index');
    Route::post('/', [ChecklistController::class, 'store'])->name('store');
    Route::put('/{checklist}', [ChecklistController::class, 'update'])->name('update');
    Route::delete('/{checklist}', [ChecklistController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [ChecklistController::class, 'reorder'])->name('reorder');
  });

  // Attachment routes
  Route::prefix('attachment')->name('attachment.')->group(function () {
    Route::post('/store', [AttachmentController::class, 'store'])->name('store');
    Route::delete('/{attachment}', [AttachmentController::class, 'destroy'])->name('destroy');
    Route::get('/{attachment}/download', [AttachmentController::class, 'download'])->name('download');
  });
  // Image routes
  Route::prefix('images')->name('images.')->group(function () {
    Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
    Route::delete('/{image}', [ImageController::class, 'destroy'])->name('destroy');
  });

  Route::post('/generate', [ImageController::class, 'generate'])->name('generate');
  Route::post('/upload', [ImageController::class, 'upload'])->name('upload');
  Route::get('/', [ImageController::class, 'getOrganizationImages'])->name('getOrganizationImages');
  Route::delete('/{id}', [ImageController::class, 'delete'])->name('delete');
  Route::post('/update-item', [ImageController::class, 'updateItemImage'])->name('updateItemImage');

  Route::prefix('chats')->name('chats.')->group(function () {
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('show');
    Route::post('/chats/name', [GeminiController::class, 'chatName'])->name('name');
    Route::post('/chats', [ChatController::class, 'store'])->name('store');
    Route::post('/chats/delete', [ChatController::class, 'delete'])->name('delete');
  });

  Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');

  // Organization routes
  Route::prefix('organization')->name('organization.')->group(function () {
    Route::get('/', [OrganizationController::class, 'index'])->name('index');
    Route::post('/', [OrganizationController::class, 'store'])->name('store');
    Route::put('/{organization}', [OrganizationController::class, 'update'])->name('update');
    Route::delete('/{organization}', [OrganizationController::class, 'destroy'])->name('destroy');
    Route::get('/members', [OrganizationController::class, 'getMembers'])->name('members');
    Route::put('/members/{user}', [OrganizationController::class, 'updateMember'])->name('members.update');
    Route::delete('/members/{user}', [OrganizationController::class, 'removeMember'])->name('members.remove');
  });

  // Focus routes
  Route::prefix('focus')->group(function () {
    Route::get('/', [FocusController::class, 'index']);
    Route::post('/start', [FocusController::class, 'startSession']);
    Route::post('/end', [FocusController::class, 'endSession']);
    Route::get('/stats', [FocusController::class, 'getStats']);
  });


  // Subscription routes
  Route::prefix('subscriptions')->prefix('subscriptions')->name('subscriptions.')->group(function () {
    Route::post('/', [SubscriptionController::class, 'create'])->name('create');
    Route::get('/success', [SubscriptionController::class, 'success'])->name('success');
    Route::get('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
  });

  // Network Routes
  Route::get('/network', [NetworkController::class, 'index'])->name('network');
  Route::get('/network/members', [NetworkController::class, 'members'])->name('network.members');
  Route::get('/network/users', [NetworkController::class, 'users'])->name('network.users');
  Route::post('/network/visibility', [NetworkController::class, 'updateVisibility'])->name('network.visibility');
  Route::post('/network/add-to-task', [NetworkController::class, 'addToTask'])->name('network.add-to-task');
  Route::post('/network/remove-from-task', [NetworkController::class, 'removeFromTask'])->name('network.remove-from-task');
  // User assignment routes
  Route::get('/network/users', [NetworkController::class, 'users'])->name('network.users');
  Route::post('/items/assign', [ItemController::class, 'assign'])->name('items.assign');
  Route::delete('/images/{id}', [ImageController::class, 'delete'])->name('images.delete');

  // Image Routes
  Route::post('/images/generate', [ImageController::class, 'generate'])->name('images.generate');
  Route::post('/images/upload', [ImageController::class, 'upload'])->name('images.upload');
  Route::get('/images/organization', [ImageController::class, 'getOrganizationImages'])->name('images.organization');

  Route::get('/items/{item}/attachments', [AttachmentController::class, 'index'])->name('items.attachments');
  Route::post('/items/attachments/store', [AttachmentController::class, 'store'])->name('items.attachments.store');
  Route::delete('/items/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('items.attachments.destroy');
  Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');

  

  // Checklist routes
  Route::prefix('items')->name('items.')->group(function () {
    Route::get('/{item}/checklists', [ChecklistController::class, 'index'])->name('checklists.index');
    Route::post('/checklists', [ChecklistController::class, 'store'])->name('checklists.store');
    Route::put('/checklists/{checklist}', [ChecklistController::class, 'update'])->name('checklists.update');
    Route::delete('/checklists/{checklist}', [ChecklistController::class, 'destroy'])->name('checklists.destroy');
    Route::post('/checklists/reorder', [ChecklistController::class, 'reorder'])->name('checklists.reorder');
  });
});
