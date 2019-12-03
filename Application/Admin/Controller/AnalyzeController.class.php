<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;
use Aliyun\Core\Config;

use Aliyun\Core\Profile\DefaultProfile;

use Aliyun\Core\DefaultAcsClient;

use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

/**
 * 数据分析控制器
 */
class AnalyzeController extends AdminBaseController
{
    public function show()
    {
 
        $xianshi = 10;
        $page_shu = 1;

        $m = M('Patient as a');

        $model = M('daoru_info as i');

        $where = "a.recycle=0 AND a.huawu<>88";

        $post = I('post.'); 
        $get = I('get.');
//        echo '<pre />';

        if (!empty($post['result'])) {
            $res = str_replace("&quot;", "\"", $post['result']);
            $post = json_decode($res, true);
        }
//    dump($post);

        if ($post['biao'] == '' || $post['biao'] == 1) {

        //判断预约科室是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['yuyueks'])) {
            $canyu_keshi = $post['yuyueks'];
            $post['yuyueks'] = explode(',', $post['yuyueks']);
        } else {
            $post['yuyueks'] = array();
        }

        //判断专家是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['yuyuezj'])) {
            $canshu_zhuanjia = $post['yuyuezj'];
            $post['yuyuezj'] = explode(',', $post['yuyuezj']);
        } else {
            $post['yuyuezj'] = array();
        }

        //判断病种是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['yuyuebz'])) {
            $canshu_bz = $post['yuyuebz'];
            $post['yuyuebz'] = explode(',', $post['yuyuebz']);
        } else {
            $post['yuyuebz'] = array();
        }

        //判断渠道是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['channel'])) {
            $canshu_qudao = $post['channel'];
            $post['channel'] = explode(',', $post['channel']);
        } else {
            $post['channel'] = array();
        }

        //判断来源是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['source'])) {
            $canshu_source = $post['source'];
            $post['source'] = explode(',', $post['source']);
        } else {
            $post['source'] = array();
        }

        //判断复诊是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['again'])) {
            $canshu_again = $post['again'];

            $post['again'] = explode(',', $post['again']);
//            dump($post['again']);
        } else {
            $post['again'] = array();
        }

        //判断到诊情况是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['diagnosis'])) {
            $canyu_diagnosis = $post['diagnosis'];

            $post['diagnosis'] = explode(',', $post['diagnosis']);
        } else {
            $post['diagnosis'] = array();
        }

        //判断消费情况是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['consumption'])) {
            $canyu_consumption = $post['consumption'];

            $post['consumption'] = explode(',', $post['consumption']);
        } else {
            $post['consumption'] = array();
        }

        //判断住院情况是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['hospital'])) {
            $canyu_hospital = $post['hospital'];

            $post['hospital'] = explode(',', $post['hospital']);
        } else {
            $post['hospital'] = array();
        }

        //判断资源类别是否为空,为空,定义空数组,不为空,字符串转为数组
        if (!empty($post['leibie'])) {
            $canyu_leibie = $post['leibie'];
            $post['leibie'] = explode(',', $post['leibie']);
        } else {
            $post['leibie'] = array();
        }


