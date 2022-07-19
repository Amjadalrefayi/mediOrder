<?php

namespace App\Http\Controllers;

use App\Models\Supporter;
use Illuminate\Http\Request;
use App\Http\Resources\SimpleSupporterResources;
use App\Http\Resources\SupporterResources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
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
        $supporters = Supporter::latest()->paginate(5);
        return $this->sendResponse(SimpleSupporterResources::collection($supporters),[
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
        return $this->sendResponse($data, 'Supporter registed successfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supporter  $supporter
     * @return \Illuminate\Http\Response
     */
    public function update($id , Request  $request)
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

        $supporter->name = $request->name;
        $supporter->email = $request->email;
        $supporter->password = $request['password'] = Hash::make($request['password']);
        $supporter->phone = $request->phone;
        $supporter->gender = $request->gender;
        $supporter->location = $request->location;
        $supporter->image = $request->image;
        $supporter->update();
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
        return $this->sendResponse('', 'Supporter deleted successfully');

    }
}
