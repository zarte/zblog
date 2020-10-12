<?php
namespace Admins\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){

        $Usermodel = D('User');
        $userinfo = $Usermodel->getOneById($_SESSION['user_id']);
        if(!$userinfo){
            echo '用户不存在';
            exit;
        }
        $this->assign('userinfo',$userinfo);
        $this->display();
    }
    public function setting(){
        $webmodel = M('web');
        $webinfo = $webmodel->find();
        $this->assign('webinfo',$webinfo);
        $this->display();
    }

    public function saveset(){
        $update = array();
        $update['web_name'] = trim($_POST['title']);
        $update['web_desc'] = trim($_POST['content']);
        if($_POST['webstatus']){
            $update['status'] = 1;
        }else{
            $update['status'] = 0;
        }

        $webmodel = M('web');
       $res =  $webmodel->where('id = 1')->save($update);
       if($res!==false){
           echo json_encode(array('code'=>200,'msg'=>'保存成功'));
       }else{
           echo json_encode(array('code'=>4,'msg'=>'保存失败'));
       }

    }

    public function system(){
        if(strpos(PHP_OS,"WINNT")!==false){
            $info = new SystemInfoWindows();
            $cpu = $info->getCpuUsage();
            $memory = $info->getMemoryUsage();

        }else if(strpos(PHP_OS,"Linux")!==false){
            $fp = popen('top -b -n 2 | grep -E "^(Cpu|Mem|Tasks)"',"r");//获取某一时刻系统cpu和内存使用情况
            $rs = "";
            while(!feof($fp)){
                $rs .= fread($fp,1024);
            }
            pclose($fp);
            $sys_info = explode("\n",$rs);

            $tast_info = explode(",",$sys_info[3]);//进程 数组
            $cpu_info = explode(",",$sys_info[4]); //CPU占有量 数组
            $mem_info = explode(",",$sys_info[5]); //内存占有量 数组

//正在运行的进程数
            $tast_running = trim(trim($tast_info[1],'running'));

//CPU占有量
            $cpu_usage = trim(trim($cpu_info[0],'Cpu(s): '),'%us'); //百分比

//内存占有量
            $mem_total = trim(trim($mem_info[0],'Mem: '),'k total');
            $mem_used = trim($mem_info[1],'k used');
            $mem_usage = round(100*intval($mem_used)/intval($mem_total),2); //百分比

            /*硬盘使用率 begin*/
            $fp = popen('df -lh | grep -E "^(/)"',"r");
            $rs = fread($fp,1024);
            pclose($fp);
            $rs = preg_replace("/\s{2,}/",' ',$rs); //把多个空格换成 “_”
            $hd = explode(" ",$rs);
            $hd_avail = trim($hd[3],'G'); //磁盘可用空间大小 单位G
            $hd_usage = trim($hd[4],'%'); //挂载点 百分比
//print_r($hd);
            /*硬盘使用率 end*/
            $memory= array('cpu_usage'=>$cpu_usage,'mem_usage'=>$mem_usage,'hd_avail'=>$hd_avail,'hd_usage'=>$hd_usage,'tast_running'=>$tast_running);

        }else{
            echo '未知系统'.PHP_OS;
        }

        if($_POST['ajax']){
            echo json_encode(array(
                'code'=>200,
                'cpu'=>$cpu,
                'memory'=>$memory
            ));
            exit;
        }
        $this->assign('cpu',$cpu);
        $this->assign('memory',$memory);
        $this->display();
    }


    public function createsitemap(){
        //获取所有文章
        $page_num = C('PAGE_NUM');
        // 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
        $p = 1;

        $condition['page'] = $p.','.$page_num;
        $condition['where'] = '';
        $condition['where']['status']=1;
        $condition['where']['show']=1;
        $condition['order'] = 'id desc';

        $list = array();
        $Articlemodel = D('Article');
        $count      = $Articlemodel->getcount($condition['where']);
        $totalpage = ceil($count/$page_num);
        for($i=1;$i<=$totalpage;$i++){
            $condition['page'] = $i.','.$page_num;
            $clist = $Articlemodel->getlistpage($condition['page'],$condition);
            $list = array_merge($list,$clist);
        }
        $res = $this->createsitexml($list,'https://'.DOMAIN.'/');
        $res2 = $this->createsitehtml($list,'https://'.DOMAIN.'/');
        if($res && $res2){
            echo '{"code":200}';
        }else{
            echo '{"code":4}';
        }


    }

    private function createsitexml($list,$domain){
        $datestr = date(DATE_W3C);
        $str ='';
        $str .=  '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
        $str .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
        $str .= '<url>'."\r\n";
        $str .= '<loc>'.$domain.'</loc>'."\r\n";
        $str .= '<lastmod>'.$datestr.'</lastmod>'."\r\n";
        $str .= '<changefreq>weekly</changefreq>'."\r\n";
        $str .= ' <priority>1.0</priority>'."\r\n";
        $str .= '</url>'."\r\n";

        foreach ($list as $v){
            $str .= '<url>'."\r\n";
            $str .= '<loc>'.$domain.'index.php/Home/Article/detail.html?id='.$v['id'].'</loc>'."\r\n";
            $str .= '<lastmod>'.$datestr.'</lastmod>'."\r\n";
            $str .= '<changefreq>monthly</changefreq>'."\r\n";
            $str .= ' <priority>0.5</priority>'."\r\n";
            $str .= '</url>'."\r\n";
        }

        $str .='</urlset>';
        $res = file_put_contents('./sitemap.xml',$str);
        if($res){
            return true;
        }else{
            return false;
        }
    }

    private function createsitehtml($list,$domain){
        $str ='';
        $str .=  '<!DOCTYPE>'."\r\n";
        $str .=  '<html>'."\r\n";
        $str .=  '<head>'."\r\n";
        $str .=  '<title>站点地图</title>'."\r\n";
        $str .=  '<meta http-equiv="Content-Type" content="text/html;charset=utf-8">'."\r\n";

        $str .=  '</head>'."\r\n";
        $str .=  '<body>'."\r\n";
        $str .=  '<div><ul>'."\r\n";
        $str .=  '<li><a href="'.$domain.'">首页</a></li>'."\r\n";

        foreach ($list as $v){
            $str .=  '<li><a href="'.$domain.'index.php/Home/Article/detail.html?id='.$v['id'].'">'.$v['title'].'</a></li>'."\r\n";
        }

        $str .='</ul></div></body>';
        $res = file_put_contents('./sitemap.html',$str);
        if($res){
            return true;
        }else{
            return false;
        }
    }
}

