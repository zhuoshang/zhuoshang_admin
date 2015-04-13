<?php namespace App;
/*
**Author:tianling
**createTime:15/3/20 上午12:08
*/

use Illuminate\Database\Eloquent\Model;


class DebtBuy extends Model{

    protected  $table = 'debtBuy';

    public $timestamps = false;

    public function debtBuyList(){
        return $this->hasMany('App\DebtBuyList','bid','id');
    }

    public function debt(){
        return $this->belongsTo('App\Debt','did','id');
    }

    public function user(){
        return $this->belongsTo('App\FrontUser','uid','front_uid');
    }

}