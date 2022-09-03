<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [App\Http\Controllers\ChatController::class, 'index'])->middleware('auth:api')->name('home');

Route::post('register',[AuthenticationController::class,'registerUserExample']);
Route::post('login',[AuthenticationController::class,'loginUserExample']);
//add this middleware to ensure that every request is authenticated
Route::middleware('auth:api')->group(function(){
    Route::get('user', [AuthenticationController::class,'authenticatedUserDetails']);
});


Route::post('/send-message', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('send-message');
Route::post('/seen-message', [App\Http\Controllers\ChatController::class, 'seenMessage'])->name('seen-message');
