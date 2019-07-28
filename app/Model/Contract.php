<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','status','start_date','end_date','hour_limit','money_limit',
        'unlimited','overage_allow','type','contract_no','purchase_order_no','description','terms','notes',
        'subtotal','total','customer_id','location_id','contact_id','tax_code_id'];

    public function location()
    {
        return $this->belongsTo('App\Model\Location');
    }

    public function contact()
    {
        return $this->belongsTo('App\Model\Contact');
    }

    public function customer()
    {
        return $this->belongsTo('App\Model\Customer');
    }

    public function types()
    {
        $this->hasMany('App\Model\ContractType');
    }

    public function attachments()
    {
        $this->hasMany('App\Model\Attachment','parent_id');
    }
}
