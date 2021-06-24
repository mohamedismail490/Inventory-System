<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $guarded = [];
    //protected $with    = ['employee'];


    public function employee(){
        return $this->belongsTo(Employee::class)->withDefault();
    }
}
