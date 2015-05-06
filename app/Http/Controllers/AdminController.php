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

        if(Auth::user()->level != 2){
            $this->throwERROE(500,'你无权进行此项操作');
        }
        if($this->accountCheck($name)){
            $this->throwERROE(501,'该用户名已经存在');
        }

        $admin = new Admin();
        $user = new User();

        $user->password = Hash::make($password);
        $user->last_login_ip = $this->getIP();
        $user->last_login_time = time();
        $user->lock = 0;
        $user->save();

        $admin->name = $name;
        $admin->uid = $user->id;
        $admin->level = $level;

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

        $adminCheck->user->last_login_time = time();
        $adminCheck->user->last_login_ip = $this->getIP();
        $adminCheck->user->save();

        Auth::login($adminCheck);

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>''
        ));


    }


    /*
     * 管理员退出API
     **/
    public function adminLogout(){
        Auth::logout();

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>''
        ));
    }


    /*
     * 管理员列表
     **/
    public function adminList(){
        $admins = Admin::all();

        $adminData = array();
        foreach($admins as $admin){

            if($admin->level == 1){
                $level = false;
            }else{
                $level = true;
            }

            $adminData[] = array(
                'userName'=>$admin->name,
                'uid'=>$admin->id,
                'isSuperAdmin'=>$level,
                'lastLogin'=>date('Y-m-d H:i:s',$admin->user->last_login_time)
            );
        }

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>$adminData
        ));
    }


    /*
     * 删除管理员
     **/
    public function adminDelete(Request $request){
        if(Auth::user()->level != 2){
            $this->throwERROE(502,'您无权执行此项操作');
        }

        $aid = $request->id;
        if(!is_numeric($aid)){
            $this->throwERROE(501,'id格式错误');
        }

        $admin = Admin::find($aid);
        if($admin == ''){
            $this->throwERROE(500,'管理员不存在');
        }

        if($admin->user->delete()){
            echo json_encode(array(
                'status'=>200,
                'msg'=>'ok',
                'data'=>''
            ));

            exit();
        }
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
//    private function throwERROE($code,$msg){
//        echo json_encode(array(
//            'status'=>$code,
//            'msg'=>$msg,
//            'data'=>''
//        ));
//
//        exit();
//    }


}