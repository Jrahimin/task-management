<?php

namespace App\Http\Controllers;

use App\Model\Contract;
use App\Model\ContractType;
use App\Model\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function addStore(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'status'=>'required|numeric',
            'start_date'=>'required|date',
            'customer_id'=>'required|integer',
            'location_id'=>'required|integer',
            'contact_id'=>'required|integer',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $contract = Contract::create($request->all());
        return response()->json($contract);
    }

    public function editStore(Request $request, Contract $contract)
    {
        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'status'=>'required|numeric',
            'start_date'=>'required|date',
            'customer_id'=>'required|integer',
            'location_id'=>'required|integer',
            'contact_id'=>'required|integer',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $contract->update($request->all());

        return response()->json($contract);
    }

    public function viewAll()
    {
        $contracts=Contract::with('customer')->get();
        return response()->json($contracts);
    }

    public function allForCustomer(Request $request)
    {
        $id = $request->input('id');
        $customer = Customer::find($id);

        $contracts=$customer->contracts;
        return response()->json($contracts);
    }

    public function viewContract($id)
    {
        $contract=Contract::find($id);
        return response()->json($contract);
    }

    public function changeStatus(Contract $contract, Request $request)
    {
        $contract->status = $request->status;
        $contract->save();

        return response()->json($contract);
    }

    public function deleteContract(Contract $contract)
    {
        $contract->delete();
        return response()->json($contract);
    }



    //contract type handling
    public function addType(Request $request)
    {
        $rules = [
            'name'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $contractType = ContractType::create($request->all());
        return response()->json($contractType);
    }

    public function getTypes()
    {
        $contractTypes = ContractType::all();

        return response()->json($contractTypes);
    }

}
