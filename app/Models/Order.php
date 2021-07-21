<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table   = 'orders';
    protected $guarded = [];
    protected $with    = ['customer','order_details'];

    public function getSubTotalAttribute($value){
        return round( $value , 2);
    }
    public function getTotalAttribute($value){
        return round( $value , 2);
    }

    public function customer(){
        return $this->belongsTo(Customer::class)->withDefault();
    }

    public function order_details(){
        return $this->hasMany(Order_Detail::class,'order_id','id');
    }
}
