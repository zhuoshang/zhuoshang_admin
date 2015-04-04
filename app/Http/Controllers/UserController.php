<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/3 下午3:23
*/

use App\FrontUser;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller{


    /*
     * 添加新用户
     **/
    public function userAdd(Request $request){
        $realname = $request->input('realname');
        $mobile = $request->input('mobile');

        $check = $this->infoCheck($realname,$mobile);
        if(!$check){
            echo json_encode(array(
                'status'=>'501',
                'msg'=>'关键数据为空或输入不合法'
            ));

            exit();
        }

        $user = new User();
        if(!$user->save()){
            echo json_encode(array(
                'status'=>'500',
                'msg'=>'user basic false'
            ));

            exit();
        }
        $uid = $user->id;

        $front_user = new FrontUser();
        $front_user->real_name = $realname;
        $front_user->mobile = $mobile;
        $front_user->uid = $uid;

       if($front_user->save()){
           echo json_encode(
                array(
                    'status'=>'200',
                    'msg'=>'ok'
                )
           );
           exit();

       }else{
           echo json_encode(array(
               'status'=>'500',
               'msg'=>'user front false'
           ));
           exit();

       }

    }


    /*
     * 输入数据验证
     **/
    private function infoCheck($realname,$mobile){
        if($realname == '' || $mobile == ''){
            return false;
        }

        return true;
    }
}


