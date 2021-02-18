<?php
/**
 * 标签
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11 0011
 * Time: 10:00
 */
namespace Admins\Model;
use Think\Model;


class ArticletagsModel extends Model{

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
    public function delByArticleid($aid) {
        return $this->where('art_id = "'.$aid.'"')->delete();
    }
    public function getArticleTags($aid){
        return $this->where('art_id = "'.$aid.'"')->select();
    }
}