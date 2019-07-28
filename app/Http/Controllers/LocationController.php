<?php

namespace App\Http\Controllers;

use App\Enumeration\Bill;
use App\Enumeration\Primary;
use App\Model\Customer;
use App\Model\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class LocationController extends Controller
{
    public function addStore(Request $request,Customer $customer)
    {
        $rules =[
            'name'=>'required',
            'address'=>'required',
            'is_primary'=>'required',
            ];
        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }
        if($request->is_primary==true)
            $request['is_primary']==Primary::$IS_PRIMARY;
        else
            $request['is_primary']=Primary::$NOT_PRIMARY;

            // creating location
            $location=Location::create($request->all());

           // Updating the customer's primary location if the user requested the newly created loacation to be primary
            if($request->is_primary==Primary::$IS_PRIMARY)
            {
                $oldPrimaryLocation=$customer->locations()->where('is_primary',Primary::$IS_PRIMARY)->first();

                if($oldPrimaryLocation->pivot->is_bill==Bill::$IS_BILL)
                {
                    $customer->locations()->updateExistingPivot($oldPrimaryLocation->id,['is_primary'=>  Primary::$NOT_PRIMARY , 'is_bill'=>  Bill::$NOT_BILL]);
                    $customer->locations()->attach($location->id, ['is_primary' => Primary::$IS_PRIMARY, 'is_bill' => Bill::$IS_BILL]);
                }

                else
                {
                    $customer->locations()->updateExistingPivot($oldPrimaryLocation->id,['is_primary'=>Primary::$NOT_PRIMARY,'is_bill'=>Bill::$NOT_BILL]);
                    $customer->locations()->attach($location->id, ['is_primary' => Primary::$IS_PRIMARY, 'is_bill' => Bill::$NOT_BILL]);
                }

            }
            else
            {
                $customer->locations()->attach($location->id, ['is_primary' => Primary::$NOT_PRIMARY , 'is_bill' => Bill::$NOT_BILL]);
            }


            return response()->json($location);

    }


    public function editStore(Request $request,Location $location)
    {
        $rules =[
            'name'=>'required',
            'address'=>'required',
            'is_primary'=>'required',
        ];
        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        if($request->is_primary)
        {
            $request['is_primary'] = 1;

            $customerId = DB::table('customer_location')->select('customer_id')->where('location_id', $location->id)->first();

            /*$customer_location = DB::table('customer_location')->where('customer_id', $customerId->customer_id)
                ->where('is_primary', 1)->first();
            $customer_location->update(['is_primary'=>0]);*/
            $customer = Customer::find($customerId->customer_id);
            $customer_location = $customer->locations()->where('is_primary', 1)->first();
            $customer->locations()->updateExistingPivot($customer_location->id,['is_primary'=> 0]);
            //return response()->json($customer_location);



            $new_primary_location = DB::table('customer_location')->where('location_id', $location->id)->first();
            $customer->locations()->updateExistingPivot($new_primary_location->location_id,['is_primary'=> 1]);
        }

            $location->update($request->all());

            return response()->json($location);
    }


    public function viewAll(Request $request)
    {
        $id = $request->input('id');
        $customer = Customer::find($id);
        $locations=$customer->locations;
        return response()->json($locations);
    }
    public function viewCustomerLocation($id)
    {
        $customer=Customer::find($id);
        $location=$customer->locations()->where('is_primary',Primary::$IS_PRIMARY)->first();
        return response()->json($location);

    }
    public function viewAllLocation()
    {
        //$locations = DB::table('customer_location')->where('is_primary',Primary::$IS_PRIMARY)->get();
        $locations = DB::table('customer_location')->get();
        $locationDetails=array();
        foreach ($locations as $location)
        {
            $aLocation=Location::find($location->location_id);
            $aCustomer=Customer::find($location->customer_id);
            if($aCustomer)
            {
                $locationDetail=array(
                    'location'=>$aLocation,
                    'customer'=>$aCustomer
                );
                array_push($locationDetails,$locationDetail);
            }
        }
        return response()->json($locationDetails);
    }

    public function viewLocation(Location $location)
    {
        $customer_location = DB::table('customer_location')->where('location_id', $location->id)->first();
        $isPrimary = $customer_location->is_primary;

        $locationDetails = array(
            "location"=>$location,
            "is_primary"=>$isPrimary,
        );
        return response()->json($locationDetails);
    }

    public function getLocationForCustomer($id)
    {
        $customer = Customer::find($id);
        $locations = $customer->locations;

        return response()->json($locations);
    }
}