//        var_dump($post);

        $canshu = array();
        if (IS_POST) {
//    var_dump(222);
            if (!empty($post['start'])) {
                $canshu['start'] = $post['start'];
            } else {
                $canshu['start'] = '';
            }

            if (!empty($post['end'])) {
                $canshu['end'] = $post['end'];
            } else {
                $canshu['end'] = '';
            }

            if (!empty($post['age_start'])) {
                $canshu['age_start'] = $post['age_start'];
            } else {
                $canshu['age_start'] = '';
            }

            if (!empty($post['age_end'])) {
                $canshu['age_end'] = $post['age_end'];
            } else {
                $canshu['age_end'] = '';
            }

            if (!empty($post['start_time'])) {
                $canshu['start_time'] = $post['start_time'];
            } else {
                $canshu['start_time'] = '';
            }

            if (!empty($post['end_time'])) {
                $canshu['end_time'] = $post['end_time'];
            } else {
                $canshu['end_time'] = '';
            }

            if (!empty($post['phone'])) {
                $canshu['phone'] = $post['phone'];
            }

            $canshu['keshi'] = $canyu_keshi;
            $canshu['zhuanjia'] = $canshu_zhuanjia;
            $canshu['bz'] = $canshu_bz;
            $canshu['source'] = $canshu_source;
            $canshu['qudao'] = $canshu_qudao;
            $canshu['again'] = $canshu_again;
            $canshu['diagnosis'] = $canyu_diagnosis;
            $canshu['consumption'] = $canyu_consumption;
            $canshu['hospital'] = $canyu_hospital;
            $canshu['leibie'] = $canyu_leibie;
            $canshu['biao'] = 1;

            if (!empty($post['start']) && !empty($post['end']) && $post['start'] == $post['end']) {

                if ($post['start'] == date('Y-m-d', time())) {
                    //凌晨开始时间
                    $start = strtotime($post['start']);

                    //结束时间
                    $end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                } else {
                    //凌晨开始时间
                    $start = strtotime($post['start']);
                    //结束时间
                    $end = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
                }
//                查询条件
                $where .= " AND a.yuyuetime>'$start'";
                $where .= " AND a.yuyuetime<'$end'";
            } else {
                //预约时间
                if (!empty($post['start'])) {
                    $start = strtotime(trim($post['start']));

                    $where .= " AND a.yuyuetime>'$start'";

                }
                if (!empty($post['end'])) {
                    $end = strtotime(trim($post['end']));
                    $where .= " AND a.yuyuetime<'$end'";

                }
            }

            //年龄区间
            if (!empty($post['age_start'])) {
                $agestart = $post['age_start'];
                $where .= " AND a.age>" . $agestart;

            }
            if (!empty($post['age_end'])) {
                $ageend = $post['age_end'];
                $where .= " AND a.age<" . $ageend;

            }

            // 所有科室
            $keshi = $post['yuyueks'];

            // dump($post['yuyueks']);
            if (count($keshi) != 0) {

                foreach ($keshi as $key => $value) {
                    $keshi_arr .= "'" . $value . "',";
                } 
                $this->assign('keshi_arr', $keshi_arr);
                $keshi_arr = substr($keshi_arr, 0, strlen($keshi_arr) - 1);
                $where .= " AND a.yuyueks in(" . $keshi_arr . ")";

            }
            // 所有专家
            $zhuanjia = $post['yuyuezj'];

            if (count($zhuanjia) != 0) {
                foreach ($zhuanjia as $key => $value) {
                    $zhuanjia_arr .= "'" . $value . "',";
                }
                $this->assign('zhuanjia_arr', $zhuanjia_arr);
                // dump($keshi_arr);
                $zhuanjia_arr = substr($zhuanjia_arr, 0, strlen($zhuanjia_arr) - 1);

                $where .= " AND a.yuyuezj in(" . $zhuanjia_arr . ")";
            }

            // 所有病种
            $bingzhong = $post['yuyuebz'];
            // dump($post['yuyueks']);
            if (count($bingzhong) != 0) {
                foreach ($bingzhong as $key => $value) {
                    $bingzhong_arr .= "'" . $value . "',";
                }
                $this->assign('bingzhong_arr', $bingzhong_arr);
                // dump($keshi_arr);
                $bingzhong_arr = substr($bingzhong_arr, 0, strlen($bingzhong_arr) - 1);
                $where .= " AND a.yuyuebz in(" . $bingzhong_arr . ")";
            }

            // 所有渠道
            $qudao = $post['channel'];
            // dump($post['yuyueks']);
            if (count($qudao) != 0) {
                foreach ($qudao as $key => $value) {
                    $qudao_arr .= "'" . $value . "',";
                }
                $this->assign('qudao_arr', $qudao_arr);
                // dump($keshi_arr);
                $qudao_arr = substr($qudao_arr, 0, strlen($qudao_arr) - 1);
                $where .= " AND a.channel in(" . $qudao_arr . ")";
            }

            //来源
            $source = $post['source'];
            if (count($source) != 0) {
                foreach ($source as $key => $value) {
                    $source_arr .= "'" . $value . "',";
                }
                $this->assign('source_arr', $source_arr);
                // dump($keshi_arr);
                $source_arr = substr($source_arr, 0, strlen($source_arr) - 1);
                $where .= " AND a.source in(" . $source_arr . ")";
            }

            // 所有复诊
            $fuzeng = $post['again'];
            // dump($post['yuyueks']);
            if (count($fuzeng) != 0) {
                foreach ($fuzeng as $key => $value) {
                    if ($value == '初诊') {
                        $chuzhen = 0;
                    } else if ($value == '复诊') {
                        $chuzhen = 1;
                    }
                    $fuzeng_arr .= "'" . $chuzhen . "',";
                }
                $this->assign('fuzeng_arr', $fuzeng_arr);
                // dump($keshi_arr);
                $fuzeng_arr = substr($fuzeng_arr, 0, strlen($fuzeng_arr) - 1);
                $where .= " AND a.again in(" . $fuzeng_arr . ")";
            }


            if (!empty($post['phone'])) {
                $where .= " AND a.phone ='" . $post['phone'] . "'";
            }

            //到诊情况

            $daozheng = $post['diagnosis'];
            // dump($post['yuyueks']);
            if (count($daozheng) != 0) {
                foreach ($daozheng as $key => $value) {
                    if ($value == '到诊') {
                        $daozhen = 1;
                    } else if ($value == '未到诊') {
                        $daozhen = 0;
                    }

                    $daozheng_arr .= "'" . $daozhen . "',";
                }
                $this->assign('daozheng_arr', $daozheng_arr);
                // dump($keshi_arr);
                $daozheng_arr = substr($daozheng_arr, 0, strlen($daozheng_arr) - 1);
                $where .= " AND a.diagnosis in(" . $daozheng_arr . ")";

            }


            //消费情况
            $xiaofei = $post['consumption'];
            // dump($post['yuyueks']);
            if (count($xiaofei) != 0) {
                foreach ($xiaofei as $key => $value) {
                    if ($value == '消费') {
                        $cost = 1;
                    } else if ($value == '未消费') {
                        $cost = 0;
                    }

                    $xiaofei_arr .= "'" . $cost . "',";
                }
                $this->assign('xiaofei_arr', $xiaofei_arr);
                // dump($keshi_arr);
                $xiaofei_arr = substr($xiaofei_arr, 0, strlen($xiaofei_arr) - 1);
                $where .= " AND a.consumption in(" . $xiaofei_arr . ")";
            }

            //住院情况
            $zhuyuan = $post['hospital'];
            // dump($post['yuyueks']);
            if (count($zhuyuan) != 0) {
                foreach ($zhuyuan as $key => $value) {
                    if ($value == '住院') {
                        $hosp = 1;
                    } else if ($value == '未住院') {
                        $hosp = 0;
                    }

                    $zhuyuan_arr .= "'" . $hosp . "',";
                }
                $this->assign('zhuyuan_arr', $zhuyuan_arr);
                // dump($keshi_arr);
                $zhuyuan_arr = substr($zhuyuan_arr, 0, strlen($zhuyuan_arr) - 1);
                $where .= " AND a.hospital in(" . $zhuyuan_arr . ")";
            }

            //资源类别
            //住院情况
            $leibie = $post['leibie'];

            // dump($post['yuyueks']);
            if (count($leibie) != 0) {
                foreach ($leibie as $key => $value) {

                    $leibie_arr .= "'" . $value . "',";
                }

                $leibie_arr = substr($leibie_arr, 0, strlen($leibie_arr) - 1);

                $where .= " AND a.resource_leibie in(" . $leibie_arr . ")";
            }


            if (!empty($post['start_time']) && !empty($post['end_time']) && $post['start_time'] == $post['end_time']) {
                if ($post['start_time'] == date('Y-m-d', time())) {
                    $start_time_two = strtotime($post['start_time']);

                    //结束时间
                    $end_time_two = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                } else {
                    //凌晨开始时间
                    $start_time_two = strtotime($post['start_time']);

                    //结束时间
                    $end_time_two = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
                }
//                查询条件
                $where .= " AND a.time>'$start_time_two'";
                $where .= " AND a.time<'$end_time_two'";

            } else {
                if (!empty($post['start_time'])) {

                    $start_time_two = strtotime(trim($post['start_time']));
                    $where .= " AND a.time>'$start_time_two'";
                }
                if (!empty($post['end_time'])) {
                    $end_time_two = strtotime(trim($post['end_time']));
                    $where .= " AND a.time<'$end_time_two'";
                }
            }
            // $_SESSION['where'] = $where;
        }

        if (!empty($get['id'])) {
            $id = $get['id'];
            $where .= " AND a.id !='$id'";
        }


        $p = getpage($m, $where, 10);

        $result = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->order('a.id asc')->select();

        $count = $m->join('yuyue_users as  b  on b.id = a.huawu')->where($where)->count('a.id');

        if (!empty($post['form_tiaoshu']) && !empty($post['form_page'])) {
            $xianshi = $post['form_tiaoshu'];
            $page_shu = $post['form_page'];

            $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;

            $result = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->limit($limit)->order('a.yuyuetime desc')->select();
        }

        if (!empty($post['form_tiaoshu']) && !empty($post['form_page'])) {
            $xianshi = $post['form_tiaoshu'];
            $page_shu = $post['form_page'];

            $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;

            $result = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->limit($limit)->order('a.yuyuetime desc')->select();
        }

        $total_page = ceil($count / $xianshi);


        //  $count = $m->join('yuyue_users as  b  on b.id = a.huawu')->where($where)->count('a.id');


//        var_dump($page_shu,$xianshi);

//        var_dump($total_page);

