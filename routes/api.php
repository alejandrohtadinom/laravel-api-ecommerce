<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthUserController;
use App\Models\Profile;

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

// Route::apiResource('/profile', ProfileController::class);

Route::post('/auth/register', [AuthUserController::class, 'store']);
Route::post('/auth/login', [AuthUserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/auth/profile', [AuthUserController::class, 'show']);
    Route::patch('/auth/profile', [AuthUserController::class, 'update']);
    Route::post('/auth/logout', [AuthUserController::class, 'logout']);

    Route::post('/auth/profile/billing', [ProfileController::class, 'store']);
    Route::get('/auth/profile/billing', [ProfileController::class, 'show']);
});

