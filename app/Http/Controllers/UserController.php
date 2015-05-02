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
        $idCard = $request->input('idCard');


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
        $front_user->idCard = $idCard;


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
     * 修改用户数据
     **/
    public function userUpdate(Request $request){
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
        $idCard = $request->input('idCard');

        $uid = $request->input('uid');
        $front_user = FrontUser::find($uid);
        if($front_user == ''){
            $this->throwERROE(500,'该用户不存在');
        }


        $check = $this->infoCheck($realname,$mobile);
        if(!$check){
            echo json_encode(array(
                'status'=>'501',
                'msg'=>'关键数据为空或输入不合法'
            ));

            exit();
        }

        $front_user->real_name = $realname;
        $front_user->mobile = $mobile;
        $front_user->address = $address;
        $front_user->gender = $gender;
        $front_user->monthlyIncome = $monthlyIncome;
        $front_user->companyIndustry = $companyIndustry;
        $front_user->companyScale = $companyScale;
        $front_user->userJob = $userJob;
        $front_user->userIntro = $userIntro;
        $front_user->aboutUser = $aboutUser;
        $front_user->idCard = $idCard;

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
                'status'=>504,
                'msg'=>'save error'
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
            if($list->frontUser == ''){
                continue;
            }
            
            $userData['list'][$key] = array(
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
                $userData['lock_list'][] = array(
                    'id'=>$list->front_uid,
                    'phoneNum'=>$front->mobile,
                    'userName'=>$front->real_name,
            );
            }
        }

        $userUpdate = array();
        $updateList = FrontUser::orderBy('updated_at','DESC')
            ->take(5)
            ->skip(0)
            ->get();
        foreach($updateList as $list){
            if($list != ''){
                $userUpdate[] = array(
                    'id'=>$list->front_uid,
                    'phoneNum'=>$list->mobile,
                    'userName'=>$list->real_name
                );
            }
        }


        $userData['update'] = $userUpdate;

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>$userData
        ));

        exit();
    }



    /*
     * 按id查询用户
     **/
    public function getUserById(Request $request){
        $uid = $request->uid;

        if(!is_numeric($uid)){
            $this->throwERROE(501,'id违法');
        }

        $user = FrontUser::find($uid);
        if($user == ''){
            $this->throwERROE(500,'用户不存在');
        }

        if($user->gender == 0){
            $gender = '男';
        }else{
            $gender = '女';
        }

        $userData = array(
            'uid'=>$user->front_uid,
            'userName'=>$user->userName,
            'phoneNum'=>$user->mobile,
            'address'=>$user->address,
            'email'=>$user->email,
            'gender'=>$gender,
            'idCard'=>$user->idCard,
            'monthlyIncome'=>$user->monthlyIncome,
            'company'=>$user->company,
            'companyIndustry'=>$user->companyIndustry,
            'companyScale'=>$user->companyScale,
            'userJob'=>$user->userJob,
            'userIntro'=>$user->userIntro,
            'aboutUser'=>$user->aboutUser

        );

        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>$userData
        ));
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


