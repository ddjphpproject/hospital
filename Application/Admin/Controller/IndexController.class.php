<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台首页控制器
 */
class IndexController extends AdminBaseController{
    /**
     * 首页
     */
    public function index(){
        // 分配菜单数据
        $pdid = $_SESSION['user']['id'];
        $gid = M('auth_group_access') -> field('group_id') -> where("uid='$pdid'") -> find();
        $gid = $gid['group_id'];
        $res = M('auth_group as g') -> join("yuyue_auth_group_access as a on g.id=a.group_id") -> field('g.id') -> where("a.uid='$pdid'") -> find();
        $useid = $res['id'];
        $name = $_SESSION['user']['username'];
        $nav_data=D('AdminNav')->getTreeData('level','order_number,id');
        /*阅读状态*/
        // $read = M('online_read') -> where("userid='$pdid' and state=0") -> count();
        // foreach ($nav_data as $key => $value) {
        //     if($value['name'] == '预约管理'){
        //         // echo 1;die;
        //         $nav_data[$key]['state'] = $read;
        //         foreach ($value['_data'] as $k => $v) {
        //             if($v['name'] == '网约分配'){

        //                 $nav_data[$key]['_data'][$k]['state'] = $read;
        //             }elseif($v['name'] == '网约查看' && $gid != 1 && $gid != 2){
        //                 $nav_data[$key]['_data'][$k]['state'] = $read;
        //             }
        //         }
        //     }
        // }
        // /*阅读状态*/
        // $avatar = M('users') -> field('avatar') -> where("username='$name'") -> select();
        $assign=array(
            'data'=>$nav_data,
            'avatar' => $avatar,
            'useid' => $useid,
            );
        $this->assign($assign);
        $this->display();
    }
    /**
     * elements
     */
    public function elements(){

        $this->display();
    }
    
    /**
     * welcome
     */
    public function welcome(){
        $zj=M("Zj")->select();
        $uid = $_SESSION['user']['id'];
        $arr = array('198','199','211','212','213','214','215','216','217','218','219','220','221','222','223');
        if(in_array($uid,$arr)){
        $zj = array(array('zjname'=>'林洪生'),array('zjname'=>'李勇'),array('zjname'=>'张英'),array('zjname'=>'刘杰'),array('zjname'=>'刘硕'),array('zjname'=>'李道睿'),array('zjname'=>'刘义涛'),array('zjname'=>'关念波'),array('zjname'=>'董倩'),array('zjname'=>'樊慧婷'),array('zjname'=>'赵志正'),array('zjname'=>'张玉人'),array('zjname'=>'王学谦'),array('zjname'=>'王硕'),array('zjname'=>'吕丽媛'));
        $assign=array(
            'data'=>$zj
            );
        $this->assign($assign);
        $this->display('lhstd');
        }else{
        $assign=array(
            'data'=>$zj
            );
        $this->assign($assign);
        $this->display();
        }
    }

    public function lineChart()
    {
        $this -> display();
    }
}
