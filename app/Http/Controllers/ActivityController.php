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


    /*
    * 爱心捐赠列表
    **/
    public function charityList(){
        $charityList = Charity::orderBy('created_at','DESC')->get();

        $charityData = array();
        foreach($charityList as $list){


            $begin = strtotime($list->created_at);
            $end = strtotime($list->created_at) + $list->time*60*60*24;

            $now = time();
            if(($now - $begin)/60/60/24 < $list->time){
                $status = 1;
            }else{
                $status = 0;
            }

            $charityData[] = array(
                'id'=>$list->id,
                'title'=>$list->title,
                'begin'=>date('Y-m-d',$begin),
                'end'=>date('Y-m-d',$end),
                'status'=>$status

            );


        }

        echo json_encode(
            array(
                'status'=>200,
                'msg'=>'ok',
                'data'=>$charityData
            )
        );

        exit();
    }



    /*
    * 贵宾优享详情查询
    **/
    public function activityContent(Request $request){
        $id = $request->input('id');
        if(!is_numeric($id)){
            $this->throwERROE(500,'id违法');
        }

        $activityData = Activity::find($id);
        if($activityData == ''){
            $this->throwERROE(501,'id不存在');
        }

        $begin = strtotime($activityData->created_at);
        $end = strtotime($activityData->created_at) + $activityData->time*60*60*24;

        $now = time();
        if(($now - $begin)/60/60/24 < $activityData->time){
            $status = 1;
        }else{
            $status = 0;
        }

        $activityData->click+=1;
        $activityData->save();

        $contentData = array();

        $contentData = array(
            'title'=>$activityData->title,
            'content'=>$activityData->content,
            'begin'=>date('Y-m-d',$begin),
            'end'=>date('Y-m-d',$end),
            'time'=>$activityData->time,
            'status'=>$status,
            'top'=>$activityData->top
        );

        if($activityData->pic != ''){
            foreach($activityData->pic as $key=>$picture){
                if($picture->isbanner == 0){
                    $contentData['pic'][$key]['url'] = asset($picture->url);
                    $contentData['pic'][$key]['isBanner'] = $picture->isbanner;
                }
            }
        }


        echo json_encode(array(
            'status'=>200,
            'msg'=>'ok',
            'data'=>$contentData
        ));

        exit();

    }


    /*
    * 爱心捐赠详情
    **/
    public function charityContent(Request $requset){

        $id = $requset->input('id');

        if(!is_numeric($id)){
            $this->throwERROE(500,'id违法');
        }

        $charityData = Charity::find($id);
        if($charityData == ''){
            $this->throwERROE(501,'id不存在');
        }

        $begin = strtotime($charityData->created_at);
        $end = strtotime($charityData->created_at) + $charityData->time*60*60*24;

        $now = time();
        if(($now - $begin)/60/60/24 < $charityData->time){
            $status = 1;
        }else{
            $status = 0;
        }

        $charityData->click+=1;
        $charityData->save();

        $charityContent = array();
        $charityContent = array(
            'title'=>$charityData->title,
            'content'=>$charityData->content,
            'begin'=>date('Y-m-d',$begin),
            'end'=>date('Y-m-d',$end),
            'time'=>$charityData->time,
            'top'=>$charityData->top,
            'status'=>$status
        );

        if($charityData->pic != ''){
            foreach($charityData->pic as $key=>$picture){
                if($picture->isbanner == 0){
                    $charityContent['pic'][$key]['url'] = $picture->url;
                    $charityContent['pic'][$key]['isBanner'] = $picture->isbanner;
                }
            }
        }

        echo json_encode(array(
            'status'=>200,
            'msg'=>'',
            'data'=>$charityContent
        ));

        exit();
    }



    /*
     * 贵宾优享及爱心捐助删除
     **/
    public function acDelete(Request $requset){
        $option = $requset->option;
        $id = $requset->id;

        if(!is_numeric($id)){
            $this->throwERROE(501,'id违法');
        }

        if($option == 'activity'){
            $activity = Activity::find($id);
            if($activity == ''){
                $this->throwERROE(500,'id不存在');

            }

            if($activity->delete()){
                $this->show('ok');
            }

        }elseif($option == 'charity'){
            $charity = Charity::find($id);
            if($charity == ''){
                $this->throwERROE(500,'id不存在');
            }

            if($charity->delete()){
                $this->show('ok');
            }

        }else{
            $this->throwERROE(502,'操作不存在');
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







}