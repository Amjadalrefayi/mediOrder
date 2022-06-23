<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController ;
use App\Http\Resources\ProductResources;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(2);
        return $this->sendResponse(ProductResources::collection($products), [
            'current_page' => $products->currentPage(),
            'nextPageUrl' => $products->nextPageUrl(),
            'previousPageUrl' => $products->previousPageUrl(),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
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
            'available'=>'required',
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
        $input['image'] = 'uploads/products/';
        $product = Product::create($input);

        return $this->sendResponse(new ProductResources($product), 'Products Store successfully');
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
