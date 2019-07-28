<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['type','name','description','price','cost','status','taxable','note',
        'manufacturer','mfr_item_no','mfr_item_description','list_price'];

    public function work_orders()
    {
       return  $this->belongsToMany('App\Model\WorkOrder');
    }
    public function recurring_workOrders()
    {
        return $this->belongsToMany('App\Model\RecurringWorkOrder');
    }
}
