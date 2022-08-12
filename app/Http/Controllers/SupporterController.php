<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Resources\SimpleSupporterResources;
use App\Http\Resources\SupporterResources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupporterController extends BaseController
{
    protected AuthController $AuthCon;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
<<<<<<< HEAD
    //     $user = User::find(Auth::id())->first();
    //     if($user->type === 'App\Models\Admin')
    //  {
||||||| f877e64
        $user = User::find(Auth::id())->first();
        if($user->type === 'App\Models\Admin')
     {
=======
>>>>>>> bee7948148b9f24f035a798a8ae68c314f6d9a14
        $supporters = Supporter::latest()->paginate(5);
<<<<<<< HEAD


         return view('dashboard.supportertable')->with('supporters',$supporters);

     //}
        $supporters = Supporter::latest()->paginate(5);
        return $this->sendResponse(SimpleSupporterResources::collection($supporters),[
            'nextPageUrl' =>  $supporters->nextPageUrl() ,
            'previousPageUrl' => $supporters->previousPageUrl()
        ]);
||||||| f877e64


         return view('dashboard.supportertable')->with('supporters',$supporters);

     }
        $supporters = Supporter::latest()->paginate(5);
        return $this->sendResponse(SimpleSupporterResources::collection($supporters),[
            'nextPageUrl' =>  $supporters->nextPageUrl() ,
            'previousPageUrl' => $supporters->previousPageUrl()
        ]);
=======
        return view('dashboard.supportertable')->with('supporters',$supporters);
>>>>>>> bee7948148b9f24f035a798a8ae68c314f6d9a14
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.createsupporter');
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
            'location'=> 'min:3|nullable',
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
        $input['password'] = Hash::make($input['password']);
        $supporter = Supporter::create([
            'name' =>  $input['name'],
            'email' =>  $input['email'],
            'password' =>  $input['password'],
            'phone'=> $input['phone'],
            'gender'=>$input['gender'],
            'location'=> $input['location'],
            'image' => $input['image'],
            'state' => true
        ]);

        $supporter->remember_token = $this->AuthCon->token($supporter);
        $supporter->update();

        $data['id']=$supporter['id'];
        $data['Token']=$supporter['remember_token'];
        $data['name'] = $supporter->name;
        $data['email'] = $supporter->email;
        return redirect()->route('supportertable');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(! Supporter::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $supporter = Supporter::find($id);
        return $this->sendResponse(new SupporterResources($supporter), 'Supporter show successfully');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function edit(Supporter $supporter)
    {
        return view('dashboard.editsupporter')->with('supporter',$supporter);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function update(Request  $request , $id )
    {
        if(! Supporter::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $supporter = Supporter::find($id);
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

        $supporter->name = $request->name;
        $supporter->email = $request->email;
        $supporter->password = $request['password'] = Hash::make($request['password']);
        $supporter->phone = $request->phone;
        $supporter->gender = $request->gender;
        $supporter->location = $request->location;
        $supporter->image = $request->image;
        $supporter->update();
        return redirect()->route('supportertable');
        return $this->sendResponse('', 'Supporter updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(! Supporter::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $supporter = Supporter::find($id);
        $supporter->delete();
        return redirect()->route('supportertable');
        return $this->sendResponse('', 'Supporter deleted successfully');

    }
}
