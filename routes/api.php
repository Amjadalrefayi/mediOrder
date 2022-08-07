<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PharmacyController;

use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupporterController;


use App\Http\Controllers\AddressController;
use App\Http\Controllers\ComplaintController;

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



   Route::get('/customer/all', [CustomerController::class, 'index'])->name('customertable');
   Route::delete('/deletecustomer/{id}', [CustomerController::class, 'destroy'])->name('deletecustomer');

   Route::get('pharmacy/all', [PharmacyController::class, 'index'])->name('pharmacytable');
   Route::get('pharmacy/create', [PharmacyController::class, 'create'])->name('createpharmacy');
   Route::post('/storepharmacy', [PharmacyController::class, 'store'])->name('storepharmacy');
   Route::delete('/deletepharmacy/{id}', [PharmacyController::class, 'destroy'])->name('deletepharmacy');
   Route::get('/editpharmacy/{pharmacy}', [PharmacyController::class, 'edit'])->name('editpharmacypage');
   Route::put('/updatepharmacy/{id}', [PharmacyController::class, 'update'])->name('updatepharmacy');


   Route::get('/driver/all', [DriverController::class, 'index'])->name('drivertable');
   Route::delete('/deletedriver/{id}', [DriverController::class, 'destroy'])->name('deletedriver');
   Route::get('driver/create', [DriverController::class, 'create'])->name('createdriver');
   Route::post('/storedriver', [DriverController::class, 'store'])->name('storedriver');
   Route::get('/editdriver/{driver}', [DriverController::class, 'edit'])->name('editdriverpage');
   Route::put('/updatedriver/{id}', [DriverController::class, 'update'])->name('updatedriver');

   Route::get('/supporter/all', [SupporterController::class, 'index'])->name('supportertable');
   Route::delete('/deletesupporter/{id}', [SupporterController::class, 'destroy'])->name('deletesupporter');
   //Route::get('/supporter/create', [SupporterController::class, 'create'])->name('createsupporter');
   Route::post('/storesupporter', [SupporterController::class, 'store'])->name('storesupporter');
   Route::put('/editsupporter/{supporter}', [SupporterController::class, 'edit'])->name('editsupporterpage');
   Route::put('/updatesupporter/{id}', [SupporterController::class, 'update'])->name('updatesupporter');

   Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum','isAdmin']], function () {
   });
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum','isAdmin']], function () {


});


Route::group(['prefix' => 'pharmacy', 'middleware' => ['auth:sanctum','isPharmacy']], function () {


});



// Route::post('/cart', [OrderController::class, 'customerPhOrderStore']);
// Route::get('/pro', [ProductController::class, 'index']);

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

    Route::put('/update/{id}', [CustomerController::class, 'update']);
    Route::get('/show/{id}', [CustomerController::class, 'show']);
    Route::delete('/delete/{id}', [CustomerController::class, 'destroy']);
    Route::post('order/default', [OrderController::class, 'customerPhOrderStore']);
    Route::post('order/prescription', [OrderController::class, 'rashetaCustomerOrder']);
    Route::get('order/live', [OrderController::class, 'showLiveCustomerOrders']);
    Route::get('order/history', [OrderController::class, 'showHistoryCustomerOrders']);
});

// //------pharmacy------//

Route::group(['prefix' => 'pharmacy', 'middleware' => ['auth:sanctum']], function () {

    Route::post('/store', [PharmacyController::class, 'store']);
    Route::get('/index', [PharmacyController::class, 'index']);

    Route::get('/showPharmacesPendingOrders/{id}', [PharmacyController::class, 'showPharmacesPendingOrders']);
    Route::get('/showPharmacesDoneOrders/{id}', [PharmacyController::class, 'showPharmacesDoneOrders']);
    Route::put('/update/{id}', [PharmacyController::class, 'update']);

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
    Route::get('order', [OrderController::class, 'showDriverOrders']);
    Route::get('order/live', [OrderController::class, 'showLiveDriverOrders']);
    Route::get('order/history', [OrderController::class, 'showHistoryDriverOrders']);
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
