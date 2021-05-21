<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthUserController;

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

Route::post('/auth/register', [AuthUserController::class, 'store']);
Route::post('/auth/login', [AuthUserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [AuthUserController::class, 'show']);
    Route::patch('/user', [AuthUserController::class, 'update']);
    Route::post('/auth/logout', [AuthUserController::class, 'logout']);
});

Route::apiResource('/profile', ProfileController::class);
