<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Http\Resources\DriverResources;
use App\Http\Resources\SimpleDriverResources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DriverController extends BaseController
{
    protected AuthController $AuthCon;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = Driver::latest()->paginate(5);
        return $this->sendResponse(SimpleDriverResources::collection($drivers),[
            'nextPageUrl' =>  $drivers->nextPageUrl() ,
            'previousPageUrl' => $drivers->previousPageUrl()
        ]);
    }


    /**
     * Store a newly created resource in storage.
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
            'gender'=>'in:male,female|nullable',
            'location'=> 'required',
            'image' => 'mimes:jpeg,jpg,png | nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }


        $input = $request->all();
        $driver = Driver::create([
            'name' =>  $input['name'],
            'email' =>  $input['email'],
            'password' =>  $input['password'],
            'phone'=> $input['phone'],
            'gender'=>$input['gender'],
            'location'=> $input['location'],
            'image' => $input['image'],
            'state' => true
        ]);

        $driver->remember_token = $this->AuthCon->token($driver);
        $driver->update();

        $data['id']=$driver['id'];
        $data['Token']=$driver['remember_token'];
        $data['name'] = $driver->name;
        $data['email'] = $driver->email;
        return $this->sendResponse($data, 'Driver registed successfully');
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(! Driver::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $driver = Driver::find($id);
        return $this->sendResponse(new SimpleDriverResources($driver), 'Driver show successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update($id , Request  $request)
    {
        if(! Driver::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $driver = Driver::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'min:3|nullable',
            'password' => 'min:8|nullable',
            'phone'=> 'min:13|nullable',
            'gender'=>'in:male,female|nullable',
            'location'=> 'required',
            'image' => 'mimes:jpeg,jpg,png | nullable',
        ]);

        $driver->name = $request->name;
        $driver->email = $request->email;
        $driver->password = $request['password'] = Hash::make($request['password']);
        $driver->phone = $request->phone;
        $driver->gender = $request->gender;
        $driver->location = $request->location;
        $driver->image = $request->image;
        $driver->state = $request->state;
        $driver->update();
        return $this->sendResponse('', 'Driver updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(! Driver::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $driver = Driver::find($id);
        $driver->delete();
        return $this->sendResponse('', 'Driver deleted successfully');

    }
}
