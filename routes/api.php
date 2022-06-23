<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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


//------Rigeter / Login------//

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

//------Product CRUD------//

Route::group(['prefix' => 'products', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/index', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/show/{id}', [ProductController::class, 'show']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
    Route::delete('/delete/{id}', [ProductController::class, 'destroy']);

});
