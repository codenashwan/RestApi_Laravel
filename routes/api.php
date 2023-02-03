<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::group(['middleware' => ['json']] , function(){
       
        Route::get('/',[App\Http\Controllers\Api\api::class, 'home']);
        Route::post('contact',[App\Http\Controllers\Api\api::class, 'contact']);

    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
