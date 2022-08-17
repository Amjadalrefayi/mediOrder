<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Pharmacy;
use App\Models\Driver;
use App\Models\Address ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController ;
use App\Models\Complaint;
use App\Models\Customer;
use App\Http\Resources\ComplaintResources;

/**
 * @group Complaint
 *
 * APIs to manage the Complaint
 */
class ComplaintController extends BaseController
{
    /**
     * Display a listing of the Complaint.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $complaints= Complaint::paginate(10);
        return view('supportdashboard.complaintstable')->with('complaints',$complaints);

        // return $this->sendResponse(ComplaintResources::collection($complaints), [
        //     'current_page' =>  $complaints->currentPage(),
        //     'nextPageUrl' =>  $complaints->nextPageUrl() ,
        //     'previousPageUrl' =>  $complaints->previousPageUrl(),
        // ] );
    }

    /**
     * Display a listing of the trashed resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function complaintstrashed()
    {
        $complaints=Complaint::onlyTrashed()->paginate(15);
        return $this->sendResponse(ComplaintResources::collection($complaints), [
            'current_page' =>  $complaints->currentPage(),
            'nextPageUrl' =>  $complaints->nextPageUrl() ,
            'previousPageUrl' =>  $complaints->previousPageUrl(),
        ] );

    }
    public function usercomplaints()
    {
        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        $complaints=Customer::find(Auth::id())->complaints()->paginate(10);


         if($user->type === 'App\Models\Pharmacy')
         $complaints=Pharmacy::find(Auth::id())->complaints()->paginate(10);

         if($user->type === 'App\Models\Driver')
         $complaints=Driver::find(Auth::id())->complaints()->paginate(10);


         return $this->sendResponse(ComplaintResources::collection($complaints), [
             'current_page' =>  $complaints->currentPage(),
             'nextPageUrl' =>  $complaints->nextPageUrl(),
             'previousPageUrl' =>  $complaints->previousPageUrl(),
         ] );

    }





    public function usertrashedcomplaints()
    {
        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        $trashed_complaints=Customer::find(Auth::id())->complaints()->onlyTrashed()->paginate(5);


         if($user->type === 'App\Models\Pharmacy')
         $trashed_complaints=Pharmacy::find(Auth::id())->complaints()->onlyTrashed()->paginate(5);

         if($user->type === 'App\Models\Driver')
         $trashed_complaints=Driver::find(Auth::id())->complaints()->onlyTrashed()->paginate(5);


         return $this->sendResponse(ComplaintResources::collection($trashed_complaints), [
             'current_page' =>  $trashed_complaints->currentPage(),
             'nextPageUrl' =>  $trashed_complaints->nextPageUrl(),
             'previousPageUrl' =>  $trashed_complaints->previousPageUrl(),
         ] );

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pharmacydashboard.createcomplaint');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'note'=>'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }

        $input = $request->all();
        $user = User::find(Auth::id());

        if($user->type === 'App\Models\Customer')
            $input['customer_id'] = Auth::id();

        if($user->type === 'App\Models\Pharmacy'){
            $input['pharmacy_id'] = Auth::id();
            $complaint = Complaint::create($input);
            return redirect()->route('producttable');
        }

        if($user->type === 'App\Models\Driver')
            $input['driver_id'] = Auth::id();

        $complaint = Complaint::create($input);
        return $this->sendResponse(new ComplaintResources($complaint), 'complaint Store successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $complaint = Complaint::find($id);


        if (is_null($complaint)) {
            return $this->sendError('complaint Not Found', 404);
        }
        return $this->sendResponse(new ComplaintResources($complaint), 'complaint retireved Successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function edit(Complaint $complaint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {



        $complaint = Complaint::find($id);

        if (is_null($complaint)) {
            return $this->sendError('complaint Not Found', 404);
        }




        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
       {
        if( Auth::id() != $complaint->customer_id)
        return $this->sendError('Not Valid to update', 'This complaint for another user');
       }

        if($user->type === 'App\Models\Pharmacy')
        {
            if(Auth::id() != $complaint->pharmacy_id)
            return $this->sendError('Not Valid to update', 'This complaint for another user');
        }
        if($user->type === 'App\Models\Driver')
        {
            if(Auth::id() != $complaint-> driver_id)
            return $this->sendError('Not Valid to update', 'This complaint for another user');
        }



        $validator = Validator::make($request->all(), [
            'note' => 'required'


        ]);

        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors());
        }


        $complaint->note = $request->note;
        $complaint->update();

        return $this->sendResponse(new ComplaintResources($complaint), 'complaint Updated successfully');

    }

    /**
     * Remove the specified resource from storage softdelete.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function softdelete( $id)
    {

        //soft delete

        $complaint = Complaint::find($id);
        if (is_null($complaint)) {
            return $this->sendError('Address Not Found', 404);
        }

        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        {

            if( Auth::id() != $complaint->customer_id)
            return $this->sendError('Not Valid to delete', 'This complaint for another user');
        }

        if($user->type === 'App\Models\Pharmacy')
        {
            if(Auth::id() != $complaint->pharmacy_id)
            return $this->sendError('Not Valid to delete', 'This complaint for another user');
        }
        if($user->type === 'App\Models\Driver')
        {

            if(Auth::id() != $complaint-> driver_id)
            return $this->sendError('Not Valid to delete', 'This complaint for another user');
        }




        $complaint->delete();

        return $this->sendResponse('', 'complaint soft_Deleted successfully');


    }

    /**
     * Remove the specified resource from storage harddelete.
     *
     * @param  \App\Models\Complaint  $complaint
     * @return \Illuminate\Http\Response
     */
    public function hdelete( $id)
    {
        $complaint=Complaint::withTrashed()->where('id',$id)->first();
        if (is_null($complaint)) {
            return $this->sendError('complaintTrashed Not Found', 404);
        }



        $user = User::find(Auth::id())->first();

        if($user->type === 'App\Models\Customer')
        {

            if( Auth::id() != $complaint->customer_id)
            return $this->sendError('Not Valid to delete', 'This complaintTrash for another user');
        }

        if($user->type === 'App\Models\Pharmacy')
        {
            if(Auth::id() != $complaint->pharmacy_id)
            return $this->sendError('Not Valid to delete', 'This complaintTrash for another user');
        }
        if($user->type === 'App\Models\Driver')
        {

            if(Auth::id() != $complaint-> driver_id)
            return $this->sendError('Not Valid to delete', 'This complaintTrash for another user');
        }

        $complaint->forceDelete();
        return $this->sendResponse('', 'complaint hard_deleted successfully');

    }




}
