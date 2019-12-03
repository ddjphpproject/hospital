<?php

namespace Home\Controller;

use Common\Controller\HomeBaseController;

//活动controller
class ActivityController extends HomeBaseController
{
    //手机端活动列表
    public function list_activity()
    {

//        $data = I('post.');
//
//        $title = $data['title'];

//        // $m = M('Activity as a');
//        // $where = "recycle=0 AND huawu<>88";
//        // $p = getpage($m,$where,10);
//        // $assign = array('data' => $result,'area' => $area,'expert'=>$expert,'govern'=>$govern,'entity'=>$entity,'channel'=>$channel,'source'=>$source);
//        // $this->page=$p->show();
//        // $this -> assign($assign);
//        if(!empty($get['page_shu'])){
//            $page_shu = $get['page_shu'];
//        }
//
        $m = M('Activity');
        $where = 'is_del=0';
        $where .= ' and image_url is not null';
//    var_dump($where);die;

//        if(!empty($title)){
//            $where['name'] = array('like','%'.$title.'%');
//        }
//
////        dump($where);
//
        $class_list = M("Activity")->where($where)->select();
//        dump($class_list);die;
//        $p = getpage($m, $where, 10);
//        $Pages = ceil($p->totalRows/10);
//        $dangqian_page = $p->nowPage;
        /*foreach ($class_list as $key => $value) {
          if ($value['end_time'] < time()) {
            $data['edit_time']=time();
            $data['state']='1';
            $where='id='.$value['id'];
            $class_list_new=M("Activity")->data($data)->where($where)->save();
          }
        }*/

        foreach ($class_list as $k => &$v) {
            $v['start_time'] = date('Y-m-d', $v['start_time']);

        }

        $assign = array('data' => $class_list);
        $this->assign($assign);
        $this->display();
    }


    //手机端端活动添加
    public function add_activity()
    {
        $data = I('post.');

//        //获取类别数据
        $where = 'recycle=0';
        $class_list = M("Activity_class")->where($where)->select();

        //添加数据
        if ($data) {
            $activity = M("Activity"); //实例化活动
            $where_a = "name=" . "'" . $data['name'] . "'";
            $isact = $activity->where($where_a)->select();
            if ($data['name'] == "" || !empty($isact)) {
                $this->error('添加失败');
            }
            $data['add_time'] = time();
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);
            $activity->data($data)->add();
            if ($activity) {
                $this->success('添加成功', U('Home/Activity/list_activity'));
//                $this->success('添加成功');
                die;
            } else {
                $this->error('添加失败');
            }
        }
        $assign = array(
            'class_list' => $class_list
        );
        $this->assign($assign);
        $this->display();
    }

    //手机端端活动编辑
    public function edit_activity()
    {
        $id = I('get.id');
        $data = I('post.');

        if (!$data) {
            //回显数据
            $where = 'id=' . $id;
            $activity = M("Activity")->where($where)->select();

            //获取类别数据
            $where_c = 'recycle=0';
            $class_list = M("Activity_class")->where($where_c)->select();

            $assign = array('data' => $activity, 'class' => $class_list);
            $this->assign($assign);
            $this->display();
        } else {
            //更改相关
            $class_list = M("Activity");
            $where_a = "name=" . "'" . $data['name'] . "'";


            $is_class_list = $class_list->where($where_a)->select();
            if (!$is_class_list) {
                $this->error('修改失败,参数错误');
            }
            $where = 'id=' . $is_class_list[0]['id'];
            $data['edit_time'] = time();
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']);


            $class_list = $class_list->data($data)->where($where)->save();
            if (!empty($class_list)) {
                $this->success('修改成功', U('Home/Activity/list_activity'));
            } else {
                $this->error('修改失败');
            }
        }
    }

    // 图片上传
    public function upload()
    {

        $upFile = $_FILES['file'];
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = './Public/uploads/'; // 设置附件上传根目录
        $upload->savePath = ''; // 设置附件上传（子）目录
        // 上传文件
        $info = $upload->upload();


        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功
            $array = array();

            $address = '/Public/uploads/' . $info['file']['savepath'] . $info['file']['savename'];
            $array['url'] = $address;
            $this->ajaxReturn(json_encode($array));
        }

    }

    //活动推送查询
    public function tuisong_activity()
    {

//        $model = M('history_push_activities as h');
        $model = M('history_push_activities as h');

        $res = $model->join('yuyue_activity as a on a.id = h.activity_id')->field('a.name,a.jianjie,a.id as activity_id,ts_time')->group('h.activity_id')->order('ts_time desc')->select();

        foreach ($res as $k => &$v) {

            $v['ts_time'] = date('Y-m-d',$v['ts_time']);
            $where['activity_id'] = $v['activity_id'];

            $v['send_id'] = $model->where($where)->field('send_id')->group('send_id')->select();


            foreach ($v['send_id'] as $a => &$b) {
                $array['send_id'] = $b['send_id'];
                $array['activity_id'] = $v['activity_id'];

                if ($b['send_id'] == 1) {
                    $v['duanxin_total'] = $model->where($array)->count('id');
                } else if ($b['send_id'] == 2) {
                    $v['zhannei_total'] = $model->where($array)->count('id');
                } else if ($b['send_id'] == 3) {
                    $v['weixin_total'] = $model->where($array)->count('id');
                }
            }

            if (!isset($v['zhannei_total'])) {
                $v['zhannei_total'] = 0;
            }

            if (!isset($v['duanxin_total'])) {
                $v['duanxin_total'] = 0;
            }

            if (!isset($v['weixin_total'])) {
                $v['weixin_total'] = 0;
            }
        }

        foreach ($res as $c => &$d) {
            foreach ($d['send_id'] as $g => &$h) {
                $shuzu['activity_id'] = $d['activity_id'];
                $shuzu['send_id'] = $h['send_id'];
                $shuzu['success'] = 1;

                if ($h['send_id'] == 1) {
                    $d['duanxin_success'] = $model->where($shuzu)->count('id');
                } else if ($h['send_id'] == 2) {
                    $d['zhannei_success'] = $model->where($shuzu)->count('id');
                } else if ($h['send_id'] == 3) {
                    $d['weixin_success'] = $model->where($shuzu)->count('id');
                }

            }

            if (!isset($d['duanxin_success'])) {
                $d['duanxin_success'] = 0;
            }

            if (!isset($d['zhannei_success'])) {
                $d['zhannei_success'] = 0;
            }

            if (!isset($d['weixin_success'])) {
                $d['weixin_success'] = 0;
            }

        }


        $assign=array('data'=>$res);

        $this->assign($assign);

        $this->display();
    }
}



