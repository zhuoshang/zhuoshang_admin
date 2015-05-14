<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/18 下午9:27
*/

use App\Activity;
use App\ActivityPic;
use App\Charity;
use App\CharityPic;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Auth;


class ActivityController extends Controller
{

    /*
     * 贵宾优享添加
     **/
    public function activityAdd(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $time = $request->input('time');
        $pictures = $request->input('picture');
        $top = $request->input('top');

        if($title == '' || $content == '' || !is_numeric($time)){
            $this->throwERROE(505,'数据为空或者格式非法');
        }

        $activity = new Activity();
        $activity->title = $title;
        $activity->content = $content;
        $activity->time = $time;
        $activity->top = $top;

        if(!$activity->save()){
            $this->throwERROE(502,'database save error');
        }

        $pictures = json_decode($pictures);
        foreach($pictures as $pic){
            $picture = ActivityPic::find($pic);
            if($picture != ''){
                $picture->aid = $activity->id;
                $picture->save();
            }
        }

        $this->show('ok');


    }



    /*
     * 爱心捐助添加
     **/
    public function charityAdd(Request $request){

        $title = $request->input('title');
        $content = $request->input('content');
        $time = $request->input('time');
        $pictures = $request->input('picture');
        $top = $request->input('top');

        if($title == '' || $content == '' || !is_numeric($time)){
            $this->throwERROE(501,'关键数据为空或者参数不合法');
        }

        $charity = new Charity();
        $charity->title = $title;
        $charity->content = $content;
        $charity->time = $time;
        $charity->top = $top;

        if($charity->save()){

            $pictures = json_decode($pictures);
            foreach($pictures as $pic){
                $picture = CharityPic::find($pic);
                if($picture != ''){
                    $picture->cid = $charity->id;
                    $picture->save();
                }
            }

            echo json_encode(array(
                'status'=>200,
                'msg'=>'ok',
                'data'=>''
            ));

            exit();

        }else{
            $this->throwERROE(502,'database save error');
        }
    }





    /**
     * 生成服务器端存储的新文件名
     **/
    private function fileNameMake($fileName,$fileType){
        $string = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";

        $max = strlen($strPol)-1;
        $length = strlen($fileName);
        for($i=0;$i<$length;$i++){
            $string.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        $newFileName = md5($fileName.time().$string).'.'.$fileType;

        return $newFileName;

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