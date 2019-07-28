<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use App\Model\RecurringWorkOrder;

class WorkOrder extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['description','type','priority','detailed_description','is_billable',
                'purchase_order_no','status','customer_id','contact_id','contract_id','location_id',
                'bill_location_id','tax_code_id','service_manager_id','account_manager_id','related_person_id','recurring_work_order_id'];

    public function appointment()
    {
        return $this->hasOne('App\Model\Appointment');
    }

    public function equipments()
    {
        return $this->belongsToMany('App\Model\Equipment');
    }

    public function items()
    {
        return $this->belongsToMany('App\Model\Item');
    }

    public function customer()
    {
        return $this->belongsTo('App\Model\Customer');
    }

    public function contract()
    {
        return $this->belongsTo('App\Model\Contract');
    }

    public function contact()
    {
        return $this->belongsTo('App\Model\Contact');
    }

    public function location()
    {
        return $this->belongsTo('App\Model\Location');
    }

    public function bill_location()
    {
        return $this->belongsTo('App\Model\Location');
    }

    public function tax_code()
    {
        return $this->belongsTo('App\Model\TaxCode');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function attachments()
    {
        $this->hasMany('App\Model\Attachment','parent_id');
    }

    public function logs()
    {
        $this->hasMany('App\Model\Log','parent_id');
    }

    public function recurringWorkOrder()
    {
        return $this->belongsTo('App\Model\RecurringWorkOrder');
    }

    public function createWorkOrderFromRecurringJob(RecurringWorkOrder $recurringWorkOrder)
    {
        $workOrder = WorkOrder::create([
            'description'=>$recurringWorkOrder->description,
            'type'=>$recurringWorkOrder->type,
            'priority'=>$recurringWorkOrder->priority,
            'detailed_description'=>$recurringWorkOrder->detailed_description,
            'is_billable'=>$recurringWorkOrder->is_billable,
            'purchase_order_no'=>$recurringWorkOrder->purchase_order_no,
            'status'=>$recurringWorkOrder->status,
            'customer_id'=>$recurringWorkOrder->customer_id,
            'contact_id'=>$recurringWorkOrder->contact_id,
            'contract_id'=>$recurringWorkOrder->contract_id,
            'location_id'=>$recurringWorkOrder->location_id,
            'bill_location_id'=>$recurringWorkOrder->bill_location_id,
            'tax_code_id'=>$recurringWorkOrder->tax_code_id,
            'service_manager_id'=>$recurringWorkOrder->service_manager_id,
            'account_manager_id'=>$recurringWorkOrder->account_manager_id,
            'related_person_id'=>$recurringWorkOrder->related_person_id,
            'recurring_work_order_id'=>$recurringWorkOrder->id,
        ]);

        //checking if work order is scheduled
        if($recurringWorkOrder->appointment_day_add==null)
            return $workOrder;

        $workOrderCreatedDateTime = new Carbon($workOrder->created_at);
        $appointmentStartDate = $workOrderCreatedDateTime->addDays($recurringWorkOrder->appointment_day_add)
            ->format('Y-m-d');

        // if work order is in time range
        if($recurringWorkOrder->is_scheduled==1)
        {
            if(!$recurringWorkOrder->is_all_day)
            {
                $startDateTime = $appointmentStartDate.' '.$recurringWorkOrder->appointment_start_time;
                $startDateTime = date('Y-m-d H:i', strtotime($startDateTime));

                $endDateTime = $appointmentStartDate.' '.$recurringWorkOrder->appointment_end_time;
                $endDateTime = date('Y-m-d H:i', strtotime($endDateTime));
            }
            else //if all day long schedule
            {
                $startDateTime = $appointmentStartDate.' '.'00:00';
                $startDateTime = date('Y-m-d H:i', strtotime($startDateTime));

                $endDateTime = $appointmentStartDate.' '.'00:00';
                $endDateTime = date('Y-m-d H:i', strtotime($endDateTime));
            }
        }

        if($recurringWorkOrder->is_scheduled==1)
        {
            $appointment = Appointment::create([
                "subject"=>$workOrder->description,
                "start_date_time"=>$startDateTime,
                "end_date_time"=>$endDateTime,
                "customer_id"=>$workOrder->customer_id,
                "work_order_id"=>$workOrder->id,
                "is_all_day"=>$recurringWorkOrder->is_all_day,
                "notes"=>$workOrder->detailed_description,
            ]);
        }
    }
}
