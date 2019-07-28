<?php

namespace App\Http\Controllers;

use App\Enumeration\Bill;
use App\Enumeration\BillTo;
use App\Enumeration\WorkOrderStatus;
use App\Model\Appointment;
use App\Model\Customer;
use App\Model\Location;
use App\Model\Log;
use App\Model\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psr\Log\NullLogger;

class WorkOrderController extends Controller
{
    public function addStore(Request $request)
    {
        //return redirect()->route('all_work_order_vue');

        //if not all day, generating date_time value with specific format
        if($request->is_scheduled==1)
        {
            if(!$request->is_all_day)
            {
                $start_date_time = $request->start_date.' '.$request->start_time;
                $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));

                $end_date_time = $request->end_date.' '.$request->end_time;
                $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
            }
            else{
                $request['start_time'] = "00:00:00";
                $request['end_time'] = "00:00:00";

                $start_date_time = $request->start_date.' '.$request->start_time;
                $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));

                $end_date_time = $request->end_date.' '.$request->end_time;
                $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
            }
        }


        $rules = [
            'description'=>'required',
            'type'=>'nullable|numeric',
            'is_billable'=>'required|integer',
            'customer_id'=>'required|integer',
            'contact_id'=>'required|integer',
            'location_id'=>'required|integer',
            'bill_to'=>'required|integer',
            'start_date_time'=>'nullable|date_format:Y-m-d H:i',
            'end_date_time'=>'nullable|date_format:Y-m-d H:i',
        ];

        if($request->status == WorkOrderStatus::$ASSIGNED)
            $rules['end_date'] = 'required';

        if($request->start_date!=null)
        {
            $rules['end_date'] = 'required';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }


        // if the bill location is customer's default billing location
        if($request->bill_to==BillTo::$DEFAULT_BILL)
        {
            $customer=Customer::find($request->customer_id);
            $defaultLocation = $customer->locations()->where('is_bill',Bill::$IS_BILL)->first();
            $request['bill_location_id'] = $defaultLocation->id;
        }

        // if the bill location is another customer's location
        elseif ($request->bill_to== BillTo::$DIFFERENT_CUSTOMER)
        {
            $request['bill_location_id']=$request->customer_location_id;
        }

        //if the bill location is another location entered manually
        elseif ($request->bill_to == BillTo::$OTHER_LOCATION)
        {
            $location=new Location();
            $locationName=$request->location_name;
            $location=$location->createLocation($request,$locationName);
            $request['bill_location_id'] = $location->id;
        }

        // create work order
        $workOrder = WorkOrder::create($request->all());

        //create log
        $log = Log::create([
            "name"=>'Created',
            "type"=>1,
            "description"=>'New work order has been created',
            "parent_id"=>$workOrder->id,
        ]);

        if($request->is_scheduled==1)
        {
            $appointment = Appointment::create([
                "subject"=>$request->description,
                "start_date_time"=>$request->start_date_time,
                "end_date_time"=>$request->end_date_time,
                "customer_id"=>$request->customer_id,
                "work_order_id"=>$workOrder->id,
                "is_all_day"=>$request->is_all_day,
                "notes"=>$request->detailed_description,
            ]);
        }


        if($request->equipment_ids != null)
        {
            foreach ($request->equipment_ids as $equipment_id)
            {
                $workOrder->equipments()->attach($equipment_id);
            }
        }

        if($request->is_assigned)
        {
            foreach ($request->user_ids as $user_id)
            {
                $workOrder->users()->attach($user_id);
            }
        }


        return response()->json($request->all());
    }

    public function editStore(Request $request, WorkOrder $workOrder)
    {
        $rules = [
            'description'=>'required',
            'type'=>'required|numeric',
            'customer_id'=>'required|integer',
            'contact_id'=>'required|integer',
            'location_id'=>'required|integer'
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->first()], 406);
        }

        $workOrder->update($request->all());

        //create log
        $log = Log::create([
            "name"=>'Updated',
            "type"=>1,
            "description"=>'Work order has been updated',
            "parent_id"=>$workOrder->id,
        ]);


        $workOrder->equipments()->detach();
        if($request->equipment_ids != null)
        {
            foreach ($request->equipment_ids as $equipment_id)
            {
                $workOrder->equipments()->attach($equipment_id);
            }
        }

        return response()->json($workOrder);
    }

    public function addItemStore(Request $request, WorkOrder $workOrder)
    {
        $rules = [
            'item_id'=>'required|integer',
            'date'=>'required|date',
            'quantity'=>'required|numeric',
            'billing_status'=>'required|integer',
            'tax_status'=>'required|integer',
        ];
        if($request->price){
            $rules['price'] = 'integer';
        }
        if($request->cost){
            $rules['cost'] = 'integer';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->first()], 406);
        }

        $workOrder->items()->attach($request->item_id, array("date"=>$request->date,"quantity"=>$request->quantity,
            "price"=>$request->price,"cost"=>$request->cost,"description"=>$request->description,
            "comment"=>$request->comment,"billing_status"=>$request->billing_status,"tax_status"=>$request->tax_status,));

        return response()->json($workOrder);
    }

    public function editItemStore(Request $request, WorkOrder $workOrder)
    {
        $rules = [
            'item_id'=>'required|integer',
            'date'=>'required|date',
            'quantity'=>'required|numeric',
            'billing_status'=>'required|integer',
            'tax_status'=>'required|integer',
        ];
        if($request->price){
            $rules['price'] = 'integer';
        }
        if($request->cost){
            $rules['cost'] = 'integer';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->first()], 406);
        }

        $workOrder->items()->detach();
        $workOrder->items()->attach($request->item_id, array("date"=>$request->date,"quantity"=>$request->quantity,
            "price"=>$request->price,"cost"=>$request->cost,"description"=>$request->description,
            "comment"=>$request->comment,"billing_status"=>$request->billing_status,"tax_status"=>$request->tax_status,));

        return response()->json($workOrder);
    }


    public function viewAll(Request $request)
    {
        $parameters = [];

        if($request->stat)
        {
            $parameters[] = array('status', $request->stat);
        }

        if($request->text!='')
        {
            if($request->type=='id')
            {
                $workOrders=WorkOrder::with('customer', 'location', 'users')
                    ->where('id', $request->text)->get();
                return response()->json($workOrders);
            }

            else if($request->type=='customer'){
                $relation = "customer";
                $column = "name";
            }

            else if($request->type=='city'){
                $relation = "location";
                $column = "name";
            }

            $workOrders=WorkOrder::with('customer', 'location', 'users')
                ->whereHas($relation, function ($q) use($column, $request){
                    $q->where($column, 'like', '%'.$request->text.'%');
                })->where($parameters)->get();

            return response()->json($workOrders);
        }

        $workOrders=WorkOrder::with('customer', 'location', 'users')->where($parameters)->get();

        return response()->json($workOrders);
    }


    public function assignTo(WorkOrder $workOrder, Request $request)
    {
        if(!$request->is_all_day)
        {
            $start_date_time = $request->start_date.' '.$request->start_time;
            $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));

            $end_date_time = $request->end_date.' '.$request->end_time;
            $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
        }
        else{
            $request['start_time'] = "00:00:00";
            $request['end_time'] = "00:00:00";

            $start_date_time = $request->start_date.' '.$request->start_time;
            $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));

            $end_date_time = $request->end_date.' '.$request->end_time;
            $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
        }
        if($request->is_scheduled==1)
        {
            $workOrder->status=WorkOrderStatus::$ASSIGNED;
            $workOrder->save();
            $appointment = Appointment::create([
                "subject"=>$workOrder->description,
                "start_date_time"=>$request->start_date_time,
                "end_date_time"=>$request->end_date_time,
                "customer_id"=>$workOrder->customer_id,
                "work_order_id"=>$workOrder->id,
                "is_all_day"=>$request->is_all_day,
                "notes"=>$workOrder->detailed_description,
            ]);
        }

        if($request->user_ids != null)
        {
            foreach ($request->user_ids as $user_id)
            {
                $workOrder->users()->attach($user_id);
            }
        }
    }

    public function viewWorkOrder(WorkOrder $workOrder)
    {
        return response()->json($workOrder);
    }

    public function changeStatus(WorkOrder $workOrder, Request $request)
    {
        $workOrder->status = $request->status;
        $workOrder->save();
        if(!empty($request->users))
        {
            foreach ($request->users as $user)
            {
                $workOrder->users()->attach($user);
            }
        }

        return response()->json($workOrder);
    }

    public function delete(WorkOrder $workOrder)
    {
        if($workOrder->items)
            $workOrder->items()->detach();

        if($workOrder->equipments)
            $workOrder->equipments()->detach();

        if($workOrder->users)
            $workOrder->users()->detach();

        $workOrder->delete();
    }
}
