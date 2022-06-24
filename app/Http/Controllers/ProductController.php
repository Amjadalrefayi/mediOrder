<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController ;
use App\Http\Resources\ProductResources;
use App\Models\Pharmacy;

/**
 * @group Product Management
 *
 * APIs to manage the product
 */
class ProductController extends BaseController
{
    /**
     * show All products
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(5);
        return $this->sendResponse(ProductResources::collection($products), [
            'current_page' => $products->currentPage(),
            'nextPageUrl' => $products->nextPageUrl(),
            'previousPageUrl' => $products->previousPageUrl(),
        ]);

    }

    /**
     * show pharmacy Products
     *
     * @return \Illuminate\Http\Response
     */

    public function showPharmacyProducts($id)
    {
        if(! Pharmacy::find($id)){
            return $this->sendError('Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $products = Product::paginate(5)->where('pharmacy_id',$id);
        return $this->sendResponse(ProductResources::collection($products), [
            'current_page' => $products->currentPage(),
            'nextPageUrl' => $products->nextPageUrl(),
            'previousPageUrl' => $products->previousPageUrl(),
        ]);

    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Add product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company'=>'required',
            'image'=>'required',
            'price'=>'required',
            'type'=>'required',
            'amount',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        //image = $request->image;
        //$newImage = time() . $image;
       // $image->move('uploads/products', $newImage);
        $input = $request->all();
        $input['pharmacy_id'] = Auth::id();
        $input['available'] = true;
        $input['image'] = 'uploads/products/';
        $product = Product::create($input);

        return $this->sendResponse(new ProductResources($product), 'Products Store successfully');
    }

    /**
     * show specific Product
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Poduct Not Found', 404);
        }
        return $this->sendResponse(new ProductResources($product), 'Specific Product');
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update specified Product
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Poduct Not Found', 404);
        }
        if (Auth::id() != $product->pharmacy_id) {
            return $this->sendError('Not Valid to update', 'This product for another user');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        //image = $request->image;
        //$newImage = time() . $image->getClientOriginalName();
        //$image->move('uploads/products', $newImage);

        $product->name = $request->name;
        $product->company = $request->company;
        $product->type = $request->type;
        $product->update();

        return $this->sendResponse(new ProductResources($product), 'Product Updated successfully');
    }

    /**
     * Delete specified product
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Poduct Not Found', 404);
        }
        if (Auth::id() != $product->pharmacy_id) {
            return $this->sendError('Not Valid to delete', 'This product for another user');
        }
        $product->delete();

        return $this->sendResponse('', 'Product Deleted successfully');
    }
}
