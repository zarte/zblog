<?php
/**
 * 文章
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11 0011
 * Time: 10:00
 */
namespace Home\Model;
use Think\Model;


class CateModel extends Model{

    public function getonebyid($id){
        return $this->where('id = "'.$id.'"')->find();
    }

    public function getList(){
        return $this->select();
    }
   
    public function updateById($id,$data){
        return $this->data($data)->where('id = '.$id)->save();
    }
}