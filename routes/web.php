<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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

Route::group(['middleware' => 'auth'], function() {  
    Route::get('/', [ChatController::class, 'index']);
    Route::get('/chat/{id}', [ChatController::class, 'conversation'])->name('chat');
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send-message');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
