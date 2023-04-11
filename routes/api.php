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
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
