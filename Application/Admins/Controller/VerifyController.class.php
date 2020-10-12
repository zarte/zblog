<?php
namespace Admins\Controller;
use Think\Controller;
class VerifyController extends Controller {
    public function verify1(){
        $this->get_verify('login');
    
    }
    
    private function get_verify($type){
        $Verify = new \Think\Verify();

        $Verify->imageW = 80;
        $Verify->imageH = 30;
        $Verify->fontSize = 17;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->entry($type);
    }
}