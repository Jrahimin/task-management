<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable=['file_path','link_url','description','visibility','type','parent_id'];

}
