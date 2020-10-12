<?php
/**
 * 评论
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/11 0011
 * Time: 10:00
 */
namespace Home\Model;
use Think\Model;


class CommentModel extends Model{
    public function getList($condition){
        return $this->where($condition['where'])->order($condition['order'])->limit($condition['limit'])->select();
    }
    
    public function getcount($where){
        return $this->where($where)->count();
    
    }
    
    public function  getChildList($condition){
       $sql =' SELECT comment1.*,count(comment1.id)  from blog_comment as comment1 LEFT JOIN blog_comment as comment2 ON comment1.pid =  comment2.pid and comment1.id >= comment2.id';
        $sql .=' where '.$condition['where'];
        $sql .=' GROUP BY  comment1.pid,comment1.id HAVING count(comment1.id)<5';
        $sql .=' ORDER BY '.$condition['order'];
      // echo $sql;exit;
        $list=$this->query($sql);
       return $list;
    }
    
    
    
    public function getonebyid($id){
        return $this->where('id = "'.$id.'"')->find();
    }

    
   
    public function addone($data){
        return $this->data($data)->add();

    }
    public function updateById($id,$data){
        return $this->data($data)->where('id = '.$id)->save();
    }
}