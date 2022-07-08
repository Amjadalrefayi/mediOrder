<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\Driver;
use App\Models\Address ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController ;
use App\Http\Resources\AddressResources;
class AddressController extends BaseController
{

    public function index()
    {
        $addresses= Address::paginate(5);
        return $this->sendResponse(AddressResources::collection($addresses), [
            'current_page' =>  $addresses->currentPage(),
            'nextPageUrl' =>  $addresses->nextPageUrl(),
            'previousPageUrl' =>  $addresses->previousPageUrl(),
        ] );

    }



    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'description'=>'required',
            'longitude' =>'required',
            'latitude'=>'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();

        if(Customer::find(Auth::id()))
        $input['customer_id'] = Auth::id();

        if(Driver::find(Auth::id()))
        $input['driver_id'] = Auth::id();

        if(Pharmacy::find(Auth::id()))
        $input['pharmacy_id'] = Auth::id();


        $Address = Address::create($input);

        return $this->sendResponse(new AddressResources($Address), 'Address Store successfully');
    }


    public function show($id)
    {
        $Address = Address::find($id);


        if (is_null($Address)) {
            return $this->sendError('Address Not Found', 404);
        }
        return $this->sendResponse(new AddressResources($Address), 'Address retireved Successfully');
    }



    public function update(Request $request, $id)
    {
        $Address = Address::find($id);

        if (is_null($Address)) {
            return $this->sendError('Address Not Found', 404);
        }


        if(Customer::find(Auth::id()))
      {
            if( Auth::id() != $Address->customer_id)
        return $this->sendError('Not Valid to update', 'This Address for another user');
      }

        if(Driver::find(Auth::id()))
      {
            if(Auth::id() != $Address-> driver_id)
        return $this->sendError('Not Valid to update', 'This Address for another user');
      }

        if(Pharmacy::find(Auth::id()))
      {
            if(Auth::id() != $Address->pharmacy_id)
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

    public function destroy($id)
    {


        $Address = Address::find($id);
        if (is_null($Address)) {
            return $this->sendError('Address Not Found', 404);
        }


        if(Customer::find(Auth::id()))
        {
              if( Auth::id() != $Address->customer_id)
          return $this->sendError('Not Valid to delete', 'This Address for another user');
        }

          if(Driver::find(Auth::id()))
        {
              if(Auth::id() != $Address-> driver_id)
          return $this->sendError('Not Valid to delete', 'This Address for another user');
        }

          if(Pharmacy::find(Auth::id()))
        {
              if(Auth::id() != $Address->pharmacy_id)
          return $this->sendError('Not Valid to delete', 'This Address for another user');
        }


        $Address->delete();

        return $this->sendResponse('', 'Address Deleted successfully');

    }
}
