<?php
namespace Admins\Controller;
use Think\Controller;
class CommentController extends BaseController {
    public function index(){
        $CommentModel = D('Comment');
        $page_name = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = $_GET['p']?$_GET['p']:0;
        $condition['page'] = $p.','.$page_name;
        $condition['where'] = '';
        $condition['order'] = 'id desc';
        $count      = $CommentModel->getcount($condition['where']);
        $Page       = new \Think\Page($count,$page_name);
        $list = $CommentModel->getlistpage($condition['page'],$condition);
        $show       = $Page->show2();
        if(isset($_GET['p'])){
            echo json_encode(array('list'=>$list,'page'=>$show));
            exit();
        }
        
        $this->assign('list',$list);
        $this->assign('page',$show);
        
        $this->display();
    }
    

   
    
    public function del() {
        $Comment = D('Comment');
        if(!empty($_POST['id'])){
            $id = intval($_POST['id']);
            $res = $Comment->delOneBy($id);
            if($res){
                echo  json_encode(array('code'=>200,'msg'=>"success"));
                exit;
            }
        }
        echo  json_encode(array('code'=>4,'msg'=>"删除失败"));
        exit;
    }

}