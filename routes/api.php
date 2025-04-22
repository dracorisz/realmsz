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
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Models\Priority;

// use App\Http\Controllers\TagController;

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

Route::middleware(['auth:sanctum', 'role:suzy'])->group(function () {
  Route::prefix('item')->name('item.')->group(function () {
    Route::post('/store', [ItemController::class, 'store'])->name('store');
    Route::post('/duplicate', [ItemController::class, 'duplicate'])->name('duplicate');
    Route::post('/date', [ItemController::class, 'date'])->name('date');
    Route::post('/priority', [ItemController::class, 'priority'])->name('priority');
    Route::post('/destroy', [ItemController::class, 'destroy'])->name('destroy');
    Route::post('/archive', [ItemController::class, 'archive'])->name('archive');
    Route::post('/status', [ItemController::class, 'status'])->name('status');
    Route::post('/update', [ItemController::class, 'update'])->name('update');
    Route::post('/export', [ItemController::class, 'export'])->name('export');
  });
  
  Route::prefix('priority')->name('priority.')->group(function () {
    Route::post('/store', [PriorityController::class, 'store'])->name('store');
    Route::post('/destroy', [PriorityController::class, 'destroy'])->name('destroy');
    Route::post('/update', [PriorityController::class, 'update'])->name('update');
  });

  Route::prefix('category')->name('category.')->group(function () {
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::post('/destroy', [CategoryController::class, 'destroy'])->name('destroy');
    Route::post('/update', [CategoryController::class, 'update'])->name('update');
  });

  Route::prefix('status')->name('status.')->group(function () {
    Route::post('/store', [StatusController::class, 'store'])->name('store');
    Route::post('/destroy', [StatusController::class, 'destroy'])->name('destroy');
    Route::post('/update', [StatusController::class, 'update'])->name('update');
  });

  Route::prefix('gemini')->name('gemini.')->group(function () {
    Route::post('/text', [GeminiController::class, 'text'])->name('text');
  });
});

Route::middleware('auth:sanctum')->group(function () {
  Route::prefix('chats')->name('chats.')->group(function () {
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('show');
    Route::post('/chats/name', [GeminiController::class, 'chatName'])->name('name');
    Route::post('/chats', [ChatController::class, 'store'])->name('store');
    Route::post('/chats/delete', [ChatController::class, 'delete'])->name('delete');
  });
  
  Route::post('/prompts', [PromptController::class, 'store'])->name('prompts.store');
});

// Public routes
Route::get('/plans', [PlanController::class, 'index']);

// Subscription routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('subscriptions')->group(function () {
        Route::post('/', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::get('/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
        Route::get('/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    });
});
