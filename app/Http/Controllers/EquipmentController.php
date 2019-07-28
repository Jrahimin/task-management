<?php

namespace App\Http\Controllers;

use App\Model\Customer;
use App\Model\Equipment;
use App\Model\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{
    public function addStore(Request $request)
    {
        $rules = [
            'type'=>'required|numeric',
            'name'=>'required',
            'manufacturer'=>'required',
            'model_no'=>'required',
            'serial_no'=>'required',
            'customer_id'=>'required|integer',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $equipment = Equipment::create($request->all());
        return response()->json($equipment);
    }

    public function editStore(Request $request, Equipment $equipment)
    {
        $validation = Validator::make($request->all(),[
            'type'=>'required|numeric',
            'name'=>'required',
            'manufacturer'=>'required',
            'model_no'=>'required',
            'serial_no'=>'required',
            'customer_id'=>'required|integer',
        ]);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        $equipment->update($request->all());

        return response()->json($equipment);
    }

    public function viewAll()
    {
        $equipments=Equipment::with('customer')->get();
        return response()->json($equipments);
    }

    public function allForCustomer(Request $request)
    {
        $id = $request->input('id');
        $customer = Customer::find($id);
        $equipments=$customer->equipments;
        return response()->json($equipments);
    }

    public function viewEquipment(Equipment $equipment)
    {
        return response()->json($equipment);
    }

    public function deleteEquipment(Equipment $equipment)
    {
        $equipment->work_orders()->detach();
        $equipment->recurringWorkOrders()->detach();
        $equipment->delete();
        return response()->json($equipment);
    }


    //equipment type handling
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

        $equipmentType = EquipmentType::create($request->all());
        return response()->json($equipmentType);
    }

    public function getTypes()
    {
        $equipmentTypes = EquipmentType::all();

        return response()->json($equipmentTypes);
    }
}
