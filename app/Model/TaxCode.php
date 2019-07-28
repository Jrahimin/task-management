<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxCode extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['name','agency','rate','is_default'];

    public function contracts()
    {
        return $this->hasMany('App\Model\Contract');
    }

/*    public function work_orders()
    {
        $this->hasMany('App\Model\Work_Order');
    }*/
}
