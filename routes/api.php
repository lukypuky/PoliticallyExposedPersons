<?php

use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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

//public routes
Route::post('/get_authorization_token ', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//protected routes
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/get_pep_categories', [PersonController::class, 'index']);
    Route::post('/delete_authorization_token', [AuthController::class, 'logout']);
    Route::post('/refresh_token', [AuthController::class, 'refresh']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::resource('get_pep_categories', PersonController::class);
