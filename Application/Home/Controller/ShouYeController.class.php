<?php

namespace Home\Controller;

use Common\Controller\HomeBaseController;

class ShouYeController extends HomeBaseController
{
    //手机端后台首页
    public function shouye()
    {

        $this->display();
    }

    //手机端后台--患者画像
    public function portrait()
    {

        $model = M('patient as p');

        $data = I('post.');

        //取出所有的专家名
        $expert = M("Zj")->field('zjname')->select();

        //取出所有的科室名
        $govern = M("Ks")->field('ksname')->select();

        //取出所有的客服
        $array = ' a.group_id = 7';
        $client = M('auth_group_access as a')->join('yuyue_users as u on a.uid = u.id ')->where($array)->field('username')->select();

        //取出所有的来源/媒体
        $source = M("Source")->field('name')->select();

        //取出所有的渠道
        $channel = M("mode")->field('name')->where("recycle=0")->select();

        //所有科室
        $govern = M("Ks")->field('ksname')->select();

        //地区
        $area = M('province')->field('cityid as id,cityname as area_name')->select();


        //查出数据表中的所有省份
        $province = M('province')->field('cityid,cityname')->select();


        $res = $model->field('name,age,province,sex,yuyuebz')->limit(0, 15)->select();

        $assign = array(
            'data' => $res,
            'expert' => $expert,
            'govern' => $govern,
            'client' => $client,
            'source' => $source,
            'channel' => $channel,
            'govern' => $govern,
            'area' => $area,
            'province' => $province,
        );

        $this->assign($assign);
        $this->display();
    }


