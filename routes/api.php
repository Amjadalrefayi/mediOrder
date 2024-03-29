<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\AdminController;

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

//                           -------admain dashboard-------

    Route::get('/customer/all', [CustomerController::class, 'index'])->name('customertable');
    Route::delete('/deletecustomer/{id}', [CustomerController::class, 'destroy'])->name('deletecustomer');

    Route::get('pharmacy/all', [PharmacyController::class, 'indexForAdmin'])->name('pharmacytable');
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
    Route::get('supporter/create', [SupporterController::class, 'create'])->name('createsupporter');
    Route::post('/storesupporter', [SupporterController::class, 'store'])->name('storesupporter');
    Route::get('/editsupporter/{supporter}', [SupporterController::class, 'edit'])->name('editsupporterpage');
    Route::put('/updatesupporter/{id}', [SupporterController::class, 'update'])->name('updatesupporter');

    Route::post('users/search', [CustomerController::class, 'search'])->name('searchCustomer');






   //                           -------pharmacy dashboard-------

   Route::get('/product/all', [ProductController::class, 'showPharmacyProductsView'])->name('producttable');
   Route::get('/defultorder/all', [OrderController::class, 'showPharmacyOrdersView'])->name('ordertable');
   Route::get('/acceptedordertable/all', [OrderController::class, 'acceptedOrdersTables'])->name('acceptedordertable');
   Route::get('/rejectedordertable/all', [OrderController::class, 'rejectedOrdersTables'])->name('rejectedordertable');
   Route::get('/rashetaorder/all', [OrderController::class, 'showPharmacyRashetaOrdersView'])->name('rashetaordertable');
   Route::get('/productorder/all/{id}', [OrderController::class, 'showProductsOrder'])->name('productorder');
   Route::delete('/deleteproduct/{id}', [ProductController::class, 'destroy'])->name('deleteproduct');
   Route::get('/product/create', [ProductController::class, 'create'])->name('createproduct');
   Route::post('/storeproduct', [ProductController::class, 'store'])->name('storeproduct');
   Route::get('/editproduct/{product}', [ProductController::class, 'edit'])->name('editproductpage');
   Route::put('/updateproduct/{id}', [ProductController::class, 'update'])->name('updateproduct');
   Route::get('/complaint/create', [ComplaintController::class, 'create'])->name('createcomplaint');
   Route::post('/storecomplaint', [ComplaintController::class, 'store'])->name('storecomplaint');
   Route::post('/makeOrderAccepted/{id}', [OrderController::class, 'makeOrderACCEPTED'])->name('orderacceptednow');
   Route::post('/makeOrderAcceptedRasheta/{id}', [OrderController::class, 'makeOrderACCEPTEDRasheta'])->name('orderacceptedRasheta');
   Route::post('/makeOrderRejected/{id}', [OrderController::class, 'makeOrderREJECTED'])->name('orderrejectednow');
   Route::post('/makeOrdersos/{id}', [OrderController::class, 'makeOrdersosPH'])->name('makeOrdersosPH');

   Route::post('order/show/ph/{id}', [OrderController::class, 'showDashBoard'])->name('showOrderPH');

   Route::post('order/show/general/{id}', [OrderController::class, 'showOrderGeneral'])->name('showOrderGeneral');

   Route::post('products/search', [ProductController::class, 'searchPH'])->name('searchProducts');


   //                           -------support dashboard-------
   Route::get('/complaint/all', [ComplaintController::class, 'index'])->name('complaintstable');
   Route::get('/order/all', [OrderController::class, 'index'])->name('allordertable');
   Route::get('/all/customers', [CustomerController::class, 'allcustomers'])->name('allcustomers');
   Route::get('/all/drivers', [DriverController::class, 'alldrivers'])->name('alldrivers');
   Route::get('/all/pharmacys', [PharmacyController::class, 'allpharmacies'])->name('allpharmacies');
   Route::delete('/destroycustomer/{id}', [CustomerController::class, 'destroycustomer'])->name('destroycustomer');
   Route::delete('/destroydriver/{id}', [DriverController::class, 'destroydriver'])->name('destroydriver');
   Route::delete('/destroypharmacy/{id}', [PharmacyController::class, 'destroypharmacy'])->name('destroypharmacy');
   Route::get('/blocked/customer', [CustomerController::class, 'blockedcustomer'])->name('blockedcustomer');
   Route::get('/restor/customer/{id}', [CustomerController::class, 'restorcustomer'])->name('restorcustomer');
   Route::get('/blocked/drivers', [DriverController::class, 'blockeddriver'])->name('blockeddriver');
   Route::get('/restor/driver/{id}', [DriverController::class, 'restordriver'])->name('restordriver');
   Route::get('/blocked/pharmacy', [PharmacyController::class, 'blockedpharmacy'])->name('blockedpharmacy');
   Route::get('/restor/pharmacy/{id}', [PharmacyController::class, 'restorpharmacy'])->name('restorpharmacy');

   Route::post('/makeOrderRejectedSu/{id}', [OrderController::class, 'makeOrderSOSSupport'])->name('orderrejectednowS');

   Route::post('orders/search', [OrderController::class, 'search'])->name('searchOrder');
   Route::post('orders/search/Ph', [OrderController::class, 'searchPH'])->name('searchOrderPH');

//------Register / Login------//

Route::prefix('auth')->group(function () {
    Route::post('customer/register', [CustomerController::class,'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

//------Product CRUD------//

Route::group(['prefix' => 'products', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/index', [ProductController::class, 'index']);
    Route::post('/store', [ProductController::class, 'store']);
    Route::get('/show/{id}', [ProductController::class, 'show']);
    Route::put('/update/{id}', [ProductController::class, 'update']);
    Route::delete('/delete', [ProductController::class, 'destroy']);
    Route::get('/search/{id}/{name}', [ProductController::class, 'search']);

});


// //------Customer------//


Route::group(['prefix' => 'customer', 'middleware' => ['auth:sanctum']], function () {

    Route::put('/update', [CustomerController::class, 'update']);
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
    Route::get('/search/{name}', [PharmacyController::class, 'search']);
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
    Route::post('order/sos/{id}', [OrderController::class, 'makeOrderSOS']);
    Route::post('order/delivering/{id}', [OrderController::class, 'makeOrderDELIVERING']);
    Route::post('order/onway/{id}', [OrderController::class, 'makeOrderOnWay']);
    Route::post('order/done/{id}', [OrderController::class, 'makeOrderdone']);
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

Route::post('/store', [AdminController::class, 'store']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/index', [AdminController::class, 'index']);
    Route::put('/update/{id}', [AdminController::class, 'update']);
    Route::get('/show/{id}', [AdminController::class, 'show']);
    Route::delete('/delete/{id}', [AdminController::class, 'destroy']);
});





