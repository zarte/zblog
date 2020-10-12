<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        //获取站点信息
        $webmodel = M('web');
        $webinfo = $webmodel->find();
        $this->assign('title',$webinfo['web_name']);
        $this->assign('webinfo',$webinfo);

      $Article = D('Article');
      $CateModel = D('Cate');
      $clist = $CateModel->getList();
      
      $cate_list = array();

      $condition = array();
      $condition['where']=array();

      $tag = $_GET['tag'];
      $Artandtagmodel = D("Articletags");
      if($tag){
          //获取关联文章id
          $artlist = $Artandtagmodel->getArticleByTag($tag);
          $artarr = array();
          foreach($artlist as $v){
              $artarr[] = $v['art_id'];
          }
          $condition['where']['id']=array('in',$artarr);
      }
      //获取标签
      $tags_list = $Artandtagmodel->getrandtags();
      $tagarr= array();
      foreach($tags_list as $v){
          $tagarr[] = $v['tag_id'];
      }
     if($tagarr){
         $TagsModel = D("Tags");
         $tagnamelist = $TagsModel->getList(array('where'=>array('id'=>array('in',$tagarr))));
         foreach($tags_list as $k=>$v){
             foreach($tagnamelist as $vv){
                 if($v['tag_id']==$vv['id']){
                     $tags_list[$k]['tag_name']=$vv['tag_name'];
                 }
             }
         }
     }

      $page_num = C('PAGE_NUM');
      $page = 0;
      if($_GET['page'])$page = intval($_GET['page']);
      $condition['limit'] = $page*$page_num.','.$page_num;
      $condition['order']   =  'id desc';
      $condition['where']['status']=1;
      $condition['where']['show']=1;

      $list = $Article->getList($condition);
    foreach ($list as $k=>$v){
        //$list[$k]['content'] = mb_substr(strip_tags(htmlspecialchars_decode($v['content'])),0,100);
        $list[$k]['content'] = $v['summarize'];
        $list[$k]['add_date'] = substr($v['add_date'],0,10);
    }

    
      //分类
      $this->assign('cate_list',$clist);
      $this->assign('list',$list);
      $this->assign('tags_list',$tags_list);
      $this->assign('tag',$tag);
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
      $tag = $_POST['tag_id'];
      if($tag){
          $Artandtagmodel = D("Articletags");
            //获取关联文章id
            $artlist = $Artandtagmodel->getArticleByTag($tag);
            $artarr = array();
            foreach($artlist as $v){
                $artarr[] = $v['art_id'];
            }
            $condition['where']['id']=array('in',$artarr);
      }

      $condition['limit'] = $page*$page_num.','.$page_num;
      $condition['order']   =  'id desc';
        $condition['where']['status']=1;
        $condition['where']['show']=1;
      $list = $Article->getList($condition);
      foreach ($list as $k=>$v){
         // $list[$k]['content'] = mb_substr(strip_tags(htmlspecialchars_decode($v['content'])),0,100);
          $list[$k]['content'] = $v['summarize'];
          $list[$k]['add_date'] = substr($v['add_date'],0,10);
      }
      
      echo json_encode(array('code'=>200,'data'=>$list));
     
    }
}