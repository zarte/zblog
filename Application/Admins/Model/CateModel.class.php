<?php
/**
 * 文章
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11 0011
 * Time: 10:00
 */
namespace Admins\Model;
use Think\Model;


class CateModel extends Model{

    public function getonebyid($id){
        return $this->where('id = "'.$id.'"')->find();
    }

    public function getlistpage($page,$condition){

        return $this->where($condition['where'])->order($condition['order'])->page($page)->select();
    }
    public function getList(){
        return $this->select();
    }
    public function getcount($where){
        return $this->where($where)->count();

    }
    public function addone($data){
        return $this->data($data)->add();

    }
    public function updateById($id,$data){
        return $this->data($data)->where('id = '.$id)->save();
    }
    public function delOneBy($id) {
        return $this->where('id = "'.$id.'"')->delete();
    }
}