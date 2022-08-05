<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupporterController;


use App\Http\Controllers\AddressController;

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

Route::get('loginHome', function(){
  return view('auth.login');
 })->name('loginHome');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum','isAdmin']], function () {


});


Route::group(['prefix' => 'pharmacy', 'middleware' => ['auth:sanctum','isPharmacy']], function () {


});



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

// //------pharmacy------//

Route::group(['prefix' => 'pharmacy', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [PharmacyController::class, 'store']);
    Route::get('/index', [PharmacyController::class, 'index']);
    Route::get('/showPharmacesPendingOrders/{id}', [PharmacyController::class, 'showPharmacesPendingOrders']);
    Route::get('/showPharmacesDoneOrders/{id}', [PharmacyController::class, 'showPharmacesDoneOrders']);
    Route::put('/update/{id}', [PharmacyController::class, 'update']);
    Route::get('/show/{id}', [PharmacyController::class, 'show']);
    Route::delete('/delete/{id}', [PharmacyController::class, 'destroy']);
    Route::get('/products/{pharmacy_id}', [ProductController::class, 'showPharmacyProducts']);

});

// //------Order------//

Route::group(['prefix' => 'order', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [OrderController::class, 'store']);
    Route::get('/index', [OrderController::class, 'index']);
    Route::put('/update/{id}', [OrderController::class, 'update']);
    Route::get('/show/{id}', [OrderController::class, 'show']);
    Route::delete('/delete/{id}', [OrderController::class, 'destroy']);
    Route::get('/products/{customer_id}', [OrderController::class, 'showCustomerOrders']);
    Route::get('/products/{pharmacy_id}', [OrderController::class, 'showPharmacyOrders']);


});
// //------Driver------//

Route::group(['prefix' => 'driver', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [DriverController::class, 'store']);
    Route::get('/index', [DriverController::class, 'index']);
    Route::put('/update/{id}', [DriverController::class, 'update']);
    Route::get('/show/{id}', [DriverController::class, 'show']);
    Route::delete('/delete/{id}', [DriverController::class, 'destroy']);

});


//------Address CRUD------//

Route::group(['prefix' => 'address', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/index', [AddressController::class, 'index']);
    Route::get('/useraddress', [AddressController::class, 'useraddress']);
    Route::post('/store', [AddressController::class, 'store']);
    Route::get('/show/{id}', [AddressController::class, 'show']);
    Route::put('/update/{id}', [AddressController::class, 'update']);
    Route::delete('/delete/{id}', [AddressController::class, 'destroy']);
});

//------complaint CRUD------//

Route::group(['prefix' => 'complaint', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/index', [ComplaintController::class, 'index']);
    Route::get('/usercomplaints', [ComplaintController::class, 'usercomplaints']);
    Route::get('/complaintstrashed', [ComplaintController::class, 'complaintstrashed']);
    Route::get('/usertrashedcomplaints', [ComplaintController::class, 'usertrashedcomplaints']);
    Route::post('/store', [ComplaintController::class, 'store']);
    Route::get('/show/{id}', [ComplaintController::class, 'show']);
    Route::put('/update/{id}', [ComplaintController::class, 'update']);
    Route::delete('/softdelete/{id}', [ComplaintController::class, 'softdelete']);
    Route::delete('/hdelete/{id}', [ComplaintController::class, 'hdelete']);

});

// //------supporter CRUD------//

Route::group(['prefix' => 'supporter', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [SupporterController::class, 'store']);
    Route::get('/index', [SupporterController::class, 'index']);
    Route::put('/update/{id}', [SupporterController::class, 'update']);
    Route::get('/show/{id}', [SupporterController::class, 'show']);
    Route::delete('/delete/{id}', [SupporterController::class, 'destroy']);

});

// //------Admin CRUD------//

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [AdminController::class, 'store']);
    Route::get('/index', [AdminController::class, 'index']);
    Route::put('/update/{id}', [AdminController::class, 'update']);
    Route::get('/show/{id}', [AdminController::class, 'show']);
    Route::delete('/delete/{id}', [AdminController::class, 'destroy']);

});
