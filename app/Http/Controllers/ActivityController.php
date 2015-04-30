<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/18 下午9:27
*/

use App\Activity;
use App\Charity;
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



        if($title == '' || $content == '' || !is_numeric($time)){
            $this->throwERROE(501,'关键数据为空或者参数不合法');
        }

        $activity = new Activity();
        $activity->title = $title;
        $activity->content = $content;
        $activity->time = $time;

        if($activity->save()){
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
     * 爱心捐助添加
     **/
    public function charityAdd(Request $request){

        $title = $request->input('title');
        $content = $request->input('content');
        $time = $request->input('time');

        if($title == '' || $content == '' || !is_numeric($time)){
            $this->throwERROE(501,'关键数据为空或者参数不合法');
        }

        $charity = new Charity();
        $charity->title = $title;
        $charity->content = $content;
        $charity->time = $time;

        if($charity->save()){
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