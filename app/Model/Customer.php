<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable=['name','account_manager_id','service_manager_id','status'];

    public function contacts()
    {
        return $this->hasMany('App\Model\Contact');
    }

    public function account_manager()
    {
        return $this->belongsTo('App\User');
    }

    public function service_manager()
    {
        return $this->belongsTo('App\User');
    }

    public function locations()
    {
        return $this->belongsToMany('App\Model\Location')->withPivot('is_primary','is_bill');
    }

    public function equipments()
    {
        return $this->hasMany('App\Model\Equipment');
    }

    public function contracts()
    {
        return $this->hasMany('App\Model\Contract');
    }

    public function workOrders()
    {
        return $this->hasMany('App\Model\WorkOrder');
    }
    public function recurringWorkOrders()
    {
        return $this->hasMany('App\Model\RecurringWorkOrder');
    }

    public function attachments()
    {
        return $this->hasMany('App\Model\Attachment','parent_id');
    }

    protected $dates = ['deleted_at'];



}
