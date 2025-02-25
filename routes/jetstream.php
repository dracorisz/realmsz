<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use App\Http\Controllers\ProfileController;
use Laravel\Jetstream\Jetstream;

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    $authMiddleware = config('jetstream.guard') ? 'auth:' . config('jetstream.guard') : 'auth';
    $authSessionMiddleware = config('jetstream.auth_session', false) ? config('jetstream.auth_session') : null;

    Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::delete('/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])->name('other-browser-sessions.destroy');
        Route::delete('/profile-photo', [ProfilePhotoController::class, 'destroy'])->name('current-user-photo.destroy');

        if (Jetstream::hasAccountDeletionFeatures()) {
            Route::delete('/destroy', [CurrentUserController::class, 'destroy'])->name('current-user.destroy');
        }
    });
});
