<?php
namespace App\Http\Controllers;

use App\Enumeration\RecurrenceFrequency;
use App\Model\Appointment;
use App\Model\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function addStore(Request $request)
    {
        if(!$request->is_all_day)
        {
            $start_date_time = $request->start_date.' '.$request->start_time;
            $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));

            $end_date_time = $request->end_date.' '.$request->end_time;
            $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
        }
        else
        {
            $start_date_time = $request->start_date.' '.'00:00';
            $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));

            $end_date_time = $request->end_date.' '.'00:00';
            $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
        }

        $rules = [
            'subject'=>'required',
            'customer_id'=>'required|integer',
            'notes'=>'required',
            'start_date_time'=>'date_format:Y-m-d H:i',
            'end_date_time'=>'date_format:Y-m-d H:i',
        ];
        if($request->work_order_id){
            $rules['work_order_id'] = 'integer';
        }


        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        //array to string => days_in_week
        $request['days_in_week'] = implode(',', $request->days_in_week);


        //return response()->json($request->all());
        // create appointment
        $appointment = Appointment::create($request->all());

        //assigning users
        if($request->user_ids != null)
        {
            foreach ($request->user_ids as $user_id)
            {
                $appointment->users()->attach($user_id);
            }
        }

        if($appointment->is_recurring==1)
        {
            $recurringAppointment = new Appointment();
            $recurringAppointment->addRecurringAppointment($appointment, $request);
        }

        return response()->json($appointment);
    }

    public function editStore(Request $request, Appointment $appointment)
    {

        if(!$request->is_all_day)
        {
            $start_date_time = $request->start_date.' '.$request->start_time;
            $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));
            $end_date_time = $request->end_date.' '.$request->end_time;
            $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
        }

        else
        {
            $start_date_time = $request->start_date.' '.'00:00';

            $request['start_date_time'] = date('Y-m-d H:i', strtotime($start_date_time));
            $end_date_time = $request->end_date.' '.'00:00';
            $request['end_date_time'] = date('Y-m-d H:i', strtotime($end_date_time));
        }

        //return response()->json($request->all());

        $rules = [
            'subject'=>'required',
            'customer_id'=>'required|integer',
            'notes'=>'required',
            'start_date_time'=>'date_format:Y-m-d H:i',
            'end_date_time'=>'date_format:Y-m-d H:i',
        ];
        if($request->work_order_id){
            $rules['work_order_id'] = 'integer';
        }

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json(["success" => false,
                "message" => $validation->errors()->all()], 200);
        }

        //array to string => days_in_week
        $request['days_in_week'] = implode(',', $request->days_in_week);

        $appointment->update($request->all());

        $appointment->users()->detach();
        if($request->user_ids != null)
        {
            foreach ($request->user_ids as $user_id)
            {
                $appointment->users()->attach($user_id);
            }
        }

        if($appointment->is_recurring==1)
        {
            $childAppointments = Appointment::where('parent_id', $appointment->id)->delete();
            $recurringAppointment = new Appointment();
            $recurringAppointment->addRecurringAppointment($appointment, $request);
        }

        return response()->json($appointment);
    }

    public function showAppointments()
    {
        $appointments=Appointment::all();

        foreach ($appointments as $appointment)
        {
            $startDate = strtok($appointment->start_date_time,' ');
            $startTime = strtok('');
            $endDate = strtok($appointment->end_date_time,' ');
            $endTime = strtok('');

            $appointment['start_date'] = date('Y-m-d', strtotime($startDate));
            $appointment['start_time']= date('h:i a', strtotime($startTime));

            $appointment['end_date'] = date('Y-m-d', strtotime($endDate));
            $appointment['end_time']= date('h:i a', strtotime($endTime));

            if($appointment->customer_id)
            {
                $customer =Customer::find($appointment->customer_id);
                if($customer)
                $appointment['customer']=$customer->name;
                else
                    $appointment['customer']='';
            }
            else
                $appointment['customer']='';
            if($appointment->is_all_day==1)
            {
                $appointment['is_all_day']='(all day)';
                $appointment['schedule']='';
            }
            else
            {
                $appointment['is_all_day']='';
                $appointment['schedule']=$appointment->start_time.' to '.$appointment->end_time;
            }
        }

        return response()->json($appointments);
    }

    public function showTodaysEvents($date)
    {
        $dateStart=date('Y-m-d H:i',strtotime($date));
        $date2=$date.' '.'23:59';
        $dateEnd=date('Y-m-d H:i',strtotime($date2));
        $appointments=Appointment::whereBetween('start_date_time',[$dateStart,$dateEnd])->orWhereBetween('end_date_time',[$dateStart,$dateEnd])->get();
        return response()->json($appointments);
    }

    public function showEvent($id)
    {
        $appointment=Appointment::find($id);
        $date = new Carbon($appointment->start_date_time);
        $time = new Carbon($appointment->start_date_time);
        $appointment['start_date'] = $date->format('Y-m-d');
        $appointment['start_time'] = $time->format('H:i A');

        $endDate = new Carbon($appointment->end_date_time);
        $endTime = new Carbon($appointment->end_date_time);
        $appointment['end_date'] = $endDate->format('Y-m-d');
        $appointment['end_time'] = $endTime->format('H:i A');
        if($appointment->is_all_day==1)
        {
            $appointment['is_all_day']='(all day)';
            $appointment['schedule']='';
        }
        else
        {
            $appointment['is_all_day']='';
            $appointment['schedule']=$appointment->start_time.'-'.$appointment->end_time;
        }

        $appointment['days_in_week'] = explode(",", $appointment->days_in_week);

        return response()->json($appointment);
    }

    public function deleteEvent($id)
    {
        $appointment=Appointment::find($id);
        $appointment->users()->detach();
        $appointment->delete();
        return response()->json($appointment);
    }


}
