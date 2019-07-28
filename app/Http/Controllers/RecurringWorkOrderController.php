<?php

namespace App\Http\Controllers;

use App\Enumeration\Bill;
use App\Enumeration\BillTo;
use App\Enumeration\RecurrenceFrequency;
use App\Model\Customer;
use App\Model\Location;
use App\Model\Log;
use App\Model\RecurringWorkOrder;
use App\Model\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecurringWorkOrderController extends Controller
{
    public function addStore(Request $request)
    {
        if($request->end_date == null)
        {
            $startDateFromRequest = new Carbon($request->start_date);
            //if end_date null, set it to ++50 years limit
            $request['end_date'] = $startDateFromRequest->addYears(50)->format('Y-m-d');
        }

        $rules = [
            'description'=>'required',
            'type'=>'required|numeric',
            'is_billable'=>'required|integer',
            'customer_id'=>'required|integer',
            'contact_id'=>'required|integer',
            'location_id'=>'required|integer',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
            'time'=>'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        //array to string => days_in_week
        $request['days_in_week'] = implode(',', $request->days_in_week);

        //return response()->json($request->all());
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


        // create recurring work order
        $recurringWorkOrder = RecurringWorkOrder::create($request->all());


        //create log
        $log = Log::create([
            "name"=>'Created',
            "type"=>2,
            "description"=>'New Recurring Job is defined',
            "parent_id"=>$recurringWorkOrder->id,
        ]);

       // assigning equipments
        if($request->equipment_ids != null)
        {
            foreach ($request->equipment_ids as $equipment_id)
            {
                $recurringWorkOrder->equipments()->attach($equipment_id);
            }
        }


        // assigning users
        if($request->is_assigned)
        {
            foreach ($request->user_ids as $user_id)
            {
                $recurringWorkOrder->users()->attach($user_id);
            }
        }


        return response()->json($recurringWorkOrder);
    }

    public function editStore(Request $request, RecurringWorkOrder $recurringWorkOrder)
    {
        $rules = [
            'description'=>'required',
            'type'=>'required|numeric',
            'is_billable'=>'required|integer',
            'bill_to'=>'required|integer',
            'customer_id'=>'required|integer',
            'contact_id'=>'required|integer',
            'location_id'=>'required|integer',
            'start_date'=>'required|date',
            'time'=>'required'
        ];

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


        $recurringWorkOrder->update($request->all());

        //create log
        $log = Log::create([
            "name"=>'Updated',
            "type"=>2,
            "description"=>'Recurring Work order has been updated',
            "parent_id"=>$recurringWorkOrder->id,
        ]);

        $recurringWorkOrder->equipments()->detach();
        if($request->equipment_ids != null)
        {
            foreach ($request->equipment_ids as $equipment_id)
            {
                $recurringWorkOrder->equipments()->attach($equipment_id);
            }
        }


        return response()->json($recurringWorkOrder);
    }

    public function viewAll()
    {
        $recurringWorkOrders=RecurringWorkOrder::all();
        return response()->json($recurringWorkOrders);
    }
    public function viewAllwithDetails(Request $request)
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
                $recurringWorkOrders=RecurringWorkOrder::where('id', $request->text)->get();
            }
            else
            {
                if($request->type=='customer'){
                $relation = "customer";
                $column = "name";
            }

            else if($request->type=='city'){
                $relation = "location";
                $column = "name";
            }

                $recurringWorkOrders=RecurringWorkOrder::with('customer', 'location', 'users')
                    ->whereHas($relation, function ($q) use($column, $request){
                        $q->where($column, 'like', '%'.$request->text.'%');
                    })->where($parameters)->get();
            }

        }
        else
        {
            $recurringWorkOrders=RecurringWorkOrder::where($parameters)->get();
        }

        $recurringDetails=array();
        foreach ($recurringWorkOrders as $recurringWorkOrder)
        {
            $customer=$recurringWorkOrder->customer;
            $location=$recurringWorkOrder->location;
            $workOrder=WorkOrder::where('recurring_work_order_id',$recurringWorkOrder->id)->orderBy('id', 'Desc')->first();
            if($workOrder)
                $lastRun=date($workOrder->created_at, strtotime('Y-m-d g:i a'));
            else
                $lastRun = "Did Not Run Yet";

            $startDate=' Starts :'.$recurringWorkOrder->start_date;
            $end='';
            $monthly='';
            $days='';
            $every='';
            if($recurringWorkOrder->end_date)
                $end=' Ends : '.$recurringWorkOrder->end_date;
            else
                $end=' Ends after : '.$recurringWorkOrder->end_occurance .' occurances';
            if($recurringWorkOrder->frequency==RecurrenceFrequency::$DAILY)
                $every='Every '.$recurringWorkOrder->interval.' days';
            elseif ($recurringWorkOrder->frequency==RecurrenceFrequency::$WEEKLY)
            {
                $every='Every '.$recurringWorkOrder->interval.' weeks';
                $days=' on '.$recurringWorkOrder->days_in_week;
            }

            elseif ($recurringWorkOrder->frequency==RecurrenceFrequency::$MONTHLY)
            {
                $every='Every '.$recurringWorkOrder->interval.' months';
                if($recurringWorkOrder->part_of_month)
                {
                    $monthly=' every '.$recurringWorkOrder->part_of_month.' th '.$recurringWorkOrder->week_in_month.' ';
                }
                else{
                    $monthly=' on the '.$recurringWorkOrder->day_in_month.'th of the month ';
                }
            }

            else
                $every='Once ';

            $occursAt=' at '.$recurringWorkOrder->time;
            $recurring=array(
                'recurring'=>$recurringWorkOrder,
                'customer'=>$customer,
                'location'=>$location,
                'schedule_time'=>$every.$monthly.$days.$occursAt,
                'schedule_date_start'=>$startDate,
                'schedule_date_end'=>$end,
                'last_run'=>$lastRun
            );
            array_push($recurringDetails,$recurring);
        }
        return response()->json($recurringDetails);
    }

    public function viewRecurring($id)
    {
        $recurringWorkOrder=RecurringWorkOrder::find($id);
        $equipments=$recurringWorkOrder->equipments;
        $recurringWorkOrder['equipments']=$equipments;
        $recurringWorkOrder['days_in_week'] = explode(",", $recurringWorkOrder->days_in_week);

        return response()->json($recurringWorkOrder);
    }

    public function changeStatus(RecurringWorkOrder $recurringWorkOrder, Request $request)
    {
        $recurringWorkOrder->status = $request->status;
        $recurringWorkOrder->save();
        if(!empty($request->users))
        {
            foreach ($request->users as $user)
            {
                $recurringWorkOrder->users()->attach($user);
            }
        }

        return response()->json($recurringWorkOrder);
    }

    public function delete(RecurringWorkOrder $recurringWorkOrder)
    {
        if($recurringWorkOrder->items)
            $recurringWorkOrder->items()->detach();

        if($recurringWorkOrder->equipments)
            $recurringWorkOrder->equipments()->detach();

        if($recurringWorkOrder->users)
            $recurringWorkOrder->users()->detach();

        $recurringWorkOrder->delete();
    }
}
