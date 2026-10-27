<?php

use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
<<<<<<< HEAD
use Illuminate\Http\Request;
=======
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
use Illuminate\Support\Facades\Route;

Route::prefix('api/auth')->group(function () {
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->middleware('guest');
    Route::get('/user', [EmailVerificationController::class, 'user']);

    Route::middleware('auth')->group(function () {
        Route::get('/email/status', [EmailVerificationController::class, 'status']);
        Route::post('/email/verification-notification', [EmailVerificationController::class, 'send'])->middleware('throttle:6,1');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    });
});

<<<<<<< HEAD
Route::get('/reset-password/{token}', function (Request $request, string $token) {
    $email = $request->query('email');
    $query = $email ? '?email='.urlencode((string) $email) : '';

    return redirect('/reset-password/'.$token.$query);
=======
// Named for the password-reset mail URL; the SPA reads {token} and ?email= from the browser URL.
Route::get('/reset-password/{token}', function () {
    return view('app');
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
})->middleware('guest')->name('password.reset');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/verify-email?verified=1');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::view('/{any?}', 'app')
<<<<<<< HEAD
    ->where('any', '^(?!api|sanctum|storage).*$');
=======
    ->where('any', '^(?!api|sanctum|storage).*$');
>>>>>>> df2d90b452a91bf8ff706f9c24fab051085173d9