//        $result = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->order('a.yuyuetime desc')->select();

        $area = M('area')->field('id,area_name')->where("area_parent_id=0")->select();
        $expert = M("Zj")->field('zjname')->select();

        $govern = M("Ks")->field('ksname')->select();

        $entity = M("Bz")->field('bzname')->select();

        $channel = M("mode")->field('name')->where("recycle=0")->select();
        //取出所有资源类别
        $leibie = M('daoru_info')->field('resource_leibie')->where("resource_leibie != ''")->group('resource_leibie')->select();
        // dump($leibie);die;

        //查询活动
        $activity = M('activity')->field("id,name")->where("state=2 AND open=0")->order('id desc')->select();


        if (empty($activity)) {
            $activity = array(array('id' => '', 'name' => '暂无进行中的活动！'));
        }
        $source = M("Source")->field('name')->select();

        $data = array();


        foreach ($govern as $k => $v) {
            $data[0][] = $v['ksname'];
        }

        foreach ($expert as $kk => $vv) {
            $data[1][] = $vv['zjname'];
        }


        foreach ($entity as $kkk => $vvv) {
            $data[2][] = $vvv['bzname'];
        }


        foreach ($channel as $a => $b) {
            $data[3][] = $b['name'];
        }

        foreach ($source as $aa => $bb) {
            $data[4][] = $bb['name'];
        }

        $chufuzhen = array(array('name' => '初诊'), array('name' => '复诊'));
        $daozhen = array(array('name' => '到诊'), array('name' => '未到诊'));
        $xiaofei_qingkuang = array(array('name' => '消费'), array('name' => '未消费'));
        $zhuyuan_qingkaung = array(array('name' => '住院'), array('name' => '未住院'));

        foreach ($chufuzhen as $aaa => $bbb) {
            $data[5][] = $bbb['name'];
        }

        foreach ($daozhen as $c => $d) {
            $data[6][] = $d['name'];
        }

        foreach ($xiaofei_qingkuang as $cc => $dd) {
            $data[7][] = $dd['name'];
        }

        foreach ($zhuyuan_qingkaung as $ccc => $ddd) {
            $data[8][] = $ddd['name'];
        }

        foreach ($leibie as $h => $g) {
            if ($g['resource_leibie'] != '' && $g['resource_leibie'] != 'null') {
                $data[9][] = $g['resource_leibie'];
            }
        }

        //判断是不是ajax提交
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {

            if (!empty($post['xianshi']) && !empty($post['page_shu'])) {

                $return = array();

                $xianshi = $post['xianshi'];
                $page_shu = $post['page_shu'];

                $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;

                $return['total_page'] = ceil($count / $xianshi);
                $return['current_page'] = $page_shu;

                $return['data'] = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->limit($limit)->order('a.yuyuetime desc')->select();


                $shuju = json_encode($return);

                $this->ajaxReturn($shuju);
            } else if (!empty($post['dianqian_page']) && !empty($post['xianshi_tiao'])) {
                ///////


                $return = array();

                $xianshi = $post['xianshi_tiao'];

                $page_shu = $post['dianqian_page'];

                $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;
                $return['total_page'] = ceil($count / $xianshi);
                $return['current_page'] = $page_shu;
                $return['data'] = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->limit($limit)->order('a.yuyuetime desc')->select();

                $shuju = json_encode($return);

                $this->ajaxReturn($shuju);
            } else if (!empty($post['page']) && !empty($post['current_page'])) {

                $return = array();

                $xianshi = $post['page'];

                $page_shu = $post['current_page'];

                $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;
                $return['total_page'] = ceil($count / $xianshi);
                $return['current_page'] = $page_shu;

                $return['data'] = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->limit($limit)->order('a.yuyuetime desc')->select();


                $shuju = json_encode($return);

                $this->ajaxReturn($shuju);
            }
        }

//        if($post['form_page'] && $post['form_tiaoshu']){
//            var_dump(3333);
//            $xianshi = $post['form_tiaoshu'];
//            var_dump($xianshi);
//            $page_shu = $post['form_page'];
//            var_dump($page_shu);
//            $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;
//            $total_page = ceil($count / $xianshi);
//            $page_shu = $page_shu;
//            $result = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->limit($limit)->order('a.id asc')->select();
//        }

        $result = json_encode($result);


        $assign = array('xianshi' => $xianshi, 'canshu' => $canshu, 'total_page' => $total_page, 'current_page' => $page_shu, 'data' => $result, 'area' => $area, 'expert' => $expert, 'govern' => $govern, 'entity' => $entity, 'channel' => $channel, 'source' => $source, 'activity' => $activity, 'pic' => json_encode($data), 'condition' => $where);
//        $this->page = $p->show();

        $this->assign($assign);
        $this->display();
        }else if($post['biao'] == 2){


            $tiaojian = "a.recycle=0 AND a.huawu<>88";

            //判断预约科室是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['yuyueks'])) {
                $canyu_keshi = $post['yuyueks'];
                $post['yuyueks'] = explode(',', $post['yuyueks']);
            } else {
                $post['yuyueks'] = array();
            }

            //判断专家是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['yuyuezj'])) {
                $canshu_zhuanjia = $post['yuyuezj'];
                $post['yuyuezj'] = explode(',', $post['yuyuezj']);
            } else {
                $post['yuyuezj'] = array();
            }

            //判断病种是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['yuyuebz'])) {
                $canshu_bz = $post['yuyuebz'];
                $post['yuyuebz'] = explode(',', $post['yuyuebz']);
            } else {
                $post['yuyuebz'] = array();
            }

            //判断渠道是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['channel'])) {
                $canshu_qudao = $post['channel'];
                $post['channel'] = explode(',', $post['channel']);
            } else {
                $post['channel'] = array();
            }

            //判断来源是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['source'])) {
                $canshu_source = $post['source'];
                $post['source'] = explode(',', $post['source']);
            } else {
                $post['source'] = array();
            }

            //判断复诊是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['again'])) {
                $canshu_again = $post['again'];

                $post['again'] = explode(',', $post['again']);
