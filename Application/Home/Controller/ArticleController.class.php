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
          //$info['htmlcontent'] =$info['content'];
          //$info['content'] = htmlspecialchars_decode($info['content']);
          $info['fcontent'] = htmlspecialchars_decode($info['fcontent']);
          $articletagsmodel = D('Articletags');
          $tagslist = $articletagsmodel->getArticleTags($id);
          $webinfo = array();
          if($tagslist){
              $TagsModel = D('Tags');
              $tagarr = array();
              foreach($tagslist as $v){
                  $tagarr[]= $v['tag_id'];
              }
              $tagslist=$TagsModel->getList(array('where'=>array('id'=>array('in',$tagarr))));
              $info['tagslist']=$tagslist;
              foreach ($tagslist as $v){
                  $webinfo['web_keyword'] .=$v['tag_name'].',';
              }
              $webinfo['web_keyword'] = trim($webinfo['web_keyword'],',');
          }


          //获取上下两篇文章
          $silblings = $Article->getsilbings($id);
          $webinfo['web_desc'] = mb_substr($info['summarize'],0,20);
          //$webinfo['web_desc'] = mb_substr(str_replace(array("\r\n", "\r", "\n"," "), "",strip_tags(htmlspecialchars_decode($info['content']))),0,20);

          $webmodel = M('web');
          $swebinfo = $webmodel->find();
          $this->assign('webinfo',$webinfo);
          $this->assign('silblings',$silblings);
          $this->assign('title',$info['title'].'|'.$swebinfo['web_name']);
          $this->assign('data',$info);
          $this->display();
      }
    }
    
}