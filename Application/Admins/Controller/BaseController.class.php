<?php
namespace Admins\Controller;
use Think\Controller;
class BaseController extends Controller {
   
    public function _initialize(){
      //  isset($_SESSION['is_login'])?'':$this->redirect('Login/index');
        if(!isset($_SESSION['is_login']) || !isset($_SESSION['user_id'])){
           // echo 'no login';
            $this->redirect('Login/index');
            exit;
        }
    }
}