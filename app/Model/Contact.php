<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
        use SoftDeletes;
        protected $fillable=['name','job_title','email','mobile_phone','home_phone','fax','customer_id','is_primary'];

        public function customer()
        {
            return $this->belongsTo('App\Model\Customer');
        }

        protected $dates = ['deleted_at'];

}
