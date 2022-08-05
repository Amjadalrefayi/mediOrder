<?php

namespace App\Http\Controllers;

use App\Enums\orderStatue;
use App\Enums\orderType;
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

    public function showLiveCustomerOrders()
    {
       $orders = Order::where('customer_id', Auth::id())->whereNotIn('state',[orderStatue::DONE, orderStatue::SOS])->get();
       return $this->sendResponse(SimpleOrderResources::collection($orders), 'Get all live order successfully');
    }

    public function showHistoryCustomerOrders()
    {
       $orders = Order::where('customer_id', Auth::id())->whereIn('state',[orderStatue::DONE, orderStatue::SOS])->get();
       return $this->sendResponse(SimpleOrderResources::collection($orders), 'Get all history order successfully');
    }

    public function rashetaCustomerOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text'=>'max:255',
            'image' => 'required|image',
            'lat' => 'required|between: -90,90',
            'lng' => 'required|between: -180,180',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $image = $request->image;
        $saveImage = time() . $image->getClientOriginalName();
        $image->move('uploads/orders', $saveImage);
        $input['image'] = 'uploads/orders/' . $saveImage;

        $order = new Order();
        $order->type = orderType::RASHETA;
        $order->customer_id = Auth::id();
        $order->lat = $request->lat;
        $order->lng = $request->lng;
        $order->image = $input['image'];
        $order->save();
        $order->refresh();
        return $this->sendResponse(new SimpleOrderResources($order), 'Order stored successfully');
    }

    public function customerPhOrderStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pharmacy_id' =>'required|exists:users,id',
            'products' => 'array|required',
            'products.*.id' => 'required|exists:products,id',
            'products.*.count' => 'required|integer|min:0',
            'lat' => 'required|between: -90,90',
            'lng' => 'required|between: -180,180',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $products = $request->products;
        $total_price=0;

        $order = new Order();
        $order->pharmacy_id = $request['pharmacy_id'];
        $order->customer_id = Auth::id();
        $order->lat = $request['lat'];
        $order->lng = $request['lng'];
        $order->save();
        foreach($products as $item){
            Cart::create([
                'order_id' => $order->id ,
                'product_id' => $item['id'],
                'count' => $item['count']
            ]);
            $product=Product::find($item['id']);
            $total_price += $product->price * $item['count'];
        }
        $order->total_price = $total_price;
        $order->save();
        $order->refresh();

        return $this->sendResponse(new SimpleOrderResources($order), 'Order stored successfully');

        return new SimpleOrderResources($order);
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
