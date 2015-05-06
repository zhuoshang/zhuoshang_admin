<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/18 下午9:27
*/

use App\Activity;
use App\ActivityPic;
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
            $this->throwERROE(505,'数据为空或者格式非法');
        }

        $activity = new Activity();
        $activity->title = $title;
        $activity->content = $content;
        $activity->time = $time;

        if(!$activity->save()){
            $this->throwERROE(502,'database save error');
        }

        if($request->hasFile('picture') && $request->file('picture')->isValid()){
            $file = $request->file('picture');
            $filename = $file->getClientOriginalName();//获取初始文件名

            //获取文件类型并进行验证
            $filetype = $file->getMimeType();
            $typeArray = explode('/', $filetype, 2);
            if($typeArray['0'] != 'image'){
                $this->throwERROE(500,'数据格式非法');
            }

            $typeName =  $file->getClientOriginalExtension();//获取文件后缀名
            $aid = $activity->id;

            $newFileName = $this->fileNameMake($filename,$typeName);
            $directoryName = $aid%100;//根据用户id和100的模值，生成对应存储目录地址
            $savePath = public_path().'/uploads/activity/'.$directoryName.'/photo';

            $fileSave = $file -> move($savePath,$newFileName);
            if($fileSave){
                $pic = new ActivityPic();
                $pic->aid = $aid;
                $pic->url = asset('/uploads/activity/'.$directoryName.'/photo/'.$newFileName);
                if($pic->save()){
                    echo json_encode(
                        array(
                            'status'=>200,
                            'msg'=>'ok',
                            'data'=>''
                        )
                    );

                    exit();
                }
            }else{
                $this->throwERROE(504,'pic_save_error');
            }

        }

        if($title == '' || $content == '' || !is_numeric($time)){
            $this->throwERROE(501,'关键数据为空或者参数不合法');
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
    private function throwERROE($code,$msg){
        echo json_encode(array(
            'status'=>$code,
            'msg'=>$msg,
            'data'=>''
        ));

        exit();
    }



}