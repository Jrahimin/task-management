<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Location extends Model
{
    use SoftDeletes;
    protected $fillable=['name','address','city','state','zip','country','zone'];

    public function customers()
    {
        return $this->belongsToMany('App\Model\Customer');
    }
    protected $dates = ['deleted_at'];


    public function createLocation(Request $request,$locationName)
    {
        $location=Location::create(['name'=>$locationName,
            'address'=>$request->address,
            'city'=>$request->city,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'country'=>$request->country,
            'zone'=>$request->zone]);
      return $location;
    }
}
