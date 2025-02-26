<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use Inertia\Inertia;

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

Route::get('/', function () { return Inertia::render('Front/Welcome'); })->name('landing');
Route::get('/ecosystem', function () { return Inertia::render('Front/Ecosystem'); })->name('ecosystem');
Route::get('/partnership', function () { return Inertia::render('Front/Partnership'); })->name('partnership');
Route::get('/roadmap', function () { return Inertia::render('Front/Roadmap'); })->name('roadmap');
Route::get('/ipo', function () { return Inertia::render('Front/IPO'); })->name('ipo');
Route::get('/ico', function () { return Inertia::render('Front/ICO'); })->name('ico');
Route::get('/terms-of-service', function () { return Inertia::render('Front/TermsOfService'); })->name('terms');
Route::get('/privacy-policy', function () { return Inertia::render('Front/PrivacyPolicy'); })->name('policy');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/milai', [ChatController::class, 'index'])->name('milai');
    Route::get('/focus', [ItemController::class, 'focus'])->name('focus');
    Route::get('/plans', [ItemController::class, 'index'])->name('plans');
    Route::get('/studio', function () { return Inertia::render('App/Studio/Main'); })->name('studio');
    Route::get('/finance', function () { return Inertia::render('App/Finance'); })->name('finance');
    Route::get('/crypto', function () { return Inertia::render('App/Crypto'); })->name('crypto');
    Route::get('/websites', function () { return Inertia::render('App/Websites'); })->name('websites');
    Route::get('/storage', function () { return Inertia::render('App/Storage'); })->name('storage');
    Route::get('/network', [NetworkController::class, 'index'])->name('network');
});

require_once __DIR__ . '/jetstream.php';