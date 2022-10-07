<?php

use App\Http\Controllers\Api\UserController as UserController;
use App\Http\Controllers\Api\AuthController as AuthController;

use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);


    Route::group(['prefix' => 'client', 'middleware' => ['role']], function () {

        Route::middleware(['auth:api'])->get('user', [UserController::class, 'index']);
        Route::middleware(['auth:api'])->post('user', [UserController::class, 'create']);
        Route::middleware(['auth:api'])->get('user/{id}', [UserController::class, 'detail']);
        Route::middleware(['auth:api'])->put('user/{id}', [UserController::class, 'update']);
        Route::middleware(['auth:api'])->delete('user/{id}', [UserController::class, 'delete']);
    });
});