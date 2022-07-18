<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Phamracy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Controllers\AuthController;
use App\Http\Resources\CustomerResources;
use App\Http\Resources\SimpleCustomerResources;

/**
 * @group Customer Management
 *
 * APIs to manage the customer
 */
class CustomerController extends BaseController
{

    protected AuthController $AuthCon;

    /**
     * Customer Register
     *
     */
    public function register(Request $request){

        $this->AuthCon  = new AuthController();
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone'=> 'required|min:13',
            'gender'=>'in:male,female|nullable',
            'location'=> 'required',
            'image' => 'mimes:jpeg,jpg,png|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('',$validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if(array_key_exists('image',$input)){
            $image = $request->image;
            if($image){
                $saveImage = time() . $image->getClientOriginalName();
                $image->move('uploads/profile', $saveImage);
                $input['image'] = 'uploads/profile/' . $saveImage;
            }
        }

        $user = Customer::create([
            'name' =>  $input['name'],
            'email' =>  $input['email'],
            'password' =>  $input['password'],
            'phone'=> $input['phone'],
            'gender'=>$input['gender'],
            'location'=> $input['location'],
            'image' => $input['image'],
            'state' => true
        ]);

        $user->remember_token = $this->AuthCon->token($user);
        $user->update();

        $data['id']=$user['id'];
        $data['Token']=$user['remember_token'];
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        return $this->sendResponse($data, 'User registed successfully');
    }


    /**
     * Get all Customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $customers = Customer::latest()->paginate(5);
        return $this->sendResponse(CustomerResources::collection($customers),
        'All Customers sent');
    }


    /**
     * Show specified customer.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id){

        if(! Customer::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $customer = Customer::find($id);
        return $this->sendResponse(new SimpleCustomerResources($customer), 'Customer show successfully');
    }


    /**
     * Update Profile
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update($id , Request  $request){

        if(! Customer::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $customer = Customer::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'min:3|nullable',
            'password' => 'min:8|nullable',
            'phone'=> 'min:13|nullable',
            'gender'=>'in:male,female|nullable',
            'location'=> 'nullable',
            'image' => 'mimes:jpeg,jpg,png | nullable',
        ]);

        $customer->name = $request->name;
        $customer->password = $request['password'] = Hash::make($request['password']);
        $customer->phone = $request->phone;
        $customer->gender = $request->gender;
        $customer->location = $request->location;
        $customer->image = $request->image;
        $customer->update();
        return $this->sendResponse('', 'Customer updated successfully');

    }

    /**
     * Delete specific Customer
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        if(! Customer::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $customer = Customer::find($id);
        $customer->delete();
        return $this->sendResponse('', 'Customer deleted successfully');
    }


}
