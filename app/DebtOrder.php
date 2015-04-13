<?php namespace App;
/*
**Author:tianling
**createTime:15/4/11 下午11:38
*/

use Illuminate\Database\Eloquent\Model;

class DebtOrder extends Model{

    protected $table = 'debtOrder';

    public function user(){
        return $this->belongsTo('App\FrontUser','uid','front_uid');
    }


    public function debt(){
        return $this->belongsTo('App\Debt','did','id');
    }

}