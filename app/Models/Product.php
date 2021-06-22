<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    protected $casts   = ['buying_date' => 'date'];
    protected $appends = ['buy_date'];
    protected $with    = ['category','supplier'];

    public function getBuyDateAttribute(){
        return Carbon::parse($this->buying_date)->format('d-m-Y');
    }
    public function getBuyingDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }
    public function getBuyingPriceAttribute($value){
        return round( $value , 2);
    }
    public function getSellingPriceAttribute($value){
        return round( $value , 2);
    }
    public function getImageAttribute($value){
        return (!empty($value)) ? asset($value) : null;
    }


    public function category(){
        return $this->belongsTo(Category::class)->withDefault();
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class)->withDefault();
    }
}
