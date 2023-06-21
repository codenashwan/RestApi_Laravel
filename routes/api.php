<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['json']], function () {

    Route::get('/', [App\Http\Controllers\Api\api::class, 'home']);
    Route::post('contact', [App\Http\Controllers\Api\api::class, 'contact']);
    Route::get('properties', [App\Http\Controllers\Api\api::class, 'properties']);
    Route::get('properties/{id}', [App\Http\Controllers\Api\api::class, 'property']);
    Route::get('users', [App\Http\Controllers\Api\api::class, 'users']);
    Route::get('users/{id}', [App\Http\Controllers\Api\api::class, 'user']);
    Route::post('login', [App\Http\Controllers\Api\api::class, 'login']);
    Route::post('register', [App\Http\Controllers\Api\api::class, 'register']);
    Route::post('forgot', [App\Http\Controllers\Api\api::class, 'forgot']);
    Route::post('reset', [App\Http\Controllers\Api\api::class, 'reset'])->name('password.reset');

    Route::get('verify-email/{id}/{hash}', [App\Http\Controllers\Api\api::class, 'verify'])->name('verification.verify');

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::post('email/verification-notification', [App\Http\Controllers\Api\api::class, 'sendVerificationEmail']);
        Route::get('profile', [App\Http\Controllers\Api\api::class, 'profile']);
        Route::get('profile/properties', [App\Http\Controllers\Api\api::class, 'ProfileProperties']);
        Route::delete('profile/properties/{id}', [App\Http\Controllers\Api\api::class, 'DeleteProperty']);



        Route::post('logout', [App\Http\Controllers\Api\api::class, 'logout']);
    });
});





// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
