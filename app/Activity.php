<?php namespace App;
/*
**Author:tianling
**createTime:15/4/18 下午9:33
*/

use Illuminate\Database\Eloquent\Model;


class Activity extends Model{

    protected $table = 'activity';

    public function pic(){
        return $this->hasMany('App\activityPic','aid','id');
    }



}

