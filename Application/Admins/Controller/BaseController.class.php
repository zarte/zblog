<?php
namespace Admins\Controller;
use Think\Controller;
class BaseController extends Controller {
   
    public function _initialize(){
        isset($_SESSION['is_login'])?'':$this->redirect('Login/index');
    }
}