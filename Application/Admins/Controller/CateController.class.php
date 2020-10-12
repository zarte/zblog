<?php
namespace Admins\Controller;
use Think\Controller;
class CateController extends BaseController {
    public function index(){
        $Catemodel = D('Cate');
        $page_num = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = $_POST['p']?$_POST['p']:0;
        $condition['page'] = $p.','.$page_num;
        $condition['where'] = '';
        $condition['order'] = 'id desc';
        $count      = $Catemodel->getcount($condition['where']);
       // $Page       = new \Think\Page($count,$page_name);
        $list = $Catemodel->getlistpage($condition['page'],$condition);
        //$show       = $Page->show2();

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

    public function add(){
        $this->display('edit');
    }

    public function edit() {
        $catemodel = D('Cate');
        $info = array();
        if(!empty($_GET['id'])){
            $id = intval($_GET['id']);
            $info = $catemodel->getonebyid($id);
        }else{
            echo 'error';
        }
        $this->assign('data',$info);
        $this->display('edit');
    }
    
    public function del() {
        $catemodel = D('Cate');
        if(!empty($_GET['id'])){
            $id = intval($_GET['id']);
            $res = $catemodel->delOneBy($id);
            if($res){
                echo  json_encode(array('code'=>200,'msg'=>"success"));
                exit;
            }
        }
        echo  json_encode(array('code'=>4,'msg'=>"删除失败"));
        exit;
    }

    public function save(){
       
        $insertdata['cate_name'] =$_POST['cate_name'];
        $insertdata['order'] =empty($_POST['order'])?0:$_POST['order'];

        if(empty($insertdata['cate_name'])){
            echo '{"code":4,"msg":"请输入分类名"}';
            exit;
        }
        $Cate = D('Cate');
        if($_POST['id']){
            $id = intval($_POST['id']);
            $res = $Cate->updateById($id,$insertdata);
        }else{
            $res = $Cate->addone($insertdata);
        }
        
        
        if($res){
            echo '{"code":200}';
        }else{
            echo '{"code":4}';
        }
    }
    
}