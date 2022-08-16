<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Order;
use App\Enums\orderStatue;
use App\Http\Resources\PharmacyResources;
use App\Http\Resources\OrderResources;
use App\Http\Resources\SimplePharmacyResources;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

/**
 * @group Pharmacy Management
 *
 * APIs to manage the Pharmacy
 */
class PharmacyController extends BaseController
{

    protected AuthController $AuthCon;
    /**
     * Get All pharmacies
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pharmacies = Pharmacy::latest()->get();
        return $this->sendResponse(SimplePharmacyResources::collection($pharmacies), 'Get All Pharmacies');
    }
    public function allpharmacies(){

        $pharmacies = Pharmacy::latest()->paginate(5);
        return view('supportdashboard.allpharmaciestable')->with('pharmacies',$pharmacies);
    }

    public function indexForAdmin()
    {
        $pharmacies = Pharmacy::latest()->paginate(5);
        return view('dashboard.pharmacytable')->with('pharmacies',$pharmacies);
    }

    public function showPharmacesPendingOrders($id)
    {
        if(! Pharmacy::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $orders = Order::where('pharmacy_id',$id)->where('state' , orderStatue::PROCESSING)->paginate(5);
        return $this->sendResponse(OrderResources::collection($orders), [
            'current_page' => $orders->currentPage(),
            'nextPageUrl' => $orders->nextPageUrl(),
            'previousPageUrl' => $orders->previousPageUrl(),
        ]);
    }

    public function showPharmacesDoneOrders($id)
    {
        if(! Pharmacy::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $orders = Order::where('pharmacy_id',$id)->where('state' , orderStatue::DONE)->paginate(5);
        return $this->sendResponse(OrderResources::collection($orders), [
            'current_page' => $orders->currentPage(),
            'nextPageUrl' => $orders->nextPageUrl(),
            'previousPageUrl' => $orders->previousPageUrl(),
        ]);
        if(Customer::find(Auth::id())) {
            $pharmacies = Pharmacy::latest()->get();
            return $this->sendResponse(SimplePharmacyResources::collection($pharmacies), 'Get All Pharmacies');
        }
        else {
            $pharmacies = Pharmacy::latest()->paginate(5);
            return $this->sendResponse(SimplePharmacyResources::collection($pharmacies),[
                'nextPageUrl' =>  $pharmacies->nextPageUrl() ,
                'previousPageUrl' => $pharmacies->previousPageUrl()
            ]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.createpharmacy');
    }

    /**
     * Add pharmacy
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->AuthCon  = new AuthController();
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone'=> 'required|min:13',
            'location'=> 'required',
            'image'=>'required|image',
            'lat' => 'required|between: -90,90',
            'lng' => 'required|between: -180,180',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if(!array_key_exists('image' , $input))
        {
            $input['image'] = null;
        }
        $image = $request->image;
        $saveImage = time() . $image->getClientOriginalName();
        $image->move('uploads/pharmacies', $saveImage);
        $input['image'] = 'uploads/pharmacies/' . $saveImage;

        $pharmacy = Pharmacy::create([
            'name' =>  $input['name'],
            'email' =>  $input['email'],
            'password' =>  $input['password'],
            'phone'=> $input['phone'],
            'location'=> $input['location'],
            'image' => $input['image'],
            'lat' => $input['lat'],
            'lng' => $input['lng'],
            'state' => true
        ]);

        $pharmacy->remember_token = $this->AuthCon->token($pharmacy);
        $pharmacy->update();

        $data['id']=$pharmacy['id'];
        $data['Token']=$pharmacy['remember_token'];
        $data['name'] = $pharmacy->name;
        $data['email'] = $pharmacy->email;
        return redirect()->route('pharmacytable');
        return $this->sendResponse($data, 'Pharmacy registed successfully');
    }

    /**
     * Show specified pharmacy
     *
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        if(! Pharmacy::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $phrmacy = Pharmacy::find($id);
        return $this->sendResponse(new PharmacyResources($phrmacy), 'Pharmacy show successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function edit(Pharmacy $pharmacy)
    {
        return view('dashboard.editpharmacy')->with('pharmacy',$pharmacy);
    }

    /**
     * Update  specified  pharmacy
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        if(! Pharmacy::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'min:3|nullable',
            'password' => 'min:8|nullable',
            'phone'=> 'min:13|nullable',
            'location'=> 'nullable',
            'image' => 'mimes:jpeg,jpg,png | nullable',
        ]);


        $input = $request->all();

        if(!array_key_exists('image' , $input))
        {
            $input['image'] = null;
        }

        $pharmacy->name = $input['name'];
        $pharmacy->password = $input['password'] = Hash::make($request['password']);
        $pharmacy->phone = $input['phone'];
        $pharmacy->location = $input['location'];
        $pharmacy->image = $input['image'];
        $pharmacy->update();

        return redirect()->route('pharmacytable');
    }

    /**
     * Delete specified pharamcy
     *
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        if(! Pharmacy::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $pharmacy->delete();
        return redirect()->route('pharmacytable');
        return $this->sendResponse('', 'Pharmacy deleted successfully');
    }
    public function destroypharmacy($id){

        if(! Pharmacy::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $pharmacy = Pharmacy::find($id);
        $pharmacy->delete();
        return redirect()->route('allpharmacies');
    }

    public function search($name)
    {
        $pharmacies = Pharmacy::where('name', 'Like', '%' . $name . '%' )->get();
        return $this->sendResponse(SimplePharmacyResources::collection($pharmacies), 'Get All Pharmacies');
    }


}
