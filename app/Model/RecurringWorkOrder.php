<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringWorkOrder extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['description','type','priority','detailed_description','is_billable',
        'purchase_order_no','status','customer_id','contact_id','contract_id','location_id',
        'bill_location_id','tax_code_id','service_manager_id','account_manager_id','related_person_id',
        'frequency','start_date','end_date','no_of_occurance','end_occurance','interval','time',
        'days_in_week','day_in_month','week_in_month','part_of_month','appointment_day_add',
        'appointment_start_time','appointment_end_time','is_all_day'];

    /*public function appointments()
    {
        return $this->hasMany('App\Model\Appointment');
    }*/

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

    public function workOrders()
    {
        return $this->hasMany('App\Model\WorkOrder');
    }

}