    //ajax筛选
    public function ajax()
    {

        $model = M('Patient as a');

        $where = '1=1';
        $data = I('post.');


        if (empty($data['pageno'])) {
            $data['pageno'] = 0;
        }


        if (!empty($data['diqu'])) {
            $diqu = explode(',', $data['diqu']);

            if ($data['cebian'] == 2) {
                $pic = $diqu;

                $city_arr = $this->bainli($pic);

                $where .= " AND a.province in(" . $city_arr . ")";
            } else {
                $pic = array();
                foreach ($diqu as $k => $v) {
                    if ($k == 0) {
                        $city = $v;
                    }

                    if ($k != 0) {
                        if ($v != '全部' && $v != '') {
                            if (!in_array($city . $v, $pic)) {
                                if ($city . $v != '北京市北京市') {
                                    $pic[] = $city . $v;
                                }
                            }
                        }
                    }
                }

                if (!empty($pic)) {
                    $city_arr = $this->bainli($pic);
                    $where .= " AND a.city in(" . $city_arr . ")";
                }

            }


        }

        //根据客服查找出所属的主键id
        if (!empty($data['client'])) {

            $user = M('users');

            $array = array();
            $client = explode(',', $data['client']);

            foreach ($client as $k => $v) {
                $tiaojian = "username=" . "'" . $v . "'";
                $array[] = $user->where($tiaojian)->getField('id');

            }

            $array_arr = $this->bainli($array);
            $where .= " AND a.huawu in(" . $array_arr . ")";

        }

        //预约开始时间
        if (!empty($data['start'])) {

            $start = strtotime($data['start']);
            $where .= " AND a.yuyuetime>'$start'";

        }

        //预约结束时间
        if (!empty($post['end'])) {
            $end = strtotime($data['end']);
            $where .= " AND a.yuyuetime<'$end'";
        }

        //最低消费
        if (!empty($data['zuidi_xiaofei'])) {
            $down_xiaofei = $data['zuidi_xiaofei'];
            $where .= " AND o.money >'$down_xiaofei'";
        }

        //最高消费
        if (!empty($data['zuigao_xiaofei'])) {
            $up_xiaofei = $data['zuigao_xiaofei'];
            $where .= " AND o.money <'$up_xiaofei'";
        }

        //预约专家
        if (!empty($data['yuyuezj'])) {
            $zhuanjia = explode(',', $data['yuyuezj']);

            $zhuanjia_arr = $this->bainli($zhuanjia);

            $where .= " AND a.yuyuezj in(" . $zhuanjia_arr . ")";
        }

        //来源
        if (!empty($data['source'])) {
            $source = $data['source'];

            $source = explode(',', $source);

            $source_arr = $this->bainli($source);

            $where .= " AND a.source in(" . $source_arr . ")";
        }

        //渠道
        if (!empty($data['channel'])) {
            $channel = $data['channel'];

            $channel = explode(',', $channel);

            $channel_arr = $this->bainli($channel);
            $where .= " AND a.channel in(" . $channel_arr . ")";
        }

        //预约科室
        if (!empty($data['yuyueks'])) {

            $yuyueks = explode(',', $data['yuyueks']);

            $yuyueks_arr = $this->bainli($yuyueks);

            $where .= " AND a.yuyueks in(" . $yuyueks_arr . ")";

        }

        //状态
        if (!empty($data['status'])) {
            $status = explode(',', $data['status']);

            $daozhen = array();//到诊 未到诊
            $chuzhen = array();//初诊 复诊
            $hospital = array();//住院 未住院
            $xiaofei = array(); // 消费 未消费

            foreach ($status as $k => $v) {
                if ($v == '到诊' || $v == '未到诊') {
                    $daozhen[] = $v;
                } else if ($v == '初诊' || $v == '复诊') {
                    $chuzhen[] = $v;
                } else if ($v == '住院' || $v == '未住院') {
                    $hospital[] = $v;
                } else if ($v == '消费' || $v == '未消费') {
                    $xiaofei[] = $v;
                }
            }
        }

        //到诊
        if (count($daozhen) != 0) {

            foreach ($daozhen as $key => $value) {
                if ($value == '到诊') {
                    $daozhen = 1;
                } else if ($value == '未到诊') {
                    $daozhen = 0;
                }

                $daozheng_arr .= "'" . $daozhen . "',";
            }
            $daozheng_arr = substr($daozheng_arr, 0, strlen($daozheng_arr) - 1);
            $where .= " AND a.diagnosis in(" . $daozheng_arr . ")";
        }

        //初诊
        if (count($chuzhen) != 0) {


            foreach ($chuzhen as $key => $value) {
                if ($value == '初诊') {
                    $chuzhen = 0;
                } else if ($value == '复诊') {
                    $chuzhen = 1;
                }
                $fuzeng_arr .= "'" . $chuzhen . "',";
            }
            // dump($keshi_arr);
            $fuzeng_arr = substr($fuzeng_arr, 0, strlen($fuzeng_arr) - 1);

            $where .= " AND a.again in(" . $fuzeng_arr . ")";
        }

        //住院
        if (count($hospital) != 0) {
            $zhuyuan_arr = $this->bainli($hospital);
            $where .= " AND a.hospital in(" . $zhuyuan_arr . ")";
        }

        //消费
        if (count($xiaofei) != 0) {
            $xiaofei_arr = $this->bainli($xiaofei);
            $where .= " AND a.consumption in(" . $xiaofei_arr . ")";
        }


        //年龄  时间  消费排序
        if (!empty($data['daibiao'])) {

            $daibaio = $data['daibiao'];

            if (empty($data['pageno'])) {
                $data['pageno'] = 0;
            }


            if ($daibaio == 1) {

                $res = $model->field('name,a.age,province,sex,yuyuebz')->where($where)->limit($data['pageno'], 15)->order('age desc')->select();

                $res = json_encode($res);
                $this->ajaxReturn($res);
            } else if ($daibaio == 2) {
                $res = $model->field('name,age,province,sex,yuyuebz')->where($where)->limit($data['pageno'], 15)->order('age asc')->select();
                $res = json_encode($res);
                $this->ajaxReturn($res);
            } else if ($daibaio == 3) {
                $res = $model->field('name,age,province,sex,yuyuebz')->where($where)->limit($data['pageno'], 15)->order('yuyuetime desc')->select();
                $res = json_encode($res);
                $this->ajaxReturn($res);
            } else if ($daibaio == 4) {
                $res = $model->field('name,age,province,sex,yuyuebz')->where($where)->limit($data['pageno'], 15)->order('yuyuetime asc')->select();
                $res = json_encode($res);
                $this->ajaxReturn($res);
            } else if ($daibaio == 5) {
                $res = $model->join('yuyue_online_order as o on o.ordernum = a.ordernum ')->where($where)->field('a.name,a.age,a.province,a.sex,a.yuyuebz')
                    ->limit($data['pageno'], 15)->order('o.money desc')->select();
                $res = json_encode($res);
                $this->ajaxReturn($res);
            } else if ($daibaio == 6) {
                $res = $model->join('yuyue_online_order as  o on o.ordernum = a.ordernum ')->where($where)->field('a.name,a.age,a.province,a.sex,a.yuyuebz')->limit($data['pageno'], 15)->order('o.money asc')->select();
                $res = json_encode($res);
                $this->ajaxReturn($res);
            }
        }


        if (!empty($down_xiaofei) && !empty($up_xiaofei)) {
            $res = $model->join('yuyue_online_order as o on a.ordernum = o.ordernum')->where($where)->field('name,sex,age,yuyuebz,province')->limit($data['pageno'], 15)->select();
        } else {
            $res = $model->where($where)->field('name,sex,age,yuyuebz,province')->limit($data['pageno'], 15)->select();
        }

        $res = json_encode($res);

        $this->ajaxReturn($res);


    }


