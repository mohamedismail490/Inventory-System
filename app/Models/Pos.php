<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    protected $table   = 'pos';
    protected $guarded = [];
    protected $with    = ['product'];

    public function getPriceAttribute($value){
        return round( $value , 2);
    }
    public function getSubTotalAttribute($value){
        return round( $value , 2);
    }

    public function product(){
        return $this->belongsTo(Product::class)->withDefault();
    }
}
