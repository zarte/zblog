<?php
namespace Home\Controller;
use Think\Controller;
class CommentController extends Controller {
    public function add(){
        
    if(empty($_POST['username']) || empty($_POST['comment']) || empty($_POST['verify'])){
            echo json_encode(array('code'=>4,'msg'=>'内容缺失'));
            exit();
        }
        
        $username=$_POST['username'];
       // $comment=addslashes(htmlspecialchars($_POST['comment']));
       $comment=addslashes(strip_tags($_POST['comment']));
        $comment = preg_replace("/([\s]{2,})/","\\1",$comment); 
        
        
        $verify=$_POST['verify'];
        $email=$_POST['email'];
        $pid=intval($_POST['pid']);
        $aid=intval($_POST['aid']);
        
        $verifyobj = new \Think\Verify();
        if(!$verifyobj->check($verify, 'comment')){
            echo '{"code":4,"msg":"验证码错误"}';
            exit();
        }
        
        if(strlen($comment)<6 || strlen($comment>1024)){
            echo '{"code":4,"msg":"评论长度需大于6个字符小于1024个字符"}';
            exit();
        }
        
        $reg = '/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]{2,16}$/u';
        if(!preg_match($reg,$username)){
            echo '{"code":4,"msg":"用户名格式不符"}';
            exit();
        }
        
        if($email){
            $reg = '/^([0-9A-Za-z_\.\-]+)@[0-9a-z]+\.[a-z]{2,3}$/';
            if(!preg_match($reg,$email)){
                echo '{"code":4,"msg":"邮箱格式不符"}';
                exit();
            }
        }
       
      
       $Commentmodel = D('Comment');
      $res = $Commentmodel->addone(array(
          'user_name'=>$username,
          'content'=>$comment,
          'pid'=>$pid,
          'aid'=>$aid,
          'add_time'=>date('Y-m-d H:i:s'),
          'email'=>$email
      ));
       if($res){
           echo '{"code":200,"msg":"success","res":'.$res.'}';
       }else{
           echo '{"code":4,"msg":"失败"}';
       }
    }
    function commentlist(){
        if(empty($_POST['id'])){
            exit;
        }
        $Comment = D('Comment');
        $condition = array();
        $condition['where']=array();
         
        $page_num = C('PAGE_NUM');
        $page = 0;
        if($_POST['page'])$page = intval($_POST['page'])-1;
        
        $condition['where']['aid'] = intval($_POST['id']);
        $condition['where']['pid'] = 0;
        
        $condition['limit'] = $page*$page_num.','.$page_num;
        $condition['order']   =  'id desc';
        $list = $Comment->getList($condition);
        $pidarr = array();
        foreach ($list as $k=>$v){
            $pidarr[] = $v['id'];
        }
       
        
       // var_dump($clist);exit;
        if($pidarr){
            //获取子评论
            $condition = array();
            $condition['where'] ='comment1.aid = '.intval($_POST['id']).' and comment1.pid in ('.implode(',', $pidarr).')';
            $condition['order'] ='comment1.id asc';
            $clist = $Comment->getChildList($condition);
            
            $child_arr = array();
            foreach ($clist as $kk=>$vv){
                $vv['gavatar']=$this->getavatar($vv['email']);
                $child_arr[$vv['pid']][] = $vv;
            }
            foreach ($list as $k=>$v){
                $list[$k]['gavatar'] =$this->getavatar($v['email']);
               if($child_arr[$v['id']]){
                   $list[$k]['children']=$child_arr[$v['id']];
               }
            }
        }
        
        echo json_encode(array('code'=>200,'data'=>$list));
    }
    
    public function  morechild(){
        if(empty($_POST['aid'])){
            exit;
        }
        $Comment = D('Comment');
        $condition = array();
        $condition['where']=array();
         
        $page_num = 4;
        $page = 0;
        if($_POST['page'])$page = intval($_POST['page'])-1;
        
        $condition['where']['aid'] = intval($_POST['aid']);
        $condition['where']['pid'] = intval($_POST['pid']);
        
        $condition['limit'] = $page*$page_num.','.$page_num;
        $condition['order']   =  'id asc';
        $list = $Comment->getList($condition);
      
        echo json_encode(array('code'=>200,'data'=>$list));
    }

    private function getavatar($email){
       $default = "http://cn.gravatar.com/avatar/b84fae8f33812643b6f3543253c961f8";

        $size = 80;
        $grav_url = "https://cn.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;

        return $grav_url;
    }

}