<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/5/6 下午9:26
*/

use App\Activity;
use App\ActivityPic;
use App\Charity;
use App\Debt;
use App\DebtPic;
use App\DebtPro;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Auth;

class DebtController extends Controller
{

    /*
     * 债券（基金）添加
     **/
    public function debtAdd(Request $request){
        $bondName = $request->bondName;
        $netWorth = $request->netWorth;
        $bondValue = $request->bondValue;
        $interest = $request->interest;
        $content = $request->input('content');
        $pictures = $request->picture;
        $protecPic = $request->protecPic;
        $proContent = $request->proContent;
        $top = $request->top;
        $type = $request->type;
        $time = $request->time;

        if(!is_numeric($interest) || !is_numeric($bondValue) || !is_numeric($netWorth) || !is_numeric($time)){
            $this->throwERROE(500,'数据格式违法');
        }

        $typeArray = [1,2,3,4,5];
        if(!in_array($type,$typeArray)){
            $this->throwERROE(501,'该债券类型不存在');
        }

        $debt = new Debt();
        $debt->title = $bondName;
        $debt->type = $type;
        $debt->content = $content;
        $debt->net_value = $netWorth;
        $debt->interest = $interest;
        $debt->add_time = time();
        $debt->dates = $time;
        $debt->top = $top;

        if($debt->save()){
            $pictures = json_decode($pictures);
            foreach($pictures as $picture){
                $debtPic = DebtPic::find($picture);
                if($debtPic != ''){
                    $debtPic->did = $debt->id;
                    $debtPic->save();
                }
            }

            $protecPic = json_decode($protecPic);
            foreach($protecPic as $pic){
                $proPic = DebtPro::find($pic);
                if($proPic != ''){
                    $proPic->did = $debt->id;
                    $proPic->content = $proContent;
                    $proPic->save();
                }
            }

            $this->show('ok');

        }else{
            $this->show(505,'save_error');
        }



    }




}