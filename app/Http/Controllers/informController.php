<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/13 下午7:11
*/

use App\Debt;
use App\DebtBuy;
use App\DebtOrder;
use App\FrontUser;
use App\User;
use Illuminate\Http\Request;


class informController extends Controller{


    /*
     * 待处理通知获取
     * */
    public function informGet(){
        $debt_orders = DebtOrder::where('verify','=',0)->get();

        $orderCount = $debt_orders->count();

        $informData = array();

        $orderData = array();
        foreach($debt_orders as $debt){

            $orderData[] = array(
                "userName"=>$debt->user->real_name,
                "phoneNum"=>$debt->user->mobile,
                "bondName"=>$debt->debt->title,
                "bondId"=>$debt->id,
                "bondId"=>$debt->did

            );
        }

        $informData = array(
            'tMsgCount'=>$orderCount,
            'tList'=>$orderData
        );

       $this->show('ok',$informData);
    }


    /*
     * 预约请求处理
     * */
    public function orderCheck(Request $request){
        $option = $request->option;

        switch($option){
            case 'debt':
                $order_id = $request->id;

                $data = [$order_id];
                if($this->dataCheck($data)){
                    //查询目标基金(债券)并扣除余额
                    $order = DebtOrder::find($order_id);
                    if($order == ''){
                        $this->throwERROE(500,'预约信息不存在');
                    }elseif($order->verify == 1){
                        $this->throwERROE(502,'该预约信息已经确认，请不要重复确认');
                    }

                    $money = $order->sum;
                    $uid = $order->uid;

                    $did = $order->did;
                    $debt = Debt::find($did);
                    if($debt->balance<$money){
                        $this->throwERROE(503,'该基金余额不足,无法进行操作');
                    }
                    $debt->balance = $debt->total - $money;
                    $debt->save();

                    //生成新的用户资产
                    $buy = new DebtBuy();
                    $buy->uid = $uid;
                    $buy->did = $debt->id;
                    $buy->add_time = time();
                    $buy->dates = $debt->dates;
                    $buy->total_buy = $money;
                    $buy->buy_month = date('m',time());
                    $buy->buy_year = date('Y',time());

                    //确认预约成功
                    $order->verify = 1;

                    if($buy->save() && $order->save()){
                        $this->show('ok');
                    }

                }
        }
    }



    /*
     * 参数格式验证
     * */
    private function dataCheck($data=array()){

        foreach($data as $value){
            if(!is_numeric($value)){
                $this->throwERROE(501,'参数格式非法');
            }
        }

        return true;
    }
}