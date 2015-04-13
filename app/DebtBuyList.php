<?php namespace App;
/*
**Author:tianling
**createTime:15/3/20 上午12:08
*/

use Illuminate\Database\Eloquent\Model;

class DebtBuyList extends Model{
    protected $table = 'debtBuyList';

    public $timestamps = false;

    public function debtBuy(){
        return $this->belongsTo('App\DebtBuy','bid','id');
    }
}

