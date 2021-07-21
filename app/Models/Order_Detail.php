<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_Detail extends Model
{
    protected $table   = 'order_details';
    protected $with    = ['product'];
    protected $guarded = [];

    public function order(){
        return $this->belongsTo(Order::class)->withDefault();
    }
    public function product(){
        return $this->belongsTo(Product::class)->withDefault();
    }
}
