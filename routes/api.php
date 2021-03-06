<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Passport\AuthController;

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

Route::post('register', [AuthController::class, 'register']);
Route::get('users', [ChatController::class, 'users']);
Route::get('user', [AuthController::class, 'user']);

Route::group(['prefix' => 'chat', 'middleware' => ['auth:api']], function() {
    Route::get('inbox', [ChatController::class, 'conversations']);
    Route::get('conversation/{chat_conversaion}', [ChatController::class, 'conversation']);
    Route::get('conversation/user/{user_id}', [ChatController::class, 'conversationWithUser']);
    Route::post('message/store', [ChatController::class, 'storeMessage']);
});
