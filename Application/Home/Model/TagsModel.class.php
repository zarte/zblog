<?php
/**
 * 标签
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11 0011
 * Time: 10:00
 */
namespace Home\Model;
use Think\Model;


class TagsModel extends Model{

    public function getlistpage($page,$condition){

        return $this->where($condition['where'])->order($condition['order'])->page($page)->select();
    }
    public function getList($condition){
        return $this->where($condition['where'])->select();
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
    public function getOneByName($name){
        return $this->where('tag_name = "'.$name.'"')->find();
    }
}