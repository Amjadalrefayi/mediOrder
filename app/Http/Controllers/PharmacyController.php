<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Http\Resources\PharmacyResources;
use App\Http\Resources\SimplePharmacyResources;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        //
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
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }


        $input = $request->all();
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
            'state' => true
        ]);

        $pharmacy->remember_token = $this->AuthCon->token($pharmacy);
        $pharmacy->update();

        $data['id']=$pharmacy['id'];
        $data['Token']=$pharmacy['remember_token'];
        $data['name'] = $pharmacy->name;
        $data['email'] = $pharmacy->email;
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
        //
    }

    /**
     * Update  specified  pharmacy
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pharmacy  $pharmacy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pharmacy $pharmacy)
    {
        //
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
        return $this->sendResponse('', 'Pharmacy deleted successfully');
    }
}
