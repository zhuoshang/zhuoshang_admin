<?php namespace App;
/*
**Author:tianling
**createTime:15/4/3 下午3:41
*/

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class FrontUser extends Model implements AuthenticatableContract, CanResetPasswordContract{
    use Authenticatable, CanResetPassword;

    protected $table = 'frontUser';

    protected $primaryKey = 'front_uid';

    public function user(){
        return $this->belongsTo('App\User','uid','id');
    }
}