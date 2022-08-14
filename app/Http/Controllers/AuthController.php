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
            return $this->sendError('', $validator->errors());
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->sendError('', 'Unauthorized');
        }
        $user = User::where('email',$request->email)->first();
        $data['id'] = $user->id;
        $data['token'] = $this->token($user);
        $data['name'] = $user->name;
        $data['email'] = $user->email;

        if($user->type === 'App\Models\Admin')
           return redirect()->route('customertable');
        //   return $this->sendResponse($data,' Admin logedIn successfully');
        if($user->type === 'App\Models\Pharmacy')
        return redirect()->route('producttable');

           if($user->type === 'App\Models\Customer' or $user->type === 'App\Models\Driver')
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