//            dump($post['again']);
            } else {
                $post['again'] = array();
            }

            //判断到诊情况是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['diagnosis'])) {
                $canyu_diagnosis = $post['diagnosis'];

                $post['diagnosis'] = explode(',', $post['diagnosis']);
            } else {
                $post['diagnosis'] = array();
            }

            //判断消费情况是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['consumption'])) {
                $canyu_consumption = $post['consumption'];

                $post['consumption'] = explode(',', $post['consumption']);
            } else {
                $post['consumption'] = array();
            }

            //判断住院情况是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['hospital'])) {
                $canyu_hospital = $post['hospital'];

                $post['hospital'] = explode(',', $post['hospital']);
            } else {
                $post['hospital'] = array();
            }

            //判断资源类别是否为空,为空,定义空数组,不为空,字符串转为数组
            if (!empty($post['leibie'])) {
                $canyu_leibie = $post['leibie'];
                $post['leibie'] = explode(',', $post['leibie']);
            } else {
                $post['leibie'] = array();
            }

            $canshu = array();
            if (IS_POST) {
//                $tiaojian = '1=1';

                if (!empty($post['start'])) {
                    $canshu['start'] = $post['start'];
                } else {
                    $canshu['start'] = '';
                }

                if (!empty($post['end'])) {
                    $canshu['end'] = $post['end'];
                } else {
                    $canshu['end'] = '';
                }

                if (!empty($post['age_start'])) {
                    $canshu['age_start'] = $post['age_start'];
                } else {
                    $canshu['age_start'] = '';
                }

                if (!empty($post['age_end'])) {
                    $canshu['age_end'] = $post['age_end'];
                } else {
                    $canshu['age_end'] = '';
                }

                if (!empty($post['start_time'])) {
                    $canshu['start_time'] = $post['start_time'];
                } else {
                    $canshu['start_time'] = '';
                }

                if (!empty($post['end_time'])) {
                    $canshu['end_time'] = $post['end_time'];
                } else {
                    $canshu['end_time'] = '';
                }

                if (!empty($post['phone'])) {
                    $canshu['phone'] = $post['phone'];
                }

                $canshu['keshi'] = $canyu_keshi;
                $canshu['zhuanjia'] = $canshu_zhuanjia;
                $canshu['bz'] = $canshu_bz;
                $canshu['source'] = $canshu_source;
                $canshu['qudao'] = $canshu_qudao;
                $canshu['again'] = $canshu_again;
                $canshu['diagnosis'] = $canyu_diagnosis;
                $canshu['consumption'] = $canyu_consumption;
                $canshu['hospital'] = $canyu_hospital;
                $canshu['leibie'] = $canyu_leibie;
                $canshu['biao'] =2;

                if (!empty($post['start']) && !empty($post['end']) && $post['start'] == $post['end']) {

                    if ($post['start'] == date('Y-m-d', time())) {
                        //凌晨开始时间
                        $start = strtotime($post['start']);

                        //结束时间
                        $end = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                    } else {
                        //凌晨开始时间
                        $start = strtotime($post['start']);
                        //结束时间
                        $end = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
                    }

//                查询条件
                    $tiaojian .= " AND a.yuyuetime>'$start'";
                    $tiaojian .= " AND a.yuyuetime<'$end'";

                } else {
                    //预约时间
                    if (!empty($post['start'])) {
                        $start = strtotime(trim($post['start']));

                        $tiaojian .= " AND a.yuyuetime>'$start'";

                    }
                    if (!empty($post['end'])) {
                        $end = strtotime(trim($post['end']));
                        $tiaojian .= " AND a.yuyuetime<'$end'";

                    }
                }

                //年龄区间
                if (!empty($post['age_start'])) {
                    $agestart = $post['age_start'];
                    $tiaojian .= " AND i.age>" . $agestart;

                }
                if (!empty($post['age_end'])) {
                    $ageend = $post['age_end'];
                    $tiaojian .= " AND i.age<" . $ageend;

                }

                // 所有科室
                $keshi = $post['yuyueks'];

                // dump($post['yuyueks']);
                if (count($keshi) != 0) {

                    foreach ($keshi as $key => $value) {
                        $keshi_arr .= "'" . $value . "',";
                    }

                    $this->assign('keshi_arr', $keshi_arr);
                    $keshi_arr = substr($keshi_arr, 0, strlen($keshi_arr) - 1);
                    $tiaojian .= " AND i.yuyueks in(" . $keshi_arr . ")";

                }
                // 所有专家
                $zhuanjia = $post['yuyuezj'];

                if (count($zhuanjia) != 0) {
                    foreach ($zhuanjia as $key => $value) {
                        $zhuanjia_arr .= "'" . $value . "',";
                    }
                    $this->assign('zhuanjia_arr', $zhuanjia_arr);
                    // dump($keshi_arr);
                    $zhuanjia_arr = substr($zhuanjia_arr, 0, strlen($zhuanjia_arr) - 1);

                    $tiaojian .= " AND i.yuyuezj in(" . $zhuanjia_arr . ")";
                }

                // 所有病种
                $bingzhong = $post['yuyuebz'];
                // dump($post['yuyueks']);
                if (count($bingzhong) != 0) {
                    foreach ($bingzhong as $key => $value) {
                        $bingzhong_arr .= "'" . $value . "',";
                    }
                    $this->assign('bingzhong_arr', $bingzhong_arr);
                    // dump($keshi_arr);
                    $bingzhong_arr = substr($bingzhong_arr, 0, strlen($bingzhong_arr) - 1);
                    $tiaojian .= " AND i.yuyuebz in(" . $bingzhong_arr . ")";
                }

                // 所有渠道
                $qudao = $post['channel'];
                // dump($post['yuyueks']);
                if (count($qudao) != 0) {
                    foreach ($qudao as $key => $value) {
                        $qudao_arr .= "'" . $value . "',";
                    }
                    $this->assign('qudao_arr', $qudao_arr);
                    // dump($keshi_arr);
                    $qudao_arr = substr($qudao_arr, 0, strlen($qudao_arr) - 1);
                    $tiaojian .= " AND a.channel in(" . $qudao_arr . ")";
                }

                //来源
                $source = $post['source'];
                if (count($source) != 0) {
                    foreach ($source as $key => $value) {
                        $source_arr .= "'" . $value . "',";
                    }
                    $this->assign('source_arr', $source_arr);
                    // dump($keshi_arr);
                    $source_arr = substr($source_arr, 0, strlen($source_arr) - 1);
                    $tiaojian .= " AND a.source in(" . $source_arr . ")";
                }

                // 所有复诊
                $fuzeng = $post['again'];
                // dump($post['yuyueks']);
                if (count($fuzeng) != 0) {
                    foreach ($fuzeng as $key => $value) {
                        if ($value == '初诊') {
                            $chuzhen = 0;
                        } else if ($value == '复诊') {
                            $chuzhen = 1;
                        }
                        $fuzeng_arr .= "'" . $chuzhen . "',";
                    }
                    $this->assign('fuzeng_arr', $fuzeng_arr);
                    // dump($keshi_arr);
                    $fuzeng_arr = substr($fuzeng_arr, 0, strlen($fuzeng_arr) - 1);
                    $tiaojian .= " AND a.again in(" . $fuzeng_arr . ")";
                }


                if (!empty($post['phone'])) {
                    $tiaojian .= " AND i.phone ='" . $post['phone'] . "'";
                }

                //到诊情况

                $daozheng = $post['diagnosis'];
                // dump($post['yuyueks']);
                if (count($daozheng) != 0) {
                    foreach ($daozheng as $key => $value) {
                        if ($value == '到诊') {
                            $daozhen = 1;
                        } else if ($value == '未到诊') {
                            $daozhen = 0;
                        }

                        $daozheng_arr .= "'" . $daozhen . "',";
                    }
                    $this->assign('daozheng_arr', $daozheng_arr);
                    // dump($keshi_arr);
                    $daozheng_arr = substr($daozheng_arr, 0, strlen($daozheng_arr) - 1);
                    $tiaojian .= " AND a.diagnosis in(" . $daozheng_arr . ")";

                }


                //消费情况
                $xiaofei = $post['consumption'];
                // dump($post['yuyueks']);
                if (count($xiaofei) != 0) {
                    foreach ($xiaofei as $key => $value) {
                        if ($value == '消费') {
                            $cost = 1;
                        } else if ($value == '未消费') {
                            $cost = 0;
                        }

                        $xiaofei_arr .= "'" . $cost . "',";
                    }
                    $this->assign('xiaofei_arr', $xiaofei_arr);
                    // dump($keshi_arr);
                    $xiaofei_arr = substr($xiaofei_arr, 0, strlen($xiaofei_arr) - 1);
                    $tiaojian .= " AND a.consumption in(" . $xiaofei_arr . ")";
                }

                //住院情况
                $zhuyuan = $post['hospital'];
                // dump($post['yuyueks']);
                if (count($zhuyuan) != 0) {
                    foreach ($zhuyuan as $key => $value) {
                        if ($value == '住院') {
                            $hosp = 1;
                        } else if ($value == '未住院') {
                            $hosp = 0;
                        }

                        $zhuyuan_arr .= "'" . $hosp . "',";
                    }
                    $this->assign('zhuyuan_arr', $zhuyuan_arr);
                    // dump($keshi_arr);
                    $zhuyuan_arr = substr($zhuyuan_arr, 0, strlen($zhuyuan_arr) - 1);
                    $tiaojian .= " AND a.hospital in(" . $zhuyuan_arr . ")";
                }

                //资源类别
                $leibie = $post['leibie'];

                // dump($post['yuyueks']);
                if (count($leibie) != 0) {
                    foreach ($leibie as $key => $value) {

                        $leibie_arr .= "'" . $value . "',";
                    }

                    $leibie_arr = substr($leibie_arr, 0, strlen($leibie_arr) - 1);

                    $tiaojian .= " AND i.resource_leibie in(" . $leibie_arr . ")";
                }


                if (!empty($post['start_time']) && !empty($post['end_time']) && $post['start_time'] == $post['end_time']) {
                    if ($post['start_time'] == date('Y-m-d', time())) {
                        $start_time_two = strtotime($post['start_time']);

                        //结束时间
                        $end_time_two = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
                    } else {
                        //凌晨开始时间
                        $start_time_two = strtotime($post['start_time']);

                        //结束时间
                        $end_time_two = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
                    }
//                查询条件
                    $tiaojian .= " AND a.time>'$start_time_two'";
                    $tiaojian .= " AND a.time<'$end_time_two'";

                } else {
                    if (!empty($post['start_time'])) {

                        $start_time_two = strtotime(trim($post['start_time']));
                        $tiaojian .= " AND a.time>'$start_time_two'";
                    }
                    if (!empty($post['end_time'])) {
                        $end_time_two = strtotime(trim($post['end_time']));
                        $tiaojian .= " AND a.time<'$end_time_two'";
                    }
                }
                // $_SESSION['where'] = $where;
            }

