<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\View\View;
use App\Http\Controllers\BaseController as BaseController;

/**
 * @group User Management
 *
 * APIs to manage the user
 */
class AuthController extends BaseController
{

    /**
     * Generate token for User
     *
     */

    public function token($user){
        $token = $user->createToken(str()->random(40))->plainTextToken;
        return $token;
    }

    /**
     * User Rigester
     *
     */

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone'=> 'required',
            'gender'=>'in:male,female',
            'location'=> 'required',
            'image',
            'state',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        if(!$request->image == null){
            $image = $request->image;
            $saveImage = time() . $image;
            $image->move('uploads/profile', $saveImage);

        }


        $input = $request->all();
        $input['image'] = 'uploads/profile' . $saveImage;
        $input['password'] = Hash::make($input['password']);
        $user = Customer::create([
            'name' =>  $input['name'],
            'email' =>  $input['email'],
            'password' =>  $input['password'],
            'phone'=> $input['phone'],
            'gender'=>$input['gender'],
            'location'=> $input['location'],
            'image' => $input['image'],
            'state' => $input['state'],
        ]);

        $data['token'] = $this->token($user);
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        return $this->sendResponse($data, 'User registed successfully');
    }


    /**
     * User login
     *
     */
    public function login(Request $request){

       // return view('welcome')->with('data', $request->email);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendError('Unauthorized', 401);
        }
        $user = User::find(Auth::id())->first();
        $data['id'] = $user->id;
        $data['token'] = $this->token($user);
        $data['name'] = Auth::user()->name;
        $data['email'] = Auth::user()->email;

        if($user->type === 'App\Models\Admin')
           return view('admin')->with('data', $request->email);
        //   return $this->sendResponse($data,' Admin logedIn successfully');

           if($user->type === 'App\Models\Customer')
            return $this->sendResponse($data,' User logedIn successfully');

    }

    /**
     * Refresh user token
     *
     * Must be authenticated
     */
    public function refresh(){
        User::find(Auth::id())->tokens()->delete();
        $data['token'] = $this->token(Auth::user());
        $data['name'] = Auth::user()->name;
        $data['email'] = Auth::user()->email;
        return $this->sendResponse($data, 'Token refreshed successfully');
    }

    /**
     * User logout
     *
     * Must be authenticated
     */
    public function logout(){
        User::find(Auth::id())->tokens()->delete();
        return $this->sendResponse('', 'log out succssefully');
    }
}
