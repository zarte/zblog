<?php
namespace Admins\Controller;
use Think\Controller;
class CommentController extends BaseController {
    public function index(){
        $CommentModel = D('Comment');
        $page_num = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = $_POST['p']?$_POST['p']:0;
        $condition['page'] = $p.','.$page_num;
        $condition['where'] = '';
        $condition['order'] = 'id desc';
        $count      = $CommentModel->getcount($condition['where']);
       // $Page       = new \Think\Page($count,$page_num);
        $list = $CommentModel->getlistpage($condition['page'],$condition);
       // $show       = $Page->show2();

        $pagenation=array(
            'total'=>$count,
            'page_num'=>$page_num,
            'p'=>$p,

        );

        if(isset($_POST['p'])){
            echo json_encode(array('code'=>200,'list'=>$list,'pagenation'=>$pagenation));
            exit();
        }
        
        $this->assign('list',$list);
        $this->assign('pagenation',$pagenation);
        
        $this->display();
    }
    

   
    
    public function del() {
        $Comment = D('Comment');

        if(!empty($_GET['id'])){
            $id = intval($_GET['id']);
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