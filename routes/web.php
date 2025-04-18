<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PartnershipController;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NFTController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return Inertia::render('Front/Welcome'); })->name('welcome');
Route::get('/landing', function () { return Inertia::render('Front/Welcome'); })->name('landing');
Route::get('/ecosystem', function () { return Inertia::render('Front/Ecosystem'); })->name('ecosystem');
Route::get('/partnership', function () { return Inertia::render('Front/Partnership'); })->name('partnership');
Route::post('/partnership', [PartnershipController::class, 'store'])->name('partnership.store');
Route::get('/roadmap', function () { return Inertia::render('Front/Roadmap'); })->name('roadmap');
Route::get('/faq', function () { return Inertia::render('Front/FAQ'); })->name('faq');
Route::get('/contact', function () { return Inertia::render('Front/Contact'); })->name('contact');
Route::get('/ipo', function () { return Inertia::render('Front/IPO'); })->name('ipo');
Route::get('/ico', function () { return Inertia::render('Front/ICO'); })->name('ico');
Route::get('/terms-of-service', function () { return Inertia::render('Front/TermsOfService'); })->name('terms-of-service');
Route::get('/privacy-policy', function () { return Inertia::render('Front/PrivacyPolicy'); })->name('privacy-policy');
Route::get('/whitepaper', function () { return Inertia::render('Front/Whitepaper'); })->name('whitepaper');
Route::get('/plans', function () { return Inertia::render('Front/Plans'); })->name('plans');
Route::get('/nft', [NFTController::class, 'publicIndex'])->name('nft');
Route::get('/team', function () { return Inertia::render('Front/Team'); })->name('team');

// Package Routes (moved outside auth middleware for public viewing)
Route::get('/packages', [PackageController::class, 'index'])->name('packages');
Route::get('/packages/{package}', [PackageController::class, 'show'])->name('packages.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/milai', [ChatController::class, 'index'])->name('milai');
    Route::get('/focus', [ItemController::class, 'focus'])->name('focus');
    Route::get('/plans', [ItemController::class, 'index'])->name('plans');
    Route::get('/studio', function () { return Inertia::render('App/Studio/Main'); })->name('studio');
    Route::get('/finance', function () { return Inertia::render('App/Finance'); })->name('finance');
    Route::get('/crypto', function () { return Inertia::render('App/Crypto'); })->name('crypto');
    Route::get('/websites', function () { return Inertia::render('App/Websites'); })->name('websites');
    Route::get('/storage', function () { return Inertia::render('App/Storage'); })->name('storage');
    Route::get('/network', [NetworkController::class, 'index'])->name('network');
    
    // NFT Routes
    Route::get('/nft/create', [NFTController::class, 'create'])->name('nft.create');
    Route::post('/nft', [NFTController::class, 'store'])->name('nft.store');
    Route::get('/nft/{id}', [NFTController::class, 'show'])->name('nft.show');
    Route::get('/nft/{id}/edit', [NFTController::class, 'edit'])->name('nft.edit');
    Route::put('/nft/{id}', [NFTController::class, 'update'])->name('nft.update');
    Route::delete('/nft/{id}', [NFTController::class, 'destroy'])->name('nft.destroy');
    
    // Protected Package Routes
    Route::post('/packages/{package}/subscribe', [PackageController::class, 'subscribe'])->name('packages.subscribe');
    Route::post('/subscription/cancel', [PackageController::class, 'cancel'])->name('subscription.cancel');
});

// Subscription Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/subscription/checkout', [SubscriptionController::class, 'createCheckoutSession'])->name('subscription.checkout');
    Route::get('/subscription/success', function () {
        return Inertia::render('Subscription/Success');
    })->name('subscription.success');
    Route::get('/subscription/cancel', function () {
        return Inertia::render('Subscription/Cancel');
    })->name('subscription.cancel');
});

// Stripe Webhook
Route::post('/stripe/webhook', [SubscriptionController::class, 'webhook'])->name('stripe.webhook');

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'success', 'message' => 'Database connection successful']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});

require_once __DIR__ . '/jetstream.php';