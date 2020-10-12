<?php
namespace Admins\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
       if(isset($_COOKIE['autologin'])){
           $Crypt       = new \Think\Crypt('Crypt');
           $cookiesinfo=$Crypt->decrypt($_COOKIE['autologin'],'hby20180425');
           if($cookiesinfo){

               $_SESSION['is_login'] = 1;
               $_SESSION['user_id'] = $cookiesinfo;
               $this->redirect('Index/index');
           }
       }
        $this->display('login');
    }
    
    public function login() {

       $user = I('post.username');
       $passwd = I('post.password');
       $veriy = I('post.verify');
        $remember = I('post.remember');

       $verifyobj = new \Think\Verify();
       if(!$verifyobj->check($veriy, 'login')){
           echo '{"code":4,"msg":"验证码错误"}';
           exit();
       }
      
       $usermodel = D('User');
       $userinfo = $usermodel->getOneByName($user);
       if($userinfo['passwd']==md5($passwd)){
           $_SESSION['is_login'] = 1;
           $_SESSION['user_id'] = $userinfo['id'];
           if($remember=="true"){
               $Crypt       = new \Think\Crypt('Crypt');
               $cookiestr=$Crypt->encrypt($_SESSION['user_id'],'hby20180425',3600*24*7);
               setcookie('autologin',$cookiestr,time()+3600*24*7,'/',DOMAIN);
           }
           echo '{"code":200,"msg":"success"}';
       }else{
           echo '{"code":4,"msg":"登陆失败"}';
       }
    }

    public function logout(){
        $_SESSION['is_login'] = 0;
        $_SESSION['user_id'] = null;
        unset($_SESSION['is_login']);
        unset($_SESSION['user_id']);
        setcookie('autologin',null,time()-1,'/',DOMAIN);
        $this->redirect('Login/index');
    }
}