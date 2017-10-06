<?php
namespace Admins\Controller;
use Think\Controller;
class ArticleController extends BaseController {
    public function index(){
        $Articlemodel = D('Article');
        $page_name = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = $_GET['p']?$_GET['p']:0;
        $condition['page'] = $p.','.$page_name;
        $condition['where'] = '';
        $condition['order'] = 'id desc';
        $count      = $Articlemodel->getcount($condition['where']);
        $Page       = new \Think\Page($count,$page_name);
        $list = $Articlemodel->getlistpage($condition['page'],$condition);
        $show       = $Page->show2();
        
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
        if(isset($_GET['p'])){
            echo json_encode(array('list'=>$list,'page'=>$show));
            exit();
        }
        $this->assign('cate_list',$cate_list);
        $this->assign('list',$list);
        $this->assign('page',$show);
        
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
            }
        }

        
        $this->assign('data',$info);
        $this->assign('cate_list',$list);
        $this->display('edit');
    }
    

    public function save(){
       
        $insertdata['title'] =$_POST['title'];
        $insertdata['content'] =htmlspecialchars($_POST['content']);
        $insertdata['cate_id'] =$_POST['cate_id'];
       
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
            echo '{"code":200}';
        }else{
            echo '{"code":4}';
        }
    }
    
    public function del() {
        $Article = D('Article');
        if(!empty($_POST['id'])){
            $id = intval($_POST['id']);
            $res = $Article->delOneBy($id);
            if($res){
                echo  json_encode(array('code'=>200,'msg'=>"success"));
                exit;
            }
        }
        echo  json_encode(array('code'=>4,'msg'=>"删除失败"));
        exit;
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