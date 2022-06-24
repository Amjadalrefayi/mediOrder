<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PharmacyController;
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
    Route::post('customer/register', [CustomerController::class,'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

//------Product CRUD------//

Route::group(['prefix' => 'products', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/index', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/show/{id}', [ProductController::class, 'show']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
    Route::delete('/delete', [ProductController::class, 'destroy']);

});


// //------Customer------//

Route::group(['prefix' => 'customer', 'middleware' => ['auth:sanctum']], function () {

    Route::get('/index', [CustomerController::class, 'index']);
    Route::put('/update/{id}', [CustomerController::class, 'update']);
    Route::get('/show/{id}', [CustomerController::class, 'show']);
    Route::delete('/delete/{id}', [CustomerController::class, 'destroy']);

});


Route::group(['prefix' => 'pharmacy', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [PharmacyController::class, 'store']);
    Route::get('/index', [PharmacyController::class, 'index']);
    Route::put('/update/{id}', [PharmacyController::class, 'update']);
    Route::get('/show/{id}', [PharmacyController::class, 'show']);
    Route::delete('/delete/{id}', [PharmacyController::class, 'destroy']);

});
