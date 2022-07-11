<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\Driver;
use App\Models\Address ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController ;
use App\Http\Resources\AddressResources;
use Symfony\Component\Console\Input\Input;

/**
 * @group Address
 *
 * APIs to manage the address
 */
class AddressController extends BaseController
{

     /**
     * Get all addresses
     *
     */
    public function index()
    {
        $addresses= Address::paginate(5);
        return $this->sendResponse(AddressResources::collection($addresses), [
            'current_page' =>  $addresses->currentPage(),
            'nextPageUrl' =>  $addresses->nextPageUrl() ,
            'previousPageUrl' =>  $addresses->previousPageUrl(),
        ] );

    }

    public function useraddress()
    {
        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        $addresses=Customer::find(Auth::id())->addresses()->paginate(5);


         if($user->type === 'App\Models\Pharmacy')
         $addresses=Pharmacy::find(Auth::id())->addresses()->paginate(5);

         if($user->type === 'App\Models\Driver')
         $addresses=Driver::find(Auth::id())->addresses()->paginate(5);


         return $this->sendResponse(AddressResources::collection($addresses), [
             'current_page' =>  $addresses->currentPage(),
             'nextPageUrl' =>  $addresses->nextPageUrl(),
             'previousPageUrl' =>  $addresses->previousPageUrl(),
         ] );

    }

    /**
     * add address
     *
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required|max:255',
            'description'=>'required|max:255',
            'latitude' =>'required|numeric|between:-90,90',
            'longitude'=>'required|numeric|between:-180,180'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();


        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        $input['customer_id'] = Auth::id();

        if($user->type === 'App\Models\Pharmacy')
        $input['pharmacy_id'] = Auth::id();


        if($user->type === 'App\Models\Driver')
        $input['driver_id'] = Auth::id();

        $Address = Address::create($input);

        return $this->sendResponse(new AddressResources($Address), 'Address Store successfully');
    }

    /**
     * Show address
     *
     */
    public function show($id)
    {
        $Address = Address::find($id);


        if (is_null($Address)) {
            return $this->sendError('Address Not Found', 404);
        }
        return $this->sendResponse(new AddressResources($Address), 'Address retireved Successfully');
    }


    /**
     * Update address
     *
     */
    public function update(Request $request, $id)
    {
        $Address = Address::find($id);

        if (is_null($Address)) {
            return $this->sendError('Address Not Found', 404);
        }




        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
       {
        if( Auth::id() != $Address->customer_id)
        return $this->sendError('Not Valid to update', 'This Address for another user');
       }

        if($user->type === 'App\Models\Pharmacy')
        {
            if(Auth::id() != $Address->pharmacy_id)
            return $this->sendError('Not Valid to update', 'This Address for another user');
        }
        if($user->type === 'App\Models\Driver')
        {
            if(Auth::id() != $Address-> driver_id)
            return $this->sendError('Not Valid to update', 'This Address for another user');
        }



        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'longitude' => 'required',
            'latitude' => 'required'

        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }


        $Address->name = $request->name;
        $Address->description = $request->description;
        $Address->longitude = $request->longitude;
        $Address->latitude = $request->latitude;
        $Address->update();

        return $this->sendResponse(new AddressResources($Address), 'Address Updated successfully');
    }

     /**
     * Delete address
     *
     */
    public function destroy($id)
    {

        $Address = Address::find($id);
        if (is_null($Address)) {
            return $this->sendError('Address Not Found', 404);
        }





        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        {

            if( Auth::id() != $Address->customer_id)
            return $this->sendError('Not Valid to delete', 'This Address for another user');
        }

        if($user->type === 'App\Models\Pharmacy')
        {
            if(Auth::id() != $Address->pharmacy_id)
            return $this->sendError('Not Valid to delete', 'This Address for another user');
        }
        if($user->type === 'App\Models\Driver')
        {

            if(Auth::id() != $Address-> driver_id)
            return $this->sendError('Not Valid to delete', 'This Address for another user');
        }



        $Address->delete();

        return $this->sendResponse('', 'Address Deleted successfully');

    }
}
