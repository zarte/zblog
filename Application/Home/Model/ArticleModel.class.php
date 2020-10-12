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


class ArticleModel extends Model{

    public function getonebyid($id){
        return $this->where('id = "'.$id.'"')->find();
    }

    public function getList($condition){
        
         $res=$this->where($condition['where'])->order($condition['order'])->limit($condition['limit'])->select();
        // echo $this->getlastsql();
         return $res;
    }
    
   
    public function updateById($id,$data){
        return $this->data($data)->where('id = '.$id)->save();
    }
    
    public function oneview($id) {
        return $this->where('id = "'.$id.'"')->setInc('click',1);
    }

    public function getsilbings($id){
        $res1 = $this->where(array('show'=>1,
            'status'=>1,
            'id'=>array('lt',$id)))->order(array('id'=>'desc'))->find();
        $res2 = $this->where(array('show'=>1,
            'status'=>1,
            'id'=>array('gt',$id)))->order(array('id'=>'asc'))->find();

        return array($res1,$res2);
    }
}