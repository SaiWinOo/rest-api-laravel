<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'country' => "nullable|string",
            'state' => "nullable|string",
            'street' => "nullable|nullable",
        ]);
        $hasAddress = CustomerAddress::where('user_id', Auth::id())->first();
        $address = null;
        if(!$hasAddress){
            $address = new CustomerAddress();
        }else{
            $address = $hasAddress;
        }
        if($request->has('country')){
            $address->country = $request->country;
        }
        if($request->has('state')){
            $address->state = $request->state;
        }
        if($request->has('street')){
            $address->street = $request->street;
        }
        $address->user_id = Auth::id();
        $address->save();
        return response()->json([
            'success' => true,
            'message' => 'You address is updated!',
            'address' => $address,
        ]);
//        $request->validate([
//            'country' => "required|string",
//            'state' => "required|string",
//            'street' => "required|nullable",
//        ]);
//        $address = CustomerAddress::create([
//            'country' => $request->country,
//            'state' => $request->state,
//            'street' => $request->street,
//            'user_id' => Auth::id(),
//        ]);
//        return response()->json([
//            'success' => true,
//            'message' => 'You address is updated!',
//            'address' => $address,
//        ]);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'country' => "required|string",
            'state' => "required|string",
            'street' => "required|nullable",
        ]);
        $hasAddress = CustomerAddress::where('user_id', Auth::id())->first();
        $address = null;
        if(!$hasAddress){
            $address = new CustomerAddress();
        }else{
            $address = $hasAddress;
        }
        if($request->has('country')){
            $address->country = $request->country;
        }
        if($request->has('state')){
            $address->state = $request->state;
        }
        if($request->has('street')){
            $address->street = $request->street;
        }
        $address->save();
        return response()->json([
            'success' => true,
            'message' => 'You address is updated!',
            'address' => $address,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
