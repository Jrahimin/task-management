<?php

namespace App\Http\Controllers;

use App\Enumeration\Primary;
use App\Model\Contact;
use App\Model\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    public function addStore(Request $request,Customer $customer)
    {
        $rules =[
            'name'=>'required',
            'email'=>'required|email',
            'is_primary'=>'required'
        ];
        if($request->is_primary==true)
            $request['is_primary']==Primary::$IS_PRIMARY;
        else
            $request['is_primary']=Primary::$NOT_PRIMARY;

        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }
        DB::beginTransaction();
            if($request->is_primary==Primary::$IS_PRIMARY)
            {
                $oldPrimaryContact=$customer->contacts()
                    ->where('is_primary',Primary::$IS_PRIMARY)
                    ->update(['is_primary'=>Primary::$NOT_PRIMARY]);

            }
            $request['customer_id']=$customer->id;
            $contact=Contact::create($request->all());
            if($oldPrimaryContact==1 && !empty($contact))
            {
                DB::commit();
            }

            return response()->json($contact);

    }


    public function editStore(Request $request,Contact $contact)
    {
        $rules =[
            'name'=>'required',
            'email'=>'required|email',
            'is_primary'=>'required'
        ];

        $validation=Validator::make($request->all(),$rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }
        DB::beginTransaction();

        if($request->is_primary)
        {
            $request['is_primary'] = 1;

            $customer = $contact->customer;
            $otherPrimaryContact = Contact::where('customer_id',$customer->id)->where('is_primary',Primary::$IS_PRIMARY)->first();
            $otherPrimaryContact->is_primary = 0;
            $otherPrimaryContact->save();
        }
        else
            $request['is_primary'] = 0;

        $contact->update($request->all());
        if(!empty($contact))
        {
            DB::commit();
        }
        return response()->json($contact);

    }

    public function viewAll()
    {
        //$contacts=Contact::where('is_primary',Primary::$IS_PRIMARY)->get();
        $contacts = Contact::all();

        $contactDetails=array();
        foreach ($contacts as $contact)
        {
            $customer=$contact->customer;
            if($customer)
            {
                $location=$customer->locations()->where('is_primary',Primary::$IS_PRIMARY)->first();

                $contactDetail=array(
                    'customer'=>$customer,
                    'contact'=>$contact,
                    'location'=>$location,
                );
                array_push($contactDetails,$contactDetail);
            }

        }

        return response()->json($contactDetails);
    }

    public function allForCustomer(Request $request)
    {
        $id = $request->input('id');
        $customer = Customer::find($id);
        $contacts=$customer->contacts;
        return response()->json($contacts);
    }

    public function viewCustomerContact($id)
    {
        $contact=Contact::where('customer_id',$id)->where('is_primary',Primary::$IS_PRIMARY)->first();
        return response()->json($contact);
    }

    public function viewContact($id)
    {
        $contact=Contact::find($id);
        return response()->json($contact);
    }

    public function viewContactForCustomer($id)
    {
        $contacts = Contact::where('customer_id', $id)->get();

        $contactDetails=array();
        foreach ($contacts as $contact)
        {
            $customer=$contact->customer;

            if($customer)
            {
                $location=$customer->locations()->where('is_primary',Primary::$IS_PRIMARY)->first();

                $contactDetail=array(
                    'contact'=>$contact,
                    'location'=>$location,
                );
                array_push($contactDetails,$contactDetail);
            }

        }

        return response()->json($contactDetails);
    }

}
