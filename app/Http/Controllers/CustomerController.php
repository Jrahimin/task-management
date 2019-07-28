<?php
namespace App\Http\Controllers;

use App\Enumeration\Bill;
use App\Enumeration\BillTo;
use App\Enumeration\CustomerStatus;
use App\Enumeration\Primary;
use App\Model\Contact;
use App\Model\Customer;
use App\Model\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class CustomerController extends Controller
{
    public function addStore(Request $request)
    {
        $request['status']=1;
        //return response()->json($request->all());

        $rules =[
            'name'=>'required',
            'status'=>'required',
            'account_manager_id'=>'nullable|integer',
            'service_manager_id'=>'nullable|integer',
            'location_name'=>'required',
            'address'=>'required',
            'bill_to'=>'required',
            'contact_name'=>'required',
            'email'=>'required|email',
            ];
        if($request->bill_to==BillTo::$OTHER_LOCATION)
        {

            $rules['other_location_name']='required';
            $rules['other_address']='required';
            $rules['other_city']='required';
            $rules['other_state']='required';
            $rules['other_zip']='required';
            $rules['other_country']='required';
            $rules['other_zone']='required';
        }
        elseif ($request->bill_to==BillTo::$DIFFERENT_CUSTOMER) {
            $rules['customer_location_id'] = 'required';
        }

          $validation=Validator::make($request->all(),$rules);

           if ($validation->fails()) {
                return response()->json(["success" => false,
                   "message" => $validation->errors()->all()], 200);
            }


       DB::beginTransaction();
        //creating customer
        $customer=Customer::create($request->all());

        //creating location
         $locationName=$request->location_name;
         $location=new Location();
         $location=$location->createLocation($request,$locationName);
        $bill_to=(int)$request->bill_to;   //bill location indicator

         // if the customer's primary location is the bill location
         if( $bill_to==BillTo::$PRIMARY_LOCATION )
         {
             $customer->locations()->attach($location->id,['is_primary'=>Primary::$IS_PRIMARY,'is_bill'=>Bill::$IS_BILL]);
         }

         // if another address entered manually is customer's bill location
         elseif ($bill_to==BillTo::$OTHER_LOCATION)
         {
             $customer->locations()->attach($location->id,['is_primary'=>Primary::$IS_PRIMARY,'is_bill'=>Bill::$NOT_BILL]);
             $other_location=Location::create(['name'=>$request->other_location_name,
                 'address'=>$request->other_address,
                 'city'=>$request->other_city,
                 'state'=>$request->other_state,
                 'zip'=>$request->other_zip,
                 'country'=>$request->other_country,
                 'zone'=>$request->other_zone]);
             $customer->locations()->attach($other_location->id,['is_primary'=>Primary::$NOT_PRIMARY,'is_bill'=>Bill::$IS_BILL]);
         }

         //if another customer's location is this customer's bill location
         elseif($bill_to==BillTo::$DIFFERENT_CUSTOMER)
         {
             $customer->locations()->attach($location->id,['is_primary'=>Primary::$IS_PRIMARY,'is_bill'=>Bill::$NOT_BILL]);
             $customer->locations()->attach($request->customer_location_id,['is_primary'=>Primary::$NOT_PRIMARY,'is_bill'=>Bill::$IS_BILL]);

         }

        //creating contact
        $contact=[
            'name'=>$request->contact_name,
            'job_title'=>$request->job_title,
            'email'=>$request->email,
            'mobile_phone'=>$request->mobile_phone,
            'home_phone'=>$request->home_phone,
            'fax'=>$request->fax,
            'customer_id'=>$customer->id,
            'is_primary'=>1
        ];
        $contact=Contact::create($contact);

        if( $bill_to==BillTo::$OTHER_LOCATION )
        {
            if(!empty($customer)&&!empty($contact)&&!empty($location)&& !empty($other_location))
            {
                DB::commit();
            }
        }

        else
        {
            if(!empty($customer)&&!empty($contact)&&!empty($location))
            {
                DB::commit();
            }
        }
       return response()->json($customer);

    }

    public function editStore(Request $request,Customer $customer)
    {
        $rules =[
            'name'=>'required',
            'status'=>'required',
            'account_manager_id'=>'nullable|integer',
            'service_manager_id'=>'nullable|integer',
            'location_name'=>'required',
            'address'=>'required',

            'contact_name'=>'required',
            'email'=>'required|email',
            ];
        if($request->bill_to==BillTo::$OTHER_LOCATION)
        {
            $rules['other_location_name']='required';
            $rules['other_address']='required';
            $rules['other_city']='required';
            $rules['other_state']='required';
            $rules['other_zip']='required';
            $rules['other_country']='required';
            $rules['other_zone']='required';
        }
        elseif ($request->bill_to==BillTo::$DIFFERENT_CUSTOMER)
        {
            $rules['customer_location_id']='required';
        }
        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        DB::beginTransaction();
        try{
            // updating customer
            $customer->name=$request->name;
            $customer->status=$request->status;

            //updating contact
            $contact=Contact::where('customer_id',$customer->id)->where('is_primary',Primary::$IS_PRIMARY)->first();
            $contact->name=$request->contact_name;
            $contact->job_title=$request->job_title;
            $contact->email=$request->email;
            $contact->mobile_phone=$request->mobile_phone;
            $contact->home_phone=$request->home_phone;
            $contact->fax=$request->fax;
            $contact->customer_id=$customer->id;
            $contact->save();

            //updating location
            $customer_location=$customer->locations()->where('is_primary',Primary::$IS_PRIMARY)->first();
            $customer_location->name=$request->location_name;
            $customer_location->address=$request->address;
            $customer_location->city=$request->city;
            $customer_location->state=$request->state;
            $customer_location->zip=$request->zip;
            $customer_location->country=$request->country;
            $customer_location->zone=$request->zone;
            $bill_to=$request->bill_to;   //bill location indicator

            // if the customer's primary location is the bill location
            if($bill_to==BillTo::$PRIMARY_LOCATION)
            {
                $customer_location->save();
            }

            // if another address entered manually is customer's bill location
            elseif ($bill_to==BillTo::$OTHER_LOCATION)
            {
                $customer_location->save();
                //$customer->locations()->detach($customer_location->id);
                // $customer->locations()->attach($customer_location->id,['is_primary'=>1,'is_bill'=>0]);
                $customer->locations()->updateExistingPivot($customer_location->id,['is_primary'=>Primary::$IS_PRIMARY,'is_bill'=>Bill::$NOT_BILL]);
                $other_location=Location::create(['name'=>$request->other_location_name,
                    'address'=>$request->other_address,
                    'city'=>$request->other_city,
                    'state'=>$request->other_state,
                    'zip'=>$request->other_zip,
                    'country'=>$request->other_country,
                    'zone'=>$request->other_zone]);
                $customer->locations()->attach($other_location->id,['is_primary'=>Primary::$NOT_PRIMARY,'is_bill'=>Bill::$IS_BILL]);
            }

            //if another customer's location is this customer's bill location
            elseif($bill_to=BillTo::$DIFFERENT_CUSTOMER)
            {
                $customer_location->save();
                //$customer->locations()->detach($customer_location->id);
                //$customer->locations()->attach($customer_location->id,['is_primary'=>1,'is_bill'=>0]);
                $customer->locations()->updateExistingPivot($customer_location->id,['is_primary'=>Primary::$IS_PRIMARY,'is_bill'=>Bill::$NOT_BILL]);
                $customer->locations()->attach($request->customer_location_id,['is_primary'=>Primary::$NOT_PRIMARY,'is_bill'=>Bill::$IS_BILL]);

            }

            $customer->account_manager_id=$request->account_manager_id;
            $customer->service_manager_id=$request->service_manager_id;
            $customer->save();
            DB::commit();

        }catch (Exception $exception)
        {
            DB::rollBack();

        }

        return response()->json($customer);


    }

    public function delete(Customer $customer)
    {

        $customer->locations()->detach();
        $customer->workOrders()->delete();
        $customer->recurringWorkOrders()->delete();
        $customer->contracts()->delete();
        $customer->contacts()->delete();
        $customer->attachments()->delete();
        $customer->delete();
        return response()->json($customer);
    }

    public function viewAllWithDetails(Request $request)
    {
        $parameters = [];
        if($request->type=='name')
            $parameters[] = array('name', 'like', '%'.$request->text.'%');
        if($request->stat!='')
            $parameters[] = array('status', $request->stat);

        $customers = Customer::where($parameters)->get();

        $contacts = array();
        $customerDetails = array();
        foreach ($customers as $customer)
        {
            foreach ($customer->contacts as $contact) {
                if($contact->is_primary==1) {
                    if($request->type=='email' && $request->text!='')
                    {
                        if($contact->email != $request->text)
                            continue;
                    }

                    if($request->type=='mobile' && $request->text!='')
                    {
                        if($contact->mobile_phone != $request->text)
                            continue;
                    }
                    $location = DB::table('customer_location')->where('customer_id',$customer->id)->where('is_primary',1)->first();
                    $location = Location::find($location->location_id);
                    if($customer->status==CustomerStatus::$ACTIVE)
                        $customerStatus = "Active";
                    else
                        $customerStatus = "Inactive";

                    $customerDetailsInit = array(
                        "customer"=>$customer,
                        "status"=>$customerStatus,
                        "contact"=>$contact,
                        "location"=>$location,
                    );

                    array_push($customerDetails, $customerDetailsInit);
                    break;
                }
            }
        }

        return response()->json($customerDetails);
    }

    public function all()
    {
        return response()->json(Customer::all());
    }

    public function show($id)
    {
        $customer=Customer::find($id);

        return response()->json($customer);
    }

    public function getCustomerOverview($id)
    {
        $customer = Customer::find($id);
        $customerName = $customer->name;

        $location = DB::table('customer_location')->where('customer_id', $id)->where('is_primary',1)->first();
        $location = Location::find($location->location_id);

        $contact = Contact::where('customer_id', $id)->where('is_primary',1)->first();

        $customerDetails = array(
            "location"=>$location,
            "contact"=>$contact,
            "customerName"=>$customerName,
        );

        return response()->json($customerDetails);
    }
}
