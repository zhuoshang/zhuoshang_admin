<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/4/13 下午7:11
*/

use App\DebtOrder;
use App\FrontUser;
use App\User;
use Illuminate\Http\Request;


class informController extends Controller{

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
                "bondId"=>$debt->did

            );
        }

        $informData = array(
            'tMsgCount'=>$orderCount,
            'tList'=>$orderData
        );

        var_dump($informData);
    }
}