<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/16 上午11:10
*/


use App\Admin;
use App\FrontUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;


class AdminController extends Controller{

    /*
     * 添加管理员
     **/
    public function adminAdd(Request $request){

        $name = $request->input('name');
        $password = $request->input('password');
        $level = $request->input('level');

        $admin = new Admin();
        $user = new User();

        $user->password = Hash::make($password);
        $user->last_login_ip = $this->getIP();
        $user->last_login_time = time();
        $user->lock = 0;
        $user->save();

        $admin->name = $name;
        $admin->uid = $user->id;

        if($admin->save()){
            echo json_encode(array(
                'status'=>200,
                'msg'=>'',
                'data'=>''
            ));

            exit();
        }
    }

    public function adminLoginPage(){
        echo "This is the login page";
    }

    /*
     * 管理员登录API
     **/
    public function adminLogin(Request $request){
        $name = $request->name;
        $password = $request->password;

        $adminCheck = $this->accountCheck($name);
        if(!$adminCheck){
            $this->throwERROE(500,'账号不存在');
        }

        if(!Hash::check($password,$adminCheck->user->password)){
            $this->throwERROE(501,'密码验证失败');
        }

        Auth::login($adminCheck);

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>''
        ));


    }


    /*
     * 管理员账号验证
     **/
    private function accountCheck($name){
        $adminCheck = Admin::where('name','=',$name)->first();

        if($adminCheck == ''){
            return false;
        }

        return $adminCheck;
    }



    /*
    *  获取客户端ip地址
    **/
    private function getIP(){
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }
        elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        elseif(!empty($_SERVER["REMOTE_ADDR"])){
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        else{
            $cip = "无法获取！";
        }
        return $cip;
    }


    /*
    * 抛错函数
    **/
    private function throwERROE($code,$msg){
        echo json_encode(array(
            'status'=>$code,
            'msg'=>$msg,
            'data'=>''
        ));

        exit();
    }


}