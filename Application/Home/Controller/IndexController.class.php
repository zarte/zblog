<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
      $Article = D('Article');
      $CateModel = D('Cate');
      $clist = $CateModel->getList();
      
      $cate_list = array();
      
      foreach ($clist as $v){
          $cate_list[$v['id']]=$v['cate_name'];
      }
      
      $condition = array();
      $condition['where']=array();
     
      $page_num = C('PAGE_NUM');
      $page = 0;
      if($_GET['page'])$page = intval($_GET['page']);
      $condition['limit'] = $page*$page_num.','.$page_num;
      $condition['order']   =  'id desc';
      $list = $Article->getList($condition);
     
   
    
      $this->assign('cate_list',$cate_list);
      $this->assign('list',$list);
      $this->display();
    }
    
    public function ajax_page() {
      $Article = D('Article');
      $condition = array();
      $condition['where']=array();
     
      $page_num = C('PAGE_NUM');
      $page = 0;
      if($_POST['page'])$page = intval($_POST['page'])-1;
      if($_POST['cate_id']){
          $condition['where']['cate_id'] = intval($_POST['cate_id']);
      }
      $condition['limit'] = $page*$page_num.','.$page_num;
      $condition['order']   =  'id desc';
      $list = $Article->getList($condition);
      foreach ($list as $k=>$v){
          $list[$k]['content'] = mb_substr(strip_tags(htmlspecialchars_decode($v['content'])),0,100);
      }
      
      echo json_encode(array('code'=>200,'data'=>$list));
     
    }
}