<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FocusController;
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

// Public routes (accessible without authentication)
Route::get('/', function () {
    return Inertia::render('Front/Welcome');
})->name('welcome');
Route::get('/landing', function () {
    return Inertia::render('Front/Welcome');
})->name('landing');
Route::get('/ecosystem', function () {
    return Inertia::render('Front/Ecosystem');
})->name('ecosystem');
Route::get('/partnership', function () {
    return Inertia::render('Front/Partnership');
})->name('partnership');
Route::post('/partnership', [PartnershipController::class, 'store'])->name('partnership.store');
Route::get('/roadmap', function () {
    return Inertia::render('Front/Roadmap');
})->name('roadmap');
Route::get('/faq', function () {
    return Inertia::render('Front/FAQ');
})->name('faq');
Route::get('/contact', function () {
    return Inertia::render('Front/Contact');
})->name('contact');
Route::get('/ipo', function () {
    return Inertia::render('Front/IPO');
})->name('ipo');
Route::get('/ico', function () {
    return Inertia::render('Front/ICO');
})->name('ico');
Route::get('/terms-of-service', function () {
    return Inertia::render('Front/TermsOfService');
})->name('terms-of-service');
Route::get('/privacy-policy', function () {
    return Inertia::render('Front/PrivacyPolicy');
})->name('privacy-policy');
Route::get('/whitepaper', function () {
    return Inertia::render('Front/Whitepaper');
})->name('whitepaper');
Route::get('/team', function () {
    return Inertia::render('Front/Team');
})->name('team');

// Protected routes (require authentication and subscription, except for suzy role)
Route::middleware(['protected'])->group(function () {
    // Dashboard and main features
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::get('/studio', function () {
        return Inertia::render('App/Studio/Main');
    })->name('studio');
    
    Route::get('/milai', [ChatController::class, 'index'])->name('milai');
    Route::get('/focus', [FocusController::class, 'index'])->name('focus');
    Route::get('/network', [NetworkController::class, 'index'])->name('network');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/plans', [ItemController::class, 'index'])->name('plans');

    // Subscription management
    Route::prefix('subscriptions')->group(function () {
        Route::get('/', function () {
            return Inertia::render('App/Profile/Main');
        })->name('subscriptions.plans');
        Route::get('/waiting', function () {
            return Inertia::render('Subscription/Waiting');
        })->name('waiting');
        Route::get('/success', function () {
            return Inertia::render('Subscription/Success');
        })->name('subscriptions.success');
        Route::get('/cancel', function () {
            return Inertia::render('Subscription/Cancel');
        })->name('subscriptions.cancel');
    });
});

require_once __DIR__ . '/jetstream.php';
