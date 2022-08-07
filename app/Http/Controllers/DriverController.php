<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\orderStatue;
use App\Models\Driver;
use App\Http\Resources\SimpleDriverResources;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Resources\SimpleOrderResources;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

/**
 * @group Driver
 *
 * APIs to manage the driver
 */
class DriverController extends BaseController
{
    protected AuthController $AuthCon;

    /**
     * Get all Drivers
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $user = User::find(Auth::id())->first();
        if($user->type === 'App\Models\Admin')
     {
        $drivers = Driver::latest()->paginate(5);


         return view('dashboard.drivertable')->with('drivers',$drivers);

     }
        $drivers = Driver::latest()->paginate(5);
        return $this->sendResponse(SimpleDriverResources::collection($drivers),[
            'nextPageUrl' =>  $drivers->nextPageUrl() ,
            'previousPageUrl' => $drivers->previousPageUrl()
        ]);
    }



  /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.createdriver');
    }


    /**
     * Add driver
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

        if(!array_key_exists('image' , $input))
        {
            $input['image'] = null;
        }
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
        return redirect()->route('drivertable');
        return $this->sendResponse($data, 'Driver registed successfully');
    }

    public function makeOrderDelivering($id)
    {
        if(! Order::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $order = Order::find($id);

        if($order->driver_id != Auth::id()) {
            return $this->sendError('' , 'You dont have own this order');
        }

        $order->state = orderStatue::DELIVERING;
        $order->save();
        $order->refresh();
        return new SimpleOrderResources($order);
    }

    public function makeOrderSOS($id)
    {
        if(! Order::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $order = Order::find($id);

        if($order->driver_id != Auth::id()) {
            return $this->sendError('' , 'You dont have own this order');
        }

        $order->state = orderStatue::SOS;
        $order->save();
        $order->refresh();
        return new SimpleOrderResources($order);
    }

    public function GetOrderDelivering($id)
    {


    }


    /**
     * Show the specified driver.
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

        return view('dashboard.editdriver')->with('driver',$driver);

    }

    /**
     * Update the specified Driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request  $request,$id )
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

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();

        if(!array_key_exists('image' , $input))
        {
            $input['image'] = null;
        }

        $driver->name = $input['name'];
        $driver->password = $input['password'] = Hash::make($request['password']);
        $driver->phone = $input['phone'];
        $driver->location = $input['location'];
        $driver->image = $input['image'];
        $driver->gender = $input['gender'];

        $driver->update();

        return redirect()->route('drivertable');

        return $this->sendResponse('', 'Driver updated successfully');


    }

    /**
     * Delete the specified driver.
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
        return redirect()->route('drivertable');
        return $this->sendResponse('', 'Driver deleted successfully');

    }
}
