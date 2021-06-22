<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];
    protected $casts = ['joining_date' => 'date'];
    protected $appends = ['join_date'];

    public function getJoinDateAttribute(){
        return Carbon::parse($this->joining_date)->format('d-m-Y');
    }
    public function getJoiningDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getPhotoAttribute($value){
        return (!empty($value)) ? asset($value) : null;
    }
}
