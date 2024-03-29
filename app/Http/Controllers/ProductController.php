<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController ;
use App\Http\Resources\ProductResources;
use App\Models\Customer;
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
     * show All pharmacy products
     *
     * @return \Illuminate\Http\Response
     */
    public function showPharmacyProducts($id)
    {
        if(! Pharmacy::find($id)){
            return $this->sendError('','Not Found');
        }

        if(Customer::find(Auth::id())) {
            $products = Product::where('pharmacy_id',$id)->paginate(5);
            return $this->sendResponse(ProductResources::collection($products), 'Get All Products');
        }

        $products = Product::where('pharmacy_id',$id)->paginate(5);
        return $this->sendResponse(ProductResources::collection($products), [
            'current_page' => $products->currentPage(),
            'nextPageUrl' => $products->nextPageUrl(),
            'previousPageUrl' => $products->previousPageUrl(),
        ]);

    }
    public function showPharmacyProductsView(Request $request)
    {
        $pharmacy = Auth::id();
        if(! Pharmacy::find($pharmacy)){
            return $this->sendError('','Not Found');
        }
        $products = Product::where('pharmacy_id',$pharmacy)->paginate(5);
        return view('pharmacydashboard.producttable')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Pharmacydashboard.createproduct');
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
            'image'=>'required|image',
            'price'=>'required|numeric',
            'type'=>'required',
            'amount' => 'numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        $input = $request->all();

        $image = $request->image;
        $saveImage = time() . $image->getClientOriginalName();
        $image->move('uploads/products', $saveImage);
        $input['image'] = 'uploads/products/' . $saveImage;

        $input['pharmacy_id'] = Auth::id();
        $input['available'] = true;
        $product = Product::create($input);
        return redirect()->route('producttable');
        return $this->sendResponse(new ProductResources($product), 'Product Store successfully');
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->sendError('Poduct Not Found', 404);
        }
        return $this->sendResponse(new ProductResources($product), 'Specific Product');
    }

    public function edit(Product $product)
    {
        return view('pharmacydashboard.editproduct')->with('product',$product);
    }

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
        return redirect()->route('producttable');
        return $this->sendResponse(new ProductResources($product), 'Product Updated successfully');
    }

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
        return redirect()->route('producttable');
        return $this->sendResponse('', 'Product Deleted successfully');
    }

    public function search($id, $name)
    {
        $products = Product::where('pharmacy_id', $id)->where('name', 'LIKE', '%' . $name . '%')->get();
        return $this->sendResponse(ProductResources::collection($products), 'Get All Products');
    }

    public function searchPH(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }
        $products = Product::where('pharmacy_id', Auth::id())->where('name', 'LIKE', '%' . $request->name . '%')->paginate(10);
        return view('pharmacydashboard.producttable')->with('products',$products);
    }

}
