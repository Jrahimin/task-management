<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','type','description','parent_id'];

    public function workOrder()
    {
        return $this->belongsTo('App\Model\WorkOrder', 'parent_id');
    }

    public function RecurringWorkOrder()
    {
        return $this->belongsTo('App\Model\RecurringWorkOrder', 'parent_id');
    }
}