//            $p = getpage($model, $tiaojian, 10);


            $result = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('i.id,i.name,i.phone,a.yuyuetime,a.diagnosis')->where($tiaojian)->limit(0,10)->order('a.id asc')->select();

            $count = $model->join('yuyue_patient as a on i.patient_id = a.id')->where($tiaojian)->count('i.id');

            if (!empty($post['form_tiaoshu']) && !empty($post['form_page'])) {
                $xianshi = $post['form_tiaoshu'];
                $page_shu = $post['form_page'];

                $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;

                $result = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('a.*')->where($tiaojian)->limit($limit)->order('i.id desc')->select();
            }

            if (!empty($post['form_tiaoshu']) && !empty($post['form_page'])) {
                $xianshi = $post['form_tiaoshu'];
                $page_shu = $post['form_page'];

                $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;

                $result = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('a.*')->where($tiaojian)->limit($limit)->select();
            }

            $total_page = ceil($count / $xianshi);

            $area = M('area')->field('id,area_name')->where("area_parent_id=0")->select();
            $expert = M("Zj")->field('zjname')->select();

            $govern = M("Ks")->field('ksname')->select();

            $entity = M("Bz")->field('bzname')->select();

            $channel = M("mode")->field('name')->where("recycle=0")->select();
            //取出所有资源类别
            $leibie = M('daoru_info')->field('resource_leibie')->where("resource_leibie != ''")->group('resource_leibie')->select();
            // dump($leibie);die;

            //查询活动
            $activity = M('activity')->field("id,name")->where("state=2 AND open=0")->order('id desc')->select();


            if (empty($activity)) {
                $activity = array(array('id' => '', 'name' => '暂无进行中的活动！'));
            }
            $source = M("Source")->field('name')->select();

            $data = array();


            foreach ($govern as $k => $v) {
                $data[0][] = $v['ksname'];
            }

            foreach ($expert as $kk => $vv) {
                $data[1][] = $vv['zjname'];
            }


            foreach ($entity as $kkk => $vvv) {
                $data[2][] = $vvv['bzname'];
            }


            foreach ($channel as $a => $b) {
                $data[3][] = $b['name'];
            }

            foreach ($source as $aa => $bb) {
                $data[4][] = $bb['name'];
            }

            $chufuzhen = array(array('name' => '初诊'), array('name' => '复诊'));
            $daozhen = array(array('name' => '到诊'), array('name' => '未到诊'));
            $xiaofei_qingkuang = array(array('name' => '消费'), array('name' => '未消费'));
            $zhuyuan_qingkaung = array(array('name' => '住院'), array('name' => '未住院'));

            foreach ($chufuzhen as $aaa => $bbb) {
                $data[5][] = $bbb['name'];
            }

            foreach ($daozhen as $c => $d) {
                $data[6][] = $d['name'];
            }

            foreach ($xiaofei_qingkuang as $cc => $dd) {
                $data[7][] = $dd['name'];
            }

            foreach ($zhuyuan_qingkaung as $ccc => $ddd) {
                $data[8][] = $ddd['name'];
            }

            foreach ($leibie as $h => $g) {
                if ($g['resource_leibie'] != '' && $g['resource_leibie'] != 'null') {
                    $data[9][] = $g['resource_leibie'];
                }
            }


            //判断是不是ajax提交
            if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {

                if (!empty($post['xianshi']) && !empty($post['page_shu'])) {
                    $return = array();

                    $xianshi = $post['xianshi'];
                    $page_shu = $post['page_shu'];

                    $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;

                    $return['total_page'] = ceil($count / $xianshi);
                    $return['current_page'] = $page_shu;

                    $return['data'] = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('i.id,i.name,i.phone,a.yuyuetime,a.diagnosis')->where($tiaojian)->limit($limit)->order('a.id asc')->select();


                    $shuju = json_encode($return);

                    $this->ajaxReturn($shuju);
                } else if (!empty($post['dianqian_page']) && !empty($post['xianshi_tiao'])) {


                    $return = array();

                    $xianshi = $post['xianshi_tiao'];

                    $page_shu = $post['dianqian_page'];

                    $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;
                    $return['total_page'] = ceil($count / $xianshi);
                    $return['current_page'] = $page_shu;
                    $return['data'] = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('i.id,i.name,i.phone,a.yuyuetime,a.diagnosis')->where($tiaojian)->limit($limit)->order('a.id asc')->select();

                    $shuju = json_encode($return);

                    $this->ajaxReturn($shuju);
                } else if (!empty($post['page']) && !empty($post['current_page'])) {
                    $return = array();

                    $xianshi = $post['page'];

                    $page_shu = $post['current_page'];

                    $limit = ($page_shu - 1) * $xianshi . ',' . $xianshi;
                    $return['total_page'] = ceil($count / $xianshi);
                    $return['current_page'] = $page_shu;

//                    $return['data'] = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('a.*')->where($tiaojian)->limit($limit)->select();
                    $return['data'] = $model->join('yuyue_patient as a on i.patient_id = a.id')->field('i.id,i.name,i.phone,a.yuyuetime,a.diagnosis')->where($tiaojian)->limit($limit)->order('a.id asc')->select();

                    $shuju = json_encode($return);

                    $this->ajaxReturn($shuju);
                }
            }

          // dump($canshu);die;
            $result = json_encode($result);

      

            $assign = array('xianshi' => $xianshi, 'canshu' => $canshu, 'total_page' => $total_page, 'current_page' => $page_shu, 'data' => $result, 'area' => $area, 'expert' => $expert, 'govern' => $govern, 'entity' => $entity, 'channel' => $channel, 'source' => $source, 'activity' => $activity, 'pic' => json_encode($data));
