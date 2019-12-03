<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
use Aliyun\Core\Config;

use Aliyun\Core\Profile\DefaultProfile;

use Aliyun\Core\DefaultAcsClient;

use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
/**
 * 活动管理控制器
 */
class ActivityController extends AdminBaseController
{
    public function list_activity()
    {

        $data = I('post.');

        $title = $data['title'];

        // $m = M('Activity as a');
        // $where = "recycle=0 AND huawu<>88";
        // $p = getpage($m,$where,10);
        // $assign = array('data' => $result,'area' => $area,'expert'=>$expert,'govern'=>$govern,'entity'=>$entity,'channel'=>$channel,'source'=>$source);
        // $this->page=$p->show();
        // $this -> assign($assign);
        if(!empty($get['page_shu'])){
            $page_shu = $get['page_shu'];
        }

        $m = M('Activity');
        $where['is_del'] = 0;

        if(!empty($title)){
          $where['name'] = array('like','%'.$title.'%');
        }

//        dump($where);

        $class_list=M("Activity")->where($where)->select();
        $p = getpage($m, $where, 10);
        $Pages = ceil($p->totalRows/10);
        $dangqian_page = $p->nowPage;
        /*foreach ($class_list as $key => $value) {
          if ($value['end_time'] < time()) {
            $data['edit_time']=time();
            $data['state']='1';
            $where='id='.$value['id'];
            $class_list_new=M("Activity")->data($data)->where($where)->save();
          }
        }*/
        $assign=array('data'=>$class_list,'Pages'=>$Pages,'dangqian_page'=>$dangqian_page,);
        $this->assign($assign);
        $this -> display();
    }
    public function add_activity(){
      $data=I('post.');
      //获取类别数据
      $where ='recycle=0';
      $class_list=M("Activity_class")->where($where)->select();
      //添加数据
      if ($data) {
        $activity = M("Activity"); //实例化活动
        $where_a="name="."'".$data['name']."'";
        $isact=$activity->where($where_a)->select();
        if($data['name'] == "" || !empty($isact)){
            $this->error('添加失败');
        }
        $data['add_time']=time();
        $data['start_time']=strtotime($data['start_time']);
        $data['end_time']=strtotime($data['end_time']);
        $activity->data($data)->add();
        if ($activity) {
            $this->success('添加成功',U('Admin/Activity/list_activity'));
            die;
        }else{
            $this->error('添加失败');
        }
      }
      $assign=array(
        'class_list'=>$class_list
        );
      $this->assign($assign);
      $this->display();
    }
    public function edit_activity(){
      $id=I('get.id');
      $data=I('post.');

      if (!$data) {
         //回显数据
        $where='id='.$id;
        $activity=M("Activity")->where($where)->select();

        //获取类别数据
        $where_c ='recycle=0';
        $class_list=M("Activity_class")->where($where_c)->select();

        $assign = array('data' => $activity,'class' => $class_list);
        $this->assign($assign);
        $this->display();
      }else{
        //更改相关
        $class_list=M("Activity");
        $where_a="name="."'".$data['name']."'";
        $is_class_list=$class_list->where($where_a)->select();
        if (!$is_class_list) {
          $this->error('修改失败,参数错误');
        }
        $where='id='.$is_class_list[0]['id'];
        $data['edit_time']=time();
        $data['start_time']=strtotime($data['start_time']);
        $data['end_time']=strtotime($data['end_time']);
        $class_list=$class_list->data($data)->where($where)->save();
        if (!empty($class_list)) {
            $this->success('修改成功',U('Admin/Activity/list_activity'));
        }else{
            $this->error('修改失败');
        }
      }
    }
    public function delete_activity(){
      $id=I('get.id');
      $class_list=M("Activity");
      $data['edit_time']=time();
      $data['is_del']='1';
      $where='id='.$id;
      $class_list=$class_list->data($data)->where($where)->save();
      if (!empty($class_list)) {
          $this->success('删除成功',U('Admin/Activity/list_activity'));
      }else{
          $this->error('删除失败');
      }
    }
    public function search_activity(){
      $this->display();
    }
    public function wap_activity(){
      $this->display();
    }
    public function list_class_activity(){
        $page = 1;
        $xianshi = 2;

        $post = I('post.');

      $where ='recycle=0';
      $class_list=M("Activity_class")->where($where)->select();
      $count =M("Activity_class")->where($where)->count('id');

      $total_page =  ceil($count/$xianshi);



        //判断是不是ajax提交
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){

            if(!empty( $post['xianshi']) && !empty($post['page_shu'])){

                $return = array();

                $xianshi = $post['xianshi'];
                $page_shu = $post['page_shu'];

                $limit = ($page_shu -1) * $xianshi.','.$xianshi;

                $return['total_page'] =  ceil($count/$xianshi);
                $return['current_page'] = $page_shu;
                $return['data'] = M("Activity_class")->where($where)->limit($limit)->select();
                $shuju = json_encode($return);

                $this->ajaxReturn($shuju);
            }else if(!empty($post['dianqian_page']) && !empty($post['xianshi_tiao'])){

                $return = array();

                $xianshi = $post['xianshi_tiao'];

                $page_shu = $post['dianqian_page'];

                $limit = ($page_shu -1) * $xianshi.','.$xianshi;
                $return['total_page'] =  ceil($count/$xianshi);
                $return['current_page'] = $page_shu;
                $return['data'] = M("Activity_class")->where($where)->limit($limit)->select();

                $shuju = json_encode($return);

                $this->ajaxReturn($shuju);
            }else if(!empty($post['page'])){
                $xianshi = $post['page'];

                $return['total_page'] =  ceil($count/$xianshi);
                
                $return['data'] =M("Activity_class")->where($where)->limit(0,$post['page'])->select();

                $shuju = json_encode($return);

                $this->ajaxReturn($shuju);
            }
        }