class SystemInfoWindows
{
    /**
     * 判断指定路径下指定文件是否存在，如不存在则创建
     * @param string $fileName 文件名
     * @param string $content 文件内容
     * @return string 返回文件路径
     */
    private function getFilePath($fileName, $content)
    {
        $path = dirname(__FILE__) . "\\$fileName";
        if (!file_exists($path)) {
            file_put_contents($path, $content);
        }
        return $path;
    }
    /**
     * 获得cpu使用率vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getCupUsageVbsPath()
    {
        return $this->getFilePath(
            'cpu_usage.vbs',
            "On Error Resume Next
    Set objProc = GetObject(\"winmgmts:\\\\.\\root\cimv2:win32_processor='cpu0'\")
    WScript.Echo(objProc.LoadPercentage)"
        );
    }
    /**
     * 获得总内存及可用物理内存JSON vbs文件生成函数
     * @return string 返回vbs文件路径
     */
    private function getMemoryUsageVbsPath()
    {
        return $this->getFilePath(
            'memory_usage.vbs',
            "On Error Resume Next
    Set objWMI = GetObject(\"winmgmts:\\\\.\\root\cimv2\")
    Set colOS = objWMI.InstancesOf(\"Win32_OperatingSystem\")
    For Each objOS in colOS
     Wscript.Echo(\"{\"\"TotalVisibleMemorySize\"\":\" & objOS.TotalVisibleMemorySize & \",\"\"FreePhysicalMemory\"\":\" & objOS.FreePhysicalMemory & \"}\")
    Next"
        );
    }
    /**
     * 获得CPU使用率
     * @return Number
     */
    public function getCpuUsage()
    {
        $path = $this->getCupUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        return $usage[0];
    }
    /**
     * 获得内存使用率数组
     * @return array
     */
    public function getMemoryUsage()
    {
        $path = $this->getMemoryUsageVbsPath();
        exec("cscript -nologo $path", $usage);
        $memory = json_decode($usage[0], true);
        $memory['usage'] = Round((($memory['TotalVisibleMemorySize'] - $memory['FreePhysicalMemory']) / $memory['TotalVisibleMemorySize']) * 100);
        return $memory;
    }
}