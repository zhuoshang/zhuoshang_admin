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
        $realname = $request->input('userName');
        $mobile = $request->input('phoneNum');
        $address = $request->input('address');
        $gender = $request->input('gender');
        $monthlyIncome = $request->input('monthlyIncome');
        $companyIndustry = $request->input('companyIndustry');
        $companyScale = $request->input('companyScale');
        $userJob = $request->input('userJob');
        $userIntro = $request->input('userIntro');
        $aboutUser = $request->input('aboutUser');


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
        $front_user->address = $address;
        $front_user->gender = $gender;
        $front_user->monthlyIncome = $monthlyIncome;
        $front_user->companyIndustry = $companyIndustry;
        $front_user->companyScale = $companyScale;
        $front_user->userJob = $userJob;
        $front_user->userIntro = $userIntro;
        $front_user->aboutUser = $aboutUser;


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
     * 用户列表
     **/
    public function userList(Request $request){

        $currentPage = $request->input('currentPage');

        $pageSize = 10;
        $begin = ($currentPage-1)*10;

        $userLists = User::where('lock','=','0')
            ->take($pageSize)
            ->skip($begin)
            ->get();

        $userData = array();
        foreach($userLists as $key=>$list){
            $userData[$key]['list'] = array(
                'id'=>$list->frontUser->front_uid,
                'phoneNum'=>$list->frontUser->mobile,
                'userName'=>$list->frontUser->real_name,
                'creater'=>'超级管理员',
                'modifyCount'=>$list->modify
            );
        }

        //若当前页为1则返回总页数
        if($currentPage == 1){
            $userCount = User::where('lock','=','0')->count();
            $pageNum = ceil($userCount/10);
            $userData['pageCount'] = $pageNum;
        }else{
            $userData['pageCount'] = '';
        }

        $userData['currentPage'] = $currentPage;

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>$userData
        ));

    }


    /*
     * 锁定用户列表
     **/
    public function lockUserList(){
        $userLists = User::where('lock','=','1')->get();

        $userData = array();
        foreach($userLists as $list){
            $front = $list->frontUser;
            if(is_object($front)){
                $userData[] = array(
                    'phoneNum'=>$front->mobile,
                    'userName'=>$front->real_name,
            );
            }
        }

        
        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>$userData
        ));

        exit();
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


