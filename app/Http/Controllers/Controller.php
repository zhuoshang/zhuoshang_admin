<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    public function __construct()
    {
        date_default_timezone_set('PRC');
    }


    /*
    * 抛错函数
    **/
    protected  function throwERROE($code,$msg){
        echo json_encode(array(
            'status'=>$code,
            'msg'=>$msg,
            'data'=>''
        ));

        exit();
    }


    /*
     * 反馈函数
     **/
    protected function show($msg,$data=''){
        echo json_encode(array(
            'status'=>200,
            'msg'=>$msg,
            'data'=>$data
        ));

        exit();
    }

}
