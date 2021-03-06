<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResources;
use App\Http\Resources\SimpleOrderResources;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

/**
 * @group Order
 *
 * APIs to manage the orders
 */
class OrderController extends BaseController
{
    protected AuthController $AuthCon;
    public function index()
    {
        $orders = Order::paginate(5);
        return $this->sendResponse(OrderResources::collection($orders), [
            'current_page' => $orders->currentPage(),
            'nextPageUrl' => $orders->nextPageUrl(),
            'previousPageUrl' => $orders->previousPageUrl(),
        ]);
    }

    public function showCustomerOrders($id)
    {
        if(! Customer::find($id)){
            return $this->sendError('Not Found');
        }
        $customer = Customer::find($id);
        $orders = Order::where('customer_id',$id)->paginate(5);
        return $this->sendResponse(OrderResources::collection($orders), [
            'current_page' => $orders->currentPage(),
            'nextPageUrl' => $orders->nextPageUrl(),
            'previousPageUrl' => $orders->previousPageUrl(),
        ]);

    }

    public function showPharmacyOrders($id)
    {
        if(! Pharmacy::find($id)){
            return $this->sendError('Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $orders = Order::where('pharmacy_id',$id)->paginate(5);
        return $this->sendResponse(OrderResources::collection($orders), [
            'current_page' => $orders->currentPage(),
            'nextPageUrl' => $orders->nextPageUrl(),
            'previousPageUrl' => $orders->previousPageUrl(),
        ]);

    }



    public function customerOrderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'note'=>'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }


    }

    public function customerPhOrderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pharmacy_id' =>'required|exists:users,id',
            'products' => 'array|required',
            'products.*.id' => 'required|exists:products,id',
            'products.*.count' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        $products = $request->products;
        $total_price=0;

        $order = new Order();
        foreach($products as $item){
            Cart::create([
                'order_id' ,
                'product_id' => $item['id'],
                'count' => $item['count']
            ]);
            $product=Product::find(1)->first();
            $total_price += $product->price * $item['count'];
        }

        $order->pharmacy_id = $request['pharmacy_id'];
        $order->customer_id = 1;
        $order->total_price = $total_price;
        $order->save();
        $order->refresh();


    }





    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->sendError('Order Not Found', 404);
        }
        return $this->sendResponse(new OrderResources($order), 'Specific order');
    }

    public function update(Request $request, Order $order)
    {
        //
    }


    public function destroy($id){

        if(! Order::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $order = Order::find($id);
        $order->delete();
        return $this->sendResponse('', 'Order deleted successfully');
    }
}
