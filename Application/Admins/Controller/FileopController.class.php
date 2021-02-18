<?php
namespace Admins\Controller;
use Think\Controller;
class FileopController extends BaseController {
    public function commonupload(){
          $path = C('upload_path');
          if(empty($path)){
              echo json_encode(array('code'=>4,'msg'=>'路径未设置s'));
              exit;
          }
        $pathinfo = pathinfo($_FILES['file']['name']);
        $file['extension'] = $pathinfo['extension'];
        if(in_array($file['extension'],array('php','js'))){
            echo json_encode(array('code'=>4,'msg'=>'文件类型不符'));
            exit;
        }

        $file['savepath'] = $path.'upload/common/';
        if(1){
            $dir = date('Y/m/');
        }
        $saveName = time().rand(10000,99999).'.'.$file['extension'];
        $file['savename'] = $dir.$saveName;//取得文件名

        //创建目录
        if (is_dir($file['savepath'].$dir)) {
            if (!is_writable($file['savepath'].$dir)) {
                echo json_encode(array('code'=>4,'msg'=>'上传目录不可写'));
                exit;
            }
        } else {
            if (!mkdir($file['savepath'].$dir, 0755, true)) {
                echo json_encode(array('code'=>4,'msg'=>'上传目录不可写'));
                exit;
            }
        }
        if(move_uploaded_file($_FILES["file"]["tmp_name"], ($file['savepath'].$file['savename']))){
            echo json_encode(array('code'=>200,'msg'=>'success','filename'=>$file['savename']));
        }else{
            echo json_encode(array('code'=>4,'msg'=>'error'));
        }

    }
}