<?php namespace App;
/*
**Author:tianling
**createTime:15/3/20 上午12:07
*/

use Illuminate\Database\Eloquent\Model;

class Debt extends Model{

    protected $table = 'debt';

    public $timestamps = false;

    public function debtBuy(){
       return $this->hasMany('App\DebtBuy','did','id');
    }

    public function debtType(){
        return $this->belongsTo('App\DebtType','type','id');
    }

    public function debtPic(){
        return $this->hasMany('App\DebtPic','did','id');
    }

    public function proPic(){
        return $this->hasMany('App\DebtPro','did','id');
    }


}