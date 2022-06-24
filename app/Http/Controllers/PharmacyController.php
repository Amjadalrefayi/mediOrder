<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Http\Resources\PharmacyResources;
use App\Http\Resources\SimplePharmacyResources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PharmacyController extends BaseController
{

    protected AuthController $AuthCon;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pharmacies = Pharmacy::latest()->paginate(5);
        return $this->sendResponse(SimplePharmacyResources::collection($pharmacies),[
            'nextPageUrl' =>  $pharmacies->nextPageUrl() ,
            'previousPageUrl' => $pharmacies->previousPageUrl()
        ]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'location' => 'required',
            'image' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }


        $input = $request->all();
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
        return $this->sendResponse($data, 'User registed successfully');
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
