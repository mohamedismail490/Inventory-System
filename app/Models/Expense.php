<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Expense extends Model
{
    protected $guarded = [];
    protected $casts = ['expense_date' => 'date'];
    protected $appends = ['exp_date','details_limited'];

    public function getExpDateAttribute(){
        return Carbon::parse($this->expense_date)->format('d-m-Y');
    }
    public function getExpenseDateAttribute($value){
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getAmountAttribute($value){
        return round( $value , 2);
    }
    
    public function getDetailsLimitedAttribute(){
        return Str::limit( $this->details, 100, '...' );
    }
}
