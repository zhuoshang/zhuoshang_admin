<?php namespace App\Http\Controllers;
/*
**Author:tianling
**createTime:15/5/6 下午10:17
*/

use App\Activity;
use App\ActivityPic;
use App\Charity;
use App\CharityPic;
use App\DebtPic;
use App\DebtPro;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Auth;

class PicController extends Controller{

    /*
     * 图片上传
     **/
    public function picUpload(Request $request){
        $option = $request->option;

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
            $newFileName = $this->fileNameMake($filename,$typeName);//生成新文件名

            $this->fileSave($file,$newFileName,$option);


        }
    }



    /*
     * 图片删除
     **/
    public function picDelete(Request $request){
        $id = $request->id;
        $option = $request->option;

        if(!is_numeric($id)){
            $this->throwERROE(500,'id格式违法');
        }

        switch($option){
            case 'activity':
                $picture = ActivityPic::find($id);
                if($picture == ''){
                    $this->throwERROE(501,'图片不存在');
                }

                $url = $picture->absolute_url;

                if(!file_exists($url)){
                    $this->throwERROE(502,'文件不存在');
                }

                if(unlink($url) && $picture->delete()){
                    $this->show('ok');
                }

                break;

            case 'charity':
                $picture = CharityPic::find($id);
                if($picture == ''){
                    $this->throwERROE(501,'图片不存在');
                }

                $url = $picture->absolute_url;

                if(!file_exists($url)){
                    $this->throwERROE(502,'文件不存在');
                }

                if(unlink($url) && $picture->delete()){
                    $this->show('ok');
                }

                break;

            case 'debt':
                $picture = DebtPic::find($id);
                if($picture == ''){
                    $this->throwERROE(501,'图片不存在');
                }

                $url = $picture->absolute_url;

                if(!file_exists($url)){
                    $this->throwERROE(502,'文件不存在');
                }

                if(unlink($url) && $picture->delete()){
                    $this->show('ok');
                }

                break;

            case 'debtPro':
                $picture = DebtPro::find($id);
                if($picture == ''){
                    $this->throwERROE(501,'图片不存在');
                }

                $url = $picture->absolute_url;

                if(!file_exists($url)){
                    $this->throwERROE(502,'文件不存在');
                }

                if(unlink($url) && $picture->delete()){
                    $this->show('ok');
                }

                break;

            default:
                $this->throwERROE(505,'操作不存在');
        }
    }



    /*
     * 文件保存
     **/
    private function fileSave($file,$newFileName,$option){

        $directoryName = date('Y-m',time());//根据用户id和100的模值，生成对应存储目录地址
        $savePath = public_path().'/uploads/'.$option.'/'.$directoryName.'/photo';

        $fileSave = $file -> move($savePath,$newFileName);
        if($fileSave){
            switch($option){
                case 'activity':
                    $pic = new ActivityPic();
                    break;
                case 'charity':
                    $pic = new CharityPic();
                    break;
                case 'debt':
                    $pic = new DebtPic();
                    break;

                case  'debtPro':
                    $pic = new DebtPro();
                    break;

                default:
                    $this->throwERROE(505,'操作不存在');

            }


            $pic->url = asset('/uploads/'.$option.'/'.$directoryName.'/photo/'.$newFileName);
            $pic->absolute_url = $savePath.'/'.$newFileName;
            if($pic->save()){
                echo '<script>var parent = window.parent;parent.iframeCallback('.json_encode(
                    array(
                        'status'=>200,
                        'msg'=>'ok',
                        'data'=>array(
                            'id'=>$pic->id,
                            'url'=>$pic->url
                        )
                    )
                ).');</script>';

                exit();
            }
        }else{
            $this->throwERROE(504,'pic_save_error');
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