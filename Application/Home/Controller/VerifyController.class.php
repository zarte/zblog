<?php
namespace Home\Controller;
use Think\Controller;
class VerifyController extends Controller {
   
    public function verify1(){
        $this->get_verify('comment');
    }
    
    private function get_verify($type){
        $Verify = new \Think\Verify();
        $Verify->imageW = 120;
        $Verify->imageH = 30;
        $Verify->fontSize = 18;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->entry($type);
    }
    
}