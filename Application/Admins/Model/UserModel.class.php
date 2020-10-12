<?php
namespace Admins\Model;
use Think\Model;
class UserModel extends Model {
    
    public function getOneByName($name){
        return $this->where("user_name='%s'",array($name))->find();
    }

    public function getOneById($id){
        return $this->where(array('id'=>$id))->find();
    }
    
}
