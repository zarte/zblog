<?php
namespace Admins\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
      
        $this->display('login');
    }
    
    public function login() {
       $user = I('post.username');
       $passwd = I('post.password');
       $veriy = I('post.verify');
       $verifyobj = new \Think\Verify();
       if(!$verifyobj->check($veriy, 'login')){
           echo '{"code":4,"msg":"验证码错误"}';
           exit();
       }
      
       $usermodel = D('User');
       $userinfo = $usermodel->get_one($user);
       if($userinfo['passwd']==md5($passwd)){
           $_SESSION['is_login'] = 1;
           $_SESSION['user_id'] = $userinfo['id'];
           echo '{"code":200,"msg":"success"}';
       }else{
           echo '{"code":4,"msg":"登陆失败"}';
       }
    }
}