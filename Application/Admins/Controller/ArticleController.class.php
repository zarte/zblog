<?php
namespace Admins\Controller;
use Think\Controller;
class ArticleController extends BaseController {
    public function index(){
        $Articlemodel = D('Article');
        $page_num = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = $_POST['p']?$_POST['p']:0;
        $condition['page'] = $p.','.$page_num;
        $condition['where'] = '';
        $condition['where']['status']=1;
        $condition['order'] = 'id desc';
        $count      = $Articlemodel->getcount($condition['where']);
       // $Page       = new \Think\Page($count,$page_name);
        $list = $Articlemodel->getlistpage($condition['page'],$condition);
       // $show       = $Page->show2();
        
        $catemodel = D('Cate');
        $clist = $catemodel->getList();
        $cate_list = array();
        foreach ($clist as $v){
            $cate_list[$v['id']] = $v['cate_name'];
        }
        unset($clist);
        
        foreach ($list as $k=> $v){
            $list[$k]['cate_name'] = $cate_list[$v['cate_id']];
        }
        $pagenation=array(
            'total'=>$count,
            'page_num'=>$page_num,
            'p'=>$p,

        );
        if(isset($_POST['p'])){
            echo json_encode(array('code'=>200,'list'=>$list,'pagenation'=>$pagenation));
            exit();
        }
      //  $this->assign('cate_list',$cate_list);
        $this->assign('list',$list);
        $this->assign('pagenation',$pagenation);
        
        $this->display();
    }
    
    public function add() {
        $catemodel = D('Cate');
        $list = $catemodel->getList();
        $this->assign('cate_list',$list);
        $this->display('edit');
    }
    
    public function edit() {

        $catemodel = D('Cate');
        $list = $catemodel->getList();
        $info = array();
        if(!empty($_GET['id'])){
            $id = intval($_GET['id']);
            $Articlemodel = D('Article');
            $info = $Articlemodel->getonebyid($id);
        
            if($info){
                $info['content'] = htmlspecialchars_decode($info['content']);
                $tagsModel =  D('Tags');
                $ArticletagsModel =  D('Articletags');
                $tagslist = $ArticletagsModel->getArticleTags($id);
                $tagstr = '';
                if($tagslist){
                    $tagsarr = array();
                    foreach($tagslist as $v){
                        $tagsarr[] = $v['tag_id'];
                    }

                    $tagslist = $tagsModel->getList(array('where'=>array('id'=>array('in',$tagsarr))));
                    foreach($tagslist as $v){
                        $tagstr .=$v['tag_name'].',';
                    }
                    $tagstr = trim($tagstr,',');
                }
                $info['tags']=$tagstr;
            }
        }

        
        $this->assign('data',$info);
        $this->assign('cate_list',$list);
        $this->display('edit');
    }
    

    public function save(){
        $insertdata['title'] =$_POST['title'];
        $insertdata['content'] =htmlspecialchars($_POST['hbymarkdowncontent']);
        $insertdata['fcontent'] =htmlspecialchars($_POST['hbymarkdownfcontent']);
        $insertdata['cate_id'] =$_POST['cate_id'];
        $insertdata['summarize']  = mb_substr(str_replace(array("\r\n", "\r", "\n"," "), "",strip_tags($_POST['hbymarkdownfcontent'])),0,100);;

        if(empty($insertdata['title'])){
            echo '{"code":4,"msg":"请输入标题"}';
            exit;
        }
        $tags = $_POST['tags'];


        $Article = D('Article');
        if($_POST['id']){
            $insertdata['update_date'] =date('Y-m-d H:i:s');
            $id = intval($_POST['id']);
            $res = $Article->updateById($id,$insertdata);
        }else{
            $insertdata['add_date'] =date('Y-m-d H:i:s');
            $res = $Article->addone($insertdata);
        }
        
        if($res){
            //标签
            if($tags){
                $tagsModel =  D('Tags');
                $ArticletagsModel =  D('Articletags');
                if($id)$res = $id;
                $ArticletagsModel->delByArticleid($res);
                $tagslist = explode(',',$tags);
                foreach($tagslist as $v){
                    if(empty($v))continue;
                    $tagid = 0;
                    if($tmptaginfo = $tagsModel->getOneByName($v)){
                        $tagid=$tmptaginfo['id'];
                    }else{
                        $tagid=$tagsModel->addone(array(
                            'tag_name'=>$v
                        ));
                    }
                    if($tagid>0){
                        $ArticletagsModel->addOne(array(
                            'art_id'=>$res,
                            'tag_id'=>$tagid
                        ));
                    }
                }
            }
            echo '{"code":200}';
        }else{
            echo '{"code":4}';
        }
    }
    
    public function del() {
        $Article = D('Article');
        if(!empty($_GET['id'])){
            $id = intval($_GET['id']);
           /// $res = $Article->delOneBy($id);
          $res = $Article->updateById($id,array('status'=>0));
            if($res){
                echo  json_encode(array('code'=>200,'msg'=>"success"));
                exit;
            }
        }
        echo  json_encode(array('code'=>4,'msg'=>"删除失败"));
        exit;
    }

    public function show() {
        $Article = D('Article');
        if(!empty($_GET['id'])){
            $updata['show'] = $_GET['show']>0?1:0;
            $id = intval($_GET['id']);
            $res = $Article->updateById($id,$updata);
            if($res){
                echo  json_encode(array('code'=>200,'msg'=>"success"));
                exit;
            }
        }
        echo  json_encode(array('code'=>4,'msg'=>"状态改变失败"));
        exit;
    }

    public function tagslist(){
        $Tagsmodel = D('Tags');
        $page_num = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = $_POST['p']?$_POST['p']:0;
        $condition['page'] = $p.','.$page_num;
        $condition['where'] = '';
        $condition['order'] = 'id desc';
        $count      = $Tagsmodel->getcount($condition['where']);
        $list = $Tagsmodel->getlistpage($condition['page'],$condition);

        $pagenation=array(
            'total'=>$count,
            'page_num'=>$page_num,
            'p'=>$p,

        );
        if(isset($_POST['p'])){
            echo json_encode(array('code'=>200,'list'=>$list,'pagenation'=>$pagenation));
            exit();
        }
        //  $this->assign('cate_list',$cate_list);
        $this->assign('list',$list);
        $this->assign('pagenation',$pagenation);

        $this->display();
    }

    public function ueditor(){
    
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./public/plugin/ueditor/config.json")), true);
        $action = $_GET['action'];
    
        switch ($action) {
            case 'config':
                $result =  json_encode($CONFIG);
                break;
    
                /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = include("action_upload.php");
                break;
    
                /* 列出图片 */
            case 'listimage':
                $result = include("action_list.php");
                break;
                /* 列出文件 */
            case 'listfile':
                $result = include("action_list.php");
                break;
    
                /* 抓取远程文件 */
            case 'catchimage':
                $result = include("action_crawler.php");
                break;
    
            default:
                $result = json_encode(array(
                'state'=> '请求地址出错'
                    ));
                break;
        }
    
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}