    public function CityAjax()
    {
        $data = I('post.');

        //接收的城市id
        $cityid = $data['cityid'];
        //根据城市id查询出下辖的区县信息
        $model = M('quxian');

        $result = $model->where(['cityid' => $cityid])->field('quxianname')->select();

        $result = json_encode($result);

        $this->ajaxReturn($result);
    }


    //手机端后台--推送
    public function push()
    {
        $data = I('get.');

        $where = "a.recycle=0 AND a.huawu<>88";

        $name = $data['shuju'];

        //查询活动
        $activity = M('activity')->field("id,name")->where("state=2 AND open=0")->order('id desc')->select();

        if (empty($activity)) {
            $activity = array(array('id' => '', 'name' => '暂无进行中的活动！'));
        }

        $array = array(
            'activity' => $activity,
            'condition' => $where,
        );
        $this->assign($array);

        $this->display();
    }

    //公用方法
    function bainli($arr)
    {


        foreach ($arr as $key => $value) {
            $list .= "'" . $value . "',";
        }
        $list = substr($list, 0, strlen($list) - 1);
        return $list;
    }

    //数据转换
    public function zhuanhuan()
    {

        // $model = M('daoru_info');

        // $where = 'id <= 134276';
        // // $where .= ' and len(phone > 11)';

        // //查询电话信息
        // $res = $model -> field('id,phone') ->where($where) -> select();

        // $arr = array();
        // foreach($res as $k=>$v){

        //     // dump(strlen($v['phone']));
        //     if(strlen($v['phone']) > 11){
        //         $v['tel'] = substr($v['phone'],11);
        //         $phone['phone'] = explode(' ', ltrim($v['phone']));
        //         $v['phone'] =  $phone['phone'][0];

        //         $arr[] = $v;
        //     }
        // }

        // foreach($arr as $k=>$v){
        //     $array = array();
        //     $array['tel'] = $v['tel'];
        //     $array['phone'] = $v['phone'];
        //      $result =$model->where(['id'=>$v['id']])->save($array);
        //   dump($result);
        // }


        // foreach($arr as $k=>&$v){
        //     $v['phone'] = explode(' ',trim($v['phone']));
        // }

        // $new = array();
        // foreach($arr as $a=>$b){
        //     foreach($b['phone'] as $c=>$d){
        //         if($c>0){
        //           $source_arr .= "'" . $d . "',";

        //           $new['id'] = $b['id'];
        //           $new['phone'] = $source_arr; 
        //         }
        //     }
        // }


        // $res = $model -> join('yuyue_auth_group_access as a on u.id = a.uid') ->where(['group_id'=> 7]) ->field('u.username,u.openid') ->select();
        // dump($res);


        // $model = M('daoru_info as i');

        // $a = M('Patient');
        // // $res = $model -> field('id,name,phone,yuyuezj') ->select();


        // $res = $model ->join('yuyue_patient as p on p.id = i.patient_id') ->field('i.patient_id as id,i.resource_leibie')->order('i.patient_id asc')->select();


        // foreach($res as $k=>$v){
        //     $array = array();
        //     $array['resource_leibie'] = $v['resource_leibie'];

        //     $result =$a->where(['id'=>$v['id']])->save($array);
        //     dump($result);
        //  }


        // $find = '[';

        // $where = 'id <= 134276';
        // $res = $model -> where($where) ->field('id,beizhu')-> select();

        // foreach($res as $k=>$v){
        //   $count = substr_count($v['beizhu'],$find);   

        //    $array = array();       
        //    $array['count'] = $count;

        //   $result =$model->where(['id'=>$v['id']])->save($array);
        //   // dump($result);
        // }
    }

}