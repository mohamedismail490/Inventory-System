<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    protected $table   = 'pos';
    protected $guarded = [];
    protected $with    = ['product'];

//    protected static function boot()
//    {
//        parent::boot();
//
//        $items = self::all();
//        foreach ($items as $item){
//            if (empty($item -> product) || ($item -> product -> quantity < 1) || ($item -> product -> quantity < $item -> quantity)){
//                $item -> delete();
//            }
//        }
//    }

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
