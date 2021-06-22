<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->format('d-m-Y g:i A');
    }
    public function getPhotoAttribute($value){
        return (!empty($value)) ? asset($value) : null;
    }
}