      $assign=array(
          'data'=>$class_list,
          'total_page'=>$total_page,
          'current_page'=>$page
          );
      $this->assign($assign);
      $this->display();
    }
    public function add_class_activity(){
      $data=I('post.');
      //var_dump($data);
      //die;
        if($data){
            $data['time']=time();
            $ks = M("Activity_class"); // 实例化User对象
            $where="name="."'".$data['name']."'";
            $isks=$ks->where($where)->select();
            if($data['name'] == "" || !empty($isks)){
                $this->error('添加失败');
            }
            $ks->data($data)->add();
            if ($ks) {
                $this->success('添加成功',U('Admin/Activity/list_class_activity'));
            }else{
                $this->error('添加失败');
            }
        }
        $this->display();

    }
    public function edit_class_activity(){
      $data=I('post.');
      $class_list=M("Activity_class");
      $where="name="."'".$data['name']."'";
      $is_class_list=$class_list->where($where)->select();
      if($data['name'] == ""  || !empty($is_class_list)){
          $this->error('修改失败,参数错误');
      }
      $data['pubdate']=time();
      $id='id='.$data['id'];
      $class_list=$class_list->data($data)->where($id)->save();
      if (!empty($class_list)) {
          $this->success('修改成功',U('Admin/Activity/list_class_activity'));
      }else{
          $this->error('修改失败');
      }
    }
    public function delete_class_activity(){
      $id=I('get.id');
      $class_list=M("Activity_class");
      /*$where='id='.$id.' and recycle = 0';
      if(!empty($id)){
        $is_class_list=$class_list->where($where)->select();
        if(empty($is_class_list)){
          $this->error('删除失败,参数错误');
        }
      }else{
        $this->error('删除失败,参数错误');
      }*/
      $data['pubdate']=time();
      $data['recycle']='1';
      $where='id='.$id;
      $class_list=$class_list->data($data)->where($where)->save();
      if (!empty($class_list)) {
          $this->success('删除成功',U('Admin/Activity/list_class_activity'));
      }else{
          $this->error('删除失败');
      }
    }
    public function search()
    {
        $post = I('post.');
        $m = M('Patient as a');
        if(IS_POST)
        {
            $_SESSION['where'] = $where;
        }
        else
        {
            $where = $_SESSION['where'];
        }
        $p = getpage($m,$where,10);
        $result = $m -> join('yuyue_users as  b  on b.id = a.huawu') -> field('a.*,b.username') -> where($where) -> order('a.id desc') -> select();
        $assign = array('data' => $result,'area' => $area,'expert'=>$expert,'govern'=>$govern,'entity'=>$entity,'channel'=>$channel,'source'=>$source);
        $this->page=$p->show();
        $this -> assign($assign);
        $this -> display();
    }
    public function edit(){
        $make = M("Patient");
        if(IS_POST){
            $data=I('post.');
            $province = $data['address'];
            $city = $data['city'];
            $zone = $data['zone'];
            $pwhere = "area_name='$province' and area_parent_id=0";
            $cwhere = "area_name='$city' and area_parent_id<>0";
            $zwhere = "area_name='$zone' and area_parent_id<>0";
            $m = M('area');
            $pid = $m -> field('id') -> where($pwhere) -> find();
            $pid = $pid['id'];
            $cid = $m -> field('id') -> where($cwhere) -> find();
            $cid = $cid['id'];
            $zid = $m -> field('id') -> where($zwhere) -> find();
            $zid = $zid['id'];
            $data['address'] = $pid . ',' . $cid . ',' . $zid;
            unset($data['city']);
            unset($data['zone']);
            if($data['name'] == "")
            {
                $this->error('请填写患者姓名');die;
            }
            $where='id='.$data['id'];
            $make=$make->where($where)->save($data);
            if ($make)
            {
                $this->success('修改成功',U('show'));die;
            }else{
                $this->error('修改失败');die;
            }
        }elseif(IS_GET){
            $data=I('get.');
            $make=$make->where($data)->find();
            $address = $make['address'];
            $address = explode(',',$address);
            $pid = $address[0];
            $cid = $address[1];
            $zid = $address[2];
            $am = M('area');
            $province = $am -> field('area_name') -> where("id='$pid'") -> find();
            $province = $province['area_name'];
            $city = $am -> field('area_name') -> where("id='$cid'") -> find();
            $city = $city['area_name'];
            $rcity = $am -> field('area_name') -> where("area_parent_id='$pid'") -> select();
            $zone = $am -> field('area_name') -> where("id='$zid'") -> find();
            $zone = $zone['area_name'];
            $rzone = $am -> field('area_name') -> where("area_parent_id='$cid'") -> select();
            $make['province'] = $province;
            $make['city'] = $city;
            $make['zone'] = $zone;
        }
        $this->make=$make;
        $make = array($make);
        $assign=array(
            'data'=>$make,
            'address'=>$address,
            'province'=>$province,
            'city'=>$city,
            'rcity'=>$rcity,
            'zone'=>$zone,
            'rzone'=>$rzone,
            'govern'=>$govern,
            'entity'=>$entity,
            'expert'=>$expert,
            'govern'=>$govern,
        );
        $this->assign($assign);
        $this->display();
    }
    public function delete()
    {
        $id = I('get.id');
        $result = D('Activity') -> deleteAnalyze($id);
        if($result)
        {
            $this -> success('删除成功',U('list'));
        }
        else
        {
            $this -> error('删除失败',U('list'));
        }
    }
}