//        $this->page = $p->show();
 
            $this->assign($assign);
            $this->display();
        }
    }

    public function search()
    {
        $post = I('post.');
        // echo "<pre/>";
        // var_dump($post);die;
        $m = M('Patient as a');
        $where .= "a.recycle=0 AND a.huawu<>88";
        if (IS_POST) {
            // $post = I('post.');
            if (!empty($post['start'])) {
                $start_time = strtotime(trim($post['start']));
                $where .= " AND a.yuyuetime>'$start_time'";
            }
            if (!empty($post['end'])) {
                $end_time = strtotime(trim($post['end']));
                $where .= " AND a.yuyuetime<'$end_time'";
            }
            if (!empty($post['name'])) {
                $name = $post['name'];
                $where .= " AND a.name='$name'";
            }
            if (!empty($post['age_start'])) {
                $agestart = $post['age_start'];
                $where .= " AND a.age>='$agestart'";
            }
            if (!empty($post['age_end'])) {
                $ageend = $post['age_end'];
                $where .= " AND a.age<='$ageend'";
            }
            // if(!empty($post['phone']))
            // {
            //     $phone = $post['phone'];
            //     $where .= " AND (a.phone='$phone' OR a.phones='$phone')";
            // }
            // if(!empty($post['identify']))
            // {
            //     $identify = $post['identify'];
            //     $where .= " AND a.identify='$identify'";
            // }
            // if(!empty($post['area']))
            // {
            //     $area = $post['area'];
            //     $where .= " AND a.address like '%". $area . "%'";
            // }
            if (!empty($post['yuyuezj'])) {
                $yuyuezj = $post['yuyuezj'];
                $where .= " AND a.yuyuezj='$yuyuezj'";
            }
            if (!empty($post['yuyuebz'])) {
                $yuyuebz = $post['yuyuebz'];
                $where .= " AND a.yuyuebz='$yuyuebz'";
            }
            if (!empty($post['channel'])) {
                $channel = $post['channel'];
                $where .= " AND a.channel='$channel'";
            }
            if (!empty($post['source'])) {
                $source = $post['source'];
                $where .= " AND a.source='$source'";
            }
            if (!empty($post['again'])) {
                $again = $post['again'];
                if ($again == 1) {
                    $where .= " AND a.again=0";
                } elseif ($again == 2) {
                    $where .= " AND a.again=1";
                }
            }
            if (!empty($post['diagnosis'])) {
                $diagnosis = $post['diagnosis'];
                if ($diagnosis == 1) {
                    $where .= " AND a.diagnosis=0";
                } elseif ($diagnosis == 2) {
                    $where .= " AND a.diagnosis=1";
                }
            }
            if (!empty($post['consumption'])) {
                $consumption = $post['consumption'];
                if ($consumption == 1) {
                    $where .= " AND a.consumption=0";
                } elseif ($consumption == 2) {
                    $where .= " AND a.consumption=1";
                }
            }
            if (!empty($post['hospital'])) {
                $hospital = $post['hospital'];
                if ($hospital == 1) {
                    $where .= " AND a.hospital=0";
                } elseif ($hospital == 2) {
                    $where .= " AND a.hospital=1";
                }
            }
            if (!empty($post['type'])) {
                $type = $post['type'];
                if ($type == 1) {
                    $where .= " AND a.type=0";
                } elseif ($type == 2) {
                    $where .= " AND a.type=1";
                }
            }
            if (!empty($post['start_time'])) {
                $start_time_two = strtotime(trim($post['start_time']));
                $where .= " AND a.time>'$start_time_two'";
            }
            if (!empty($post['end_time'])) {
                $end_time_two = strtotime(trim($post['end_time']));
                $where .= " AND a.time<'$end_time_two'";
            }
            $_SESSION['where'] = $where;
        } else {
            $where = $_SESSION['where'];
        }
        $p = getpage($m, $where, 10);

        $result = $m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->order('a.id desc')->select();
        $area = M('area')->field('id,area_name')->where("area_parent_id=0")->select();
        $expert = M("Zj")->field('zjname')->select();

        $govern = M("Ks")->field('ksname')->select();

        $entity = M("Bz")->field('bzname')->select();

        $channel = M("mode")->field('name')->where("recycle=0")->select();
        $activity = M('activity')->field("id,name")->where("state=2 AND open=0")->order('id desc')->select();
        // echo "<pre/>";
        // var_dump($activity);die;
        if (empty($activity)) {
            $activity = array(array('id' => '', 'name' => '暂无进行中的活动！'));
        }
        $source = M("Source")->field('name')->select();
        $assign = array('data' => $result, 'area' => $area, 'expert' => $expert, 'govern' => $govern, 'entity' => $entity, 'channel' => $channel, 'source' => $source, 'condition' => $where, 'activity' => $activity);
        $this->page = $p->show();
        $this->assign($assign);
        $this->display();
    }

    public function edit()
    {
        $make = M("Patient");
        if (IS_POST) {
            $data = I('post.');
            $province = $data['address'];
            $city = $data['city'];
            $zone = $data['zone'];
            $pwhere = "area_name='$province' and area_parent_id=0";
            $cwhere = "area_name='$city' and area_parent_id<>0";
            $zwhere = "area_name='$zone' and area_parent_id<>0";
            $m = M('area');
            $pid = $m->field('id')->where($pwhere)->find();
            $pid = $pid['id'];
            $cid = $m->field('id')->where($cwhere)->find();
            $cid = $cid['id'];
            $zid = $m->field('id')->where($zwhere)->find();
            $zid = $zid['id'];
            $data['address'] = $pid . ',' . $cid . ',' . $zid;
            unset($data['city']);
            unset($data['zone']);
            if ($data['name'] == "") {
                $this->error('请填写患者姓名');
                die;
            }
            $where = 'id=' . $data['id'];
            $make = $make->where($where)->save($data);
            if ($make) {
                $this->success('修改成功', U('show'));
                die;
            } else {
                $this->error('修改失败');
                die;
            }

        } elseif (IS_GET) {

            $data = I('get.');

            // dump($data);die;

            $make = $make->where($data)->find();
            $address = $make['address'];
            $address = explode(',', $address);
            $pid = $address[0];
            $cid = $address[1];
            $zid = $address[2];
            $am = M('area');
            $province = $am->field('area_name')->where("id='$pid'")->find();
            $province = $province['area_name'];
            $city = $am->field('area_name')->where("id='$cid'")->find();
            $city = $city['area_name'];
            $rcity = $am->field('area_name')->where("area_parent_id='$pid'")->select();
            $zone = $am->field('area_name')->where("id='$zid'")->find();
            $zone = $zone['area_name'];
            $rzone = $am->field('area_name')->where("area_parent_id='$cid'")->select();
            $make['province'] = $province;
            $make['city'] = $city;
            $make['zone'] = $zone;
        }

        $govern = M("Ks")->field('ksname')->select();

        $expert = M("Zj")->field('zjname')->select();
        $address = M("area")->field("id,area_name")->where("area_parent_id=0")->select();
        $entity = M("Bz")->field('bzname')->select();

        $channel = M("mode")->field('name')->where("recycle=0")->select();
        $modename = $make['channel'];
        $modeid = M("mode")->field('id')->where("recycle=0 and name='$modename'")->find();
        $modeid = $modeid['id'];
        $source = M("Sources")->field('name')->where("recycle=0 and pid=0 and sid='$modeid'")->select();
        $this->make = $make;
        $make = array($make);
        $assign = array(
            'data' => $make,
            'address' => $address,
            'province' => $province,
            'city' => $city,
            'rcity' => $rcity,
            'zone' => $zone,
            'rzone' => $rzone,
            'govern' => $govern,
            'entity' => $entity,
            'expert' => $expert,
            'govern' => $govern,
        );

        $this->assign($assign);
        $this->display();
    }

    public function delete()
    {
        $id = I('get.id');
        $result = D('Patient')->deleteAnalyze($id);
        if ($result) {
            $this->success('删除成功', U('show'));
        } else {
            $this->error('删除失败', U('show'));
        }
    }

    public function sendWXMsg($data, $first, $remake, $url)
    {

        foreach ($data as $key => $value) {
            $openid[$key]['openid'] = $value['openid'];
            if (empty($value['addtime'])) {
                $time = date('Y年m月d日 H:i:s', time());
            } else {
                $time = date('Y年m月d日 H:i:s', $value['addtime']);
            }
            if (empty($value['name'])) {
                $name = '优惠活动';
            } else {
                $name = $value['name'];
            }
            $openid[$key]['msg'] = array(
                'first' => array('value' => urlencode($first), 'color' => ''),
                'keyword1' => array('value' => urlencode($name), 'color' => ''),
                'keyword2' => array('value' => $time, 'color' => ''),
                'remark' => array('value' => urlencode($remake), 'color' => ''),
            );
        }

        // $url = 'pc.tianjianh.cn';
        Vendor('WeixinMsg.WeixinMsg');
        $wxtlp = new \wxmsg\WeiXin();
        if (is_array($openid)) {
            foreach ($openid as $value) {
                $template = $wxtlp->template($value['openid'], 4, $value['msg'], $url);
                $res[] = $wxtlp->send_template_message(urldecode(json_encode($template)));
            }
        } else {
            $template = $wxtlp->template($openid, 4, $msg, $url);
            $res = $wxtlp->send_template_message(urldecode(json_encode($template)));
        }


        // echo "<pre/>";
        // var_dump($res);die;
        // $data=json_decode($res,true);
        foreach ($res as $key => $value) {
            $data[] = json_decode($value, true);
        }


        foreach ($data as $key => $value) {
            var_dump($value);
            if ($value['errmsg'] == 'ok') {
                $return = true;
                return $return;

            }
        }
        return $return;
    }

    private function sendMail($address, $title, $content)
    {
        require('./ThinkPHP/Library/Vendor/phpmailer/class.phpmailer.php');
        require('./ThinkPHP/Library/Vendor/phpmailer/class.smtp.php');
        require('./ThinkPHP/Library/Vendor/phpmailer/class.pop3.php');
        try {
            $mail = new \PHPMailer(true);
            $mail->IsSMTP();
            $mail->SMTPSecure = 'ssl';
            $mail->CharSet = 'UTF-8';
            $mail->SMTPAuth = true; //开启认证
            $mail->Port = 465;    //网易为25
            $mail->Host = "smtp.qq.com";
            $mail->Username = "3221404459";    //qq此处为邮箱前缀名  163为邮箱名
            $mail->Password = "vsbdsoevcygedahg";
            $mail->AddReplyTo("3221404459@qq.com", "first");//回复地址
            $mail->From = "3221404459@qq.com";
            $mail->FromName = '3221404459';
            if (is_array($address)) {
                foreach ($address as $addressv) {
                    $mail->AddAddress($addressv);
                }
            } else {
                $mail->AddAddress($address);
            }
            $mail->Subject = $title;
            $mail->Body = $content;
            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示
            $mail->WordWrap = 80; // 设置每行字符串的长度
            //$mail->AddAttachment("f:/test.png"); //可以添加附件
            $mail->IsHTML(true);
            $mail->Send();
            // echo '发送成功';
        } catch (phpmailerException $e) {
            $e->errorMessage();
        }
    }

    private function sendMsg($phone, $url, $model_biaoshi)
    {

        if (empty($phone)) return json_encode(array('Message' => '缺少参数', 'Code' => 'Error'));
        if (!preg_match("/^1[34578]{1}\d{9}$/", $phone)) return array('Message' => '无效的手机号', 'Code' => 'Error');
        require_once './ThinkPHP/Library/Vendor/dysms/vendor/autoload.php';
        Config::load();             //加载区域结点配置
        $config = C('dysms');       //取出参数配置
        $accessKeyId = 'LTAIXQBET5tRo5VB';
        $accessKeySecret = 'i0WxMfl7K6SKOksWVFTgTIrXKJjw8I';
        // $templateParam = array('url' => $url); //模板变量替换

        $signName = '北京天健医院';
        $templateCode = $model_biaoshi;   //短信模板ID
        $product = "Dysmsapi";
        //短信API产品域名（接口地址固定，无需修改）
        $domain = "dysmsapi.aliyuncs.com";
        //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
        $region = "cn-hangzhou";
        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
        // 增加服务结点
        DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
        // 初始化AcsClient用于发起请求
        $acsClient = new DefaultAcsClient($profile);
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phone);
        // 必填，设置签名名称
        $request->setSignName($signName);
        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);
        // 可选，设置模板参数
        if ($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        //发起访问请求
        $acsResponse = $acsClient->getAcsResponse($request);
        //返回请求结果
        $result = json_encode($acsResponse);

        return $result;
    }

    public function sendMessage()
    {

        $post = I('post.');


        $activity = $post['activity'];
        $send_way = $post['send_way'];


        if (!is_numeric($activity)) {
            $activity = M('activity')->where("name='$activity'")->getField('id');

        }

        if (empty($activity)) {
            $return['code'] = '200';
            $return['msg'] = 'NO MATCHING ACTIVITY';
            $this->ajaxReturn($return);
            die;
        }

        if (!empty($post['id'])) {
            $patient_id = $post['id'];
        }


        if(!empty($post['biao'])){
            $biao = $post['biao'];
        }

        $condition = htmlspecialchars_decode($post['condition']);
        $activity_info = M('activity')->where("id='$activity'")->find();
        // echo "<pre/>";
        // var_dump($activity_info);die;
        $activity_name = $activity_info['name'];
        $activity_detail = $activity_info['jianjie'];
        $activity_url = $activity_info['a_url'];
        $activity_short_url = $activity_info['url'];
        $m = M('patient as a');
        $hpa = M('HistoryPushActivities');
        $history['activity_id'] = $activity;
        $history['userid'] = json_encode($post['id']);

        $model = M('daoru_info as i');

        // foreach($send_way as $k=>$v){
        for ($i = 0; $i < count($send_way); $i++) {
            // $history['send_id'] = $send_way[$i];
            // $history['send_id'] = $send_way[$i];
            $v = $send_way[$i];

            if ($v == 3) {
                $add = " AND a.ordernum is not null";
                $where = $condition . $add;
                $history['user_condition'] = $where;
                $history['send_id'] = $v;

                $result = $m->join('yuyue_online_wxuser as w on a.userid=w.id')->field('w.openid,w.name,w.addtime')->where($where . " AND (w.id=3 OR w.id=16)")->group("w.id")->select();
                if (!$result) {
                    $return['code'] = 200;
                    $return['msg'] = 'NO ELIGIBLE PATIENTS';
//                    $this->ajaxReturn($return);
//                    die;
                }
                $res = $this->sendWxMsg($result, $activity_name, $activity_detail, $activity_url);

                if ($res) {
                    $history['success'] = 1;
//                    $history['ts_time'] = time();
//                    $hpa->add($history);
                    $return['code'] = 100;
                    $return['msg'] = 'SUCCESS';
                } else {
                    $history['success'] = 0;
//                    $history['ts_time'] = time();
//                    $hpa->add($history);
                    $return['code'] = 200;
                    $return['msg'] = 'FAIL';
                }
//                $this->ajaxReturn($return);
            } else if ($v == 1) {

                $where = $condition;

                $history['user_condition'] = $where;
                $history['send_id'] = $v;
                $result = array();
                 foreach($patient_id as $k=>$v){

//                     $where .= " and id = '".$v."'";
                     if($biao == 1){
                         $result[] = $m->field('a.name,a.phone')->where(['id'=>$v])->group('a.phone')->find();
                     }else if($biao == 2){
                         $result[] = $model->field('i.name,i.phone')->where(['id'=>$v])->group('i.phone')->find();
                     }
                 }

//               $result = $m->field('a.name,a.phone')->where($where . " AND (a.phone='18031476775' OR a.phone='15138897908')")->group('a.phone')->limit('20000')->select();
//                 $result = $m->field('a.name,a.phone')->where($where . " AND (a.phone='15986621466' OR a.phone='15138897908')")->group('a.phone')->limit('20000')->select();

//              $result[] = $m->field('a.name,a.phone')->where($where . " AND (a.phone='18031476775' OR a.phone='15636738017')")->group('a.phone')->limit('20000')->select();

                foreach ($result as $key => $value) {
                    $res[] = $this->sendMsg($value['phone'], $activity_url, $activity_short_url);
                }

                // $res[] = $this->sendMsg('18031476775', $activity_url,$activity_short_url);
                foreach ($res as $key => $value) {
                    $data[] = json_decode($value, true);
                }

                foreach ($data as $key => $value) {
                    if ($value['Message'] == 'OK') {
                        $success = 1;
                        $return['code'] = 100;
                        $return['msg'] = 'SUCCESS';
//                        die;
                    } else {
                        $success = 0;
                        $return['code'] = 200;
                        $return['msg'] = $value['Message'];
//                        die;
                    }
                }
            } else if ($v == 2) {

                $add = " AND a.ordernum is not null";
                $where = $condition . $add;
                $history['user_condition'] = $where;
                $history['send_id'] = $v;
                $result = $m->join('yuyue_online_wxuser as w on a.userid=w.id')->field('w.id')->where($where . " AND (w.id=3 OR w.id=16)")->group('w.id')->select();
                if (!$result) {
                    $return['code'] = 200;
                    $return['msg'] = 'NO ELIGIBLE PATIENTS';
                }
                $om = M('online_activity');
                $data['activity_id'] = $activity;
                $data['addtime'] = time();
                foreach ($result as $key => $value) {
                    $data['user_id'] = $value['id'];
                    $res[] = $om->add($data);
                }
                if ($res) {
//                    $history['success'] = 1;
//                    $history['ts_time'] = time();
//                    $hpa->add($history);
                    $return['code'] = 100;
                    $return['msg'] = 'SUCCESS';
                } else {
//                    $history['success'] = 0;
//                    $history['ts_time'] = time();
//                    $hpa->add($history);
                    $return['code'] = 200;
                    $return['msg'] = 'FAIL';
                }
            }


//            switch ($v) {
//                case '1':
//                    $add = " AND a.ordernum is not null";
//                    $where = $condition . $add;
//                    $history['user_condition'] = $where;
//                    $hpa->add($history);
//                    $result = $m->join('yuyue_online_wxuser as w on a.userid=w.id')->field('w.openid,w.name,w.addtime')->where($where . " AND (w.id=3 OR w.id=16)")->group("w.id")->select();
//                    if (!$result) {
//                        $return['code'] = 200;
//                        $return['msg'] = 'NO ELIGIBLE PATIENTS';
//                        $this->ajaxReturn($return);
//                        die;
//                    }
//                    $res = $this->sendWxMsg($result, $activity_name, $activity_detail, $activity_url);
//                    // $return = json_code()
//                    if ($res) {
//                        $return['code'] = 100;
//                        $return['msg'] = 'SUCCESS';
//                    } else {
//                        $return['code'] = 200;
//                        $return['msg'] = 'FAIL';
//                    }
//                    $this->ajaxReturn($return);
//                    break;
//                case '2':
//                    $where = $condition;
//                    $history['user_condition'] = $where;
//                    $hpa->add($history);
//
////                    $result = $m->field('a.name,a.phone')->where($where . " AND (a.phone='18031476775' OR a.phone='15138897908')")->group('a.phone')->limit('20000')->select();
//                    $result = $m->field('a.name,a.phone')->where($where . " AND (a.phone='15138897908')")->group('a.phone')->limit('20000')->select();
////                    $result = $m->field('a.name,a.phone')->where($where . " AND (a.phone='15986621466' OR a.phone='15138897908')")->group('a.phone')->limit('20000')->select();
//
//                    foreach ($result as $key => $value) {
//                        $res[] = $this->sendMsg($value['phone'], $value['name'], $activity_detail, $activity_short_url);
//                    }
//                    foreach ($res as $key => $value) {
//                        $data[] = json_decode($value, true);
//                    }
//                    foreach ($data as $key => $value) {
//                        if ($value['Message'] == 'OK') {
//                            $return['code'] = 100;
//                            $return['msg'] = 'SUCCESS';
//                            die;
//                        } else {
//                            $return['code'] = 200;
//                            $return['msg'] = $value['Message'];
//                            die;
//                        }
//                    }
//                    $this->ajaxReturn($return);
//                    break;
//                case '3':
//                    $add = " AND a.ordernum is not null";
//                    $where = $condition . $add;
//                    $history['user_condition'] = $where;
//                    $hpa->add($history);
//                    // $where = $condition;
//                    $result = $m->join('yuyue_online_wxuser as w on a.userid=w.id')->field('w.id')->where($where . " AND (w.id=3 OR w.id=16)")->group('w.id')->select();
//                    if (!$result) {
//                        $return['code'] = 200;
//                        $return['msg'] = 'NO ELIGIBLE PATIENTS';
//                        $this->ajaxReturn($return);
//                        die;
//                    }
//                    $om = M('online_activity');
//                    // $data['user_id'] = $send_way;
//                    $data['activity_id'] = $activity;
//                    $data['addtime'] = time();
//                    foreach ($result as $key => $value) {
//                        $data['user_id'] = $value['id'];
//                        $res[] = $om->add($data);
//                    }
//                    if ($res) {
//                        $return['code'] = 100;
//                        $return['msg'] = 'SUCCESS';
//                    } else {
//                        $return['code'] = 200;
//                        $return['msg'] = 'FAIL';
//                    }
//                    $this->ajaxReturn($return);
//                    break;
//                case '4':
//                    # code...
//                    break;
//                default:
//                    # code...
//                    break;
//            }
        }
        $history['success'] = $success;
        $history['ts_time'] = time();
        $hpa->add($history);

        $this->ajaxReturn($return);
    }
}



