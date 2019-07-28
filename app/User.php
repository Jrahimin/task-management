<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function accounts_customers()
    {
        return $this->hasMany('App\Model\Customer','accounts_manager_id');
    }

    public function service_customers()
    {
        return $this->hasMany('App\Model\Customer','service_manager_id');
    }

    public function work_orders()
    {
        return  $this->belongsToMany('App\Model\WorkOrder');
    }
}
