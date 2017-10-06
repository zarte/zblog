<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {
    public function detail(){
      
      $id = intval($_GET['id']);
      if($id){
          $Article = D('Article');
          //增加浏览量
          $Article->oneview($id);
          $info = $Article->getonebyid($id);
          $info['content'] = htmlspecialchars_decode($info['content']);
          $this->assign('title',$info['title']);
          $this->assign('data',$info);
          $this->display();
      }
    }
    
}