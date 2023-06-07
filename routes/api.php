<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\TagController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\PostController;
use App\Http\Controllers\AaaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function() {

  Route::prefix((function() {
      return '';
    })())->group(function() {

        //'auth:api'
        Route::middleware(['api', ])->group(function () {
            Route::apiResource('posts', PostController::class);
            Route::apiResource('categories', CategoryController::class);
            Route::apiResource('tags', TagController::class);
            Route::apiResource('users', UserController::class);
        });
    });

});