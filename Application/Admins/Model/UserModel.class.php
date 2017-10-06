<?php
namespace Admins\Model;
use Think\Model;
class UserModel extends Model {
    
    public function get_one($name){
        return $this->where(array('user_name'=>$name))->find();
    }
    
}
