<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Resources\SimpleAdminResources;
use App\Http\Resources\AdminResources;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AdminController extends BaseController
{
    protected AuthController $AuthCon;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::latest()->paginate(5);
        return $this->sendResponse(SimpleAdminResources::collection($admins),[
            'nextPageUrl' =>  $admins->nextPageUrl() ,
            'previousPageUrl' => $admins->previousPageUrl()
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

        $input['password'] = Hash::make($input['password']);

        $admin = Admin::create([
            'name' =>  $input['name'],
            'email' =>  $input['email'],
            'password' =>  $input['password'],
            'phone'=> $input['phone'],
            'gender'=>$input['gender'],
            'location'=> $input['location'],
            'image' => $input['image'],
            'state' => true
        ]);

        $admin->remember_token = $this->AuthCon->token($admin);
        $admin->save();

        $data['id']=$admin['id'];
        $data['Token']=$admin['remember_token'];
        $data['name'] = $admin->name;
        $data['email'] = $admin->email;
        return $this->sendResponse($data, 'Admin registed successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(! Admin::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $admin = Admin::find($id);
        return $this->sendResponse(new AdminResources($admin), 'Admin show successfully');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update($id , Request  $request)
    {
        if(! Admin::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $admin = Admin::find($id);
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

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = $request['password'] = Hash::make($request['password']);
        $admin->phone = $request->phone;
        $admin->gender = $request->gender;
        $admin->location = $request->location;
        $admin->image = $request->image;
        $admin->update();
        return $this->sendResponse('', 'Admin updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(! Admin::find($id)) {
            return $this->sendError('' , 'Not Found');
        }
        $admin = Admin::find($id);
        $admin->delete();
        return $this->sendResponse('', 'Admin deleted successfully');

    }
}
