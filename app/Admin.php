<?php namespace App;
/*
**Author:tianling
**createTime:15/4/16 上午11:13
*/

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Admin extends Model implements AuthenticatableContract, CanResetPasswordContract{
    use Authenticatable, CanResetPassword;

    protected $table = 'admin';

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function user(){
        return $this->belongsTo('App\User','uid','id');
    }
}