<?php

namespace App\Model;

use App\Enumeration\RecurrenceFrequency;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Appointment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['subject','start_date_time','end_date_time','is_all_day','notes','work_order_id','customer_id',
        'is_recurring','parent_id','frequency','no_of_occurance','end_occurance','interval','days_in_week','day_in_month',
        'part_of_month','recurring_end_date'];

    public function customers()
    {
        return $this->belongsTo('App\Model\Customer');
    }

    public function work_order()
    {
        return $this->hasOne('App\Model\WorkOrder');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function addRecurringAppointment(Appointment $appointment, Request $request)
    {
        $startDateTime= new Carbon($request->start_date_time);
        $tempDateTime=$startDateTime;
        $interval=null;
        $count=1;
        // determining interval
        if(!empty($appointment->frequency))
        {
            if($appointment->frequency==RecurrenceFrequency::$DAILY)
                $interval=$appointment->interval." days";

            elseif ($appointment->frequency==RecurrenceFrequency::$WEEKLY)
                $interval=$appointment->interval." weeks";

            elseif ($appointment->frequency==RecurrenceFrequency::$MONTHLY)
                $interval=$appointment->interval . " months";
            elseif (($appointment->frequency==RecurrenceFrequency::$ONCE))
                $interval=$appointment->interval . " years";
        }
        elseif(empty($appointment->frequency))
        {
            // this is the case where no intervals are chosen
            $interval=$appointment->interval . " months";
        }

        if(empty($appointment->recurring_end_date))
            $recurringEndDate = "1900-05-12";
        else
            $recurringEndDate=date('Y-m-d',strtotime($appointment->recurring_end_date));

        if(empty($appointment->end_occurance))
            $endOccurance = -100;
        else
            $endOccurance=$appointment->end_occurance;

        while(1)
        {
            $tempDateTime=date('Y-m-d H:i', strtotime($tempDateTime. " + ".$interval));
            $tempDate = date('Y-m-d', strtotime($tempDateTime));

            if($recurringEndDate!="1900-05-12" && $tempDate>$recurringEndDate)
                break;
            if($endOccurance!=-100 && $count>$endOccurance)
                break;

            if(!empty($appointment->days_in_week))
            {
                $daysInWeek=explode(',', $appointment->days_in_week);
                $tempDate1=new Carbon($tempDateTime);
                $tempDate2=new Carbon($tempDateTime);
                $startOfWeek=$tempDate1->startOfWeek();
                $endOfWeek=$tempDate2->endOfWeek();
                for ($i=$startOfWeek;$i<=$endOfWeek;$i=$i->addDays(1))
                {
                    $day=$i->format('D');
                    $iDate = $i->format('Y-m-d');
                    if(in_array($day,$daysInWeek))
                    {
                        if($endOccurance!=-100 && $count>$endOccurance)
                            break;
                        if($recurringEndDate!="1900-05-12" && $iDate>$recurringEndDate)
                            break;

                        $date=$i->format('Y-m-d H:i');

                        //creating appointment
                        $appointmentFromRecurring = Appointment::create([
                            'subject'=>$appointment->subject,
                            'start_date_time'=>$date,
                            'end_date_time'=>$date,
                            'is_all_day'=>$appointment->is_all_day,
                            'notes'=>$appointment->notes,
                            'work_order_id'=>$appointment->work_order_id,
                            'customer_id'=>$appointment->customer_id,
                            'is_recurring'=>0,
                            'parent_id'=>$appointment->id,
                        ]);

                        //incrementing count to compare with end occurance
                        $count++;

                        //assigning users
                        if($request->user_ids != null)
                        {
                            foreach ($request->user_ids as $user_id)
                            {
                                $appointmentFromRecurring->users()->attach($user_id);
                            }
                        }
                    }
                }
                continue;
            }

            if(!empty($appointment->part_of_month))
            {
                $tempDateTime1=new Carbon($tempDateTime);
                $tempDateTime2=new Carbon($tempDateTime);
                $tempMonth=$tempDateTime1->format('F');
                $tempYear=$tempDateTime2->format('Y');
                $dateString=$appointment->part_of_month." ".$appointment->day_in_month . " of ".$tempMonth.",".$tempYear;
                $tempDateTime=date('Y-m-d H:i',strtotime($dateString));
            }


            //creating appointment
            $appointmentFromRecurring = Appointment::create([
                'subject'=>$appointment->subject,
                'start_date_time'=>$tempDateTime,
                'end_date_time'=>$tempDateTime,
                "is_all_day"=>$appointment->is_all_day,
                'notes'=>$appointment->notes,
                'work_order_id'=>$appointment->work_order_id,
                'customer_id'=>$appointment->customer_id,
                'is_recurring'=>0,
                'parent_id'=>$appointment->id,
            ]);

            // incrementing count to compare with end occuarance
            $count++;

            //assigning users
            if($request->user_ids != null)
            {
                foreach ($request->user_ids as $user_id)
                {
                    $appointmentFromRecurring->users()->attach($user_id);
                }
            }
        }
    }
}
