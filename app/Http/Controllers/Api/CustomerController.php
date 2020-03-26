<?php

namespace App\Http\Controllers\Api;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
   
        return new CustomerCollection(Customer::orderBy('id','DESC')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'total' => 'required',
        ]);

        $cus = new Customer();
        $cus->name = $request->name;
        $cus->email = $request->email;
        $cus->address = $request->address;
        $cus->phone = $request->phone;
        $cus->total = $request->total;
        $cus->save();

        return new CustomerResource($cus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new CustomerResource(Customer::findorfail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'total' => 'required',
        ]);

        $cus = Customer::findorfail($id);
        $cus->name = $request->name;
        $cus->email = $request->email;
        $cus->address = $request->address;
        $cus->phone = $request->phone;
        $cus->total = $request->total;
        $cus->save();

        return new CustomerResource($cus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus = Customer::findorfail($id);
        $cus->delete();
        return new CustomerResource($cus);
    }

    public function searchCustomer($field,$search){

        $search_customers = Customer::where($field,'LIKE','%'.$search.'%')->latest()->paginate(10);
        return new CustomerCollection($search_customers);

    }
}
