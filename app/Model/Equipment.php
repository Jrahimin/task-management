<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    protected $table = 'equipments';

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['type','name','manufacturer','model_no','serial_no','install_date',
        'purchase_date','warranty_exp_date','purchased_from','customer_id'];

    public function attachments()
    {
        return $this->hasMany('App\Model\Attachment');
    }

    public function types()
    {
        return $this->hasMany('App\Model\EquipmentType');
    }

    public function customer()
    {
        return $this->belongsTo('App\Model\Customer');
    }

    public function work_orders()
    {
        return  $this->belongsToMany('App\Model\WorkOrder');
    }
    public function recurringWorkOrders()
    {
        return $this->belongsToMany('App\Model\RecurringWorkOrder');
    }
}
