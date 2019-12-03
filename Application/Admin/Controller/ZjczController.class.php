<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 数据查询控制器
 */
class ZjczController extends AdminBaseController{
    /**
     * 首页
     */
    public function index(){
        $zj=M("Zj")->select();
        $assign=array(
            'data'=>$zj
            );
        $this->assign($assign);
        $this->display();
    }
    public function zjcz(){
        if(IS_POST){
            // echo "<pre/>";
            $post = I('post.');
        // var_dump($post);die;

            $aid = $post['zid'];
            $next = $post['next'];
            $week = I('post.week');
            $tnum = $post['ticket'];
            if($post['ticket'] == '')
            {
                // echo 1;die;
                $this -> error('号数不能为空',U('zjcz',array('id'=>$aid)));
            }
            // echo $tnum;die;
            // var_dump($aid);
            // var_dump($next);
            // var_dump($week);die;
            $week = explode(',',$week);
            $swcz = array_slice($week,0,7);//截取上午的一周数据
            $xwcz = array_slice($week,7,7);//截取下午的一周数据
            if($next == 1){//判断是否是下月
                $aaa = date('Y-m');
                $month = strtotime(getNextMonthDays($aaa));
                $year_month = date('Y-m',$month);
                /*取出所有用户选择星期的日期*/
                foreach ($swcz as $key => $value) {
                    if($value != '0'){
                        $swval[] =  getAllMondayOf($key,$year_month);
                    }
                }
                foreach ($xwcz as $key => $value) {
                    if($value != '0'){
                        $xwval[] =  getAllMondayOf($key,$year_month);
                    }
                }
            }else{//本月
                $month = strtotime(date('Y-m'));
                /*取出所有用户选择日期*/
                foreach ($swcz as $key => $value) {
                    if($value != 0){
                        $swval[] =  getAllMondayOf($key);
                    }
                }
                foreach ($xwcz as $key => $value) {
                    if($value != '0'){
                        $xwval[] =  getAllMondayOf($key);
                    }
                }
            }
            //合并上、下午星期的数组如(周一是个数组、周二是个数组合并他两)
            $sw = array_reduce($swval, function ($result, $value) {
                    return array_merge($result, array_values($value));
                }, array());
            $xw = array_reduce($xwval, function ($result, $value) {
                    return array_merge($result, array_values($value));
                }, array());
            sort($sw);//排序
            sort($xw);
            //组装余号表参数存余号表
            if(empty($sw) && empty($xw)){
                if($next == 1){
                    $this -> error('请选择星期后操作！',U('zjcz',array('id' => $aid,'next' => 1)));die;
                }else{
                    $this -> error('请选择星期后操作！',U('zjcz',array('id' => $aid)));die;
                }
            }
            if(empty($sw)){
                for ($i=0; $i <count($xw) ; $i++) { 
                    $ticket[$i]['date'] = $xw[$i];
                    $ticket[$i]['czsj'] = 2;
                    }
            }
            if(empty($xw)){
                for ($i=0; $i <count($sw) ; $i++) { 
                    $ticket[$i]['date'] = $sw[$i];
                    $ticket[$i]['czsj'] = 1;
                    }
            }
            if(!empty($sw) && !empty($xw)){
                $sect = array_intersect($sw,$xw);
                $shw = array_diff($sw,$sect);
                $xiaw = array_diff($xw,$sect);
                sort($shw);
                sort($xiaw);
                sort($sect);

                for ($i=0; $i <count($shw) ; $i++) { 
                    $aa[$i]['date'] = $shw[$i];
                    $aa[$i]['czsj'] = 1;
                }
                for ($i=0; $i <count($xiaw) ; $i++) { 
                    $bb[$i]['date'] = $xiaw[$i];
                    $bb[$i]['czsj'] = 2;
                }
                for ($i=0; $i <count($sect) ; $i++) { 
                    $cc[$i]['date'] = $sect[$i];
                    $cc[$i]['czsj'] = 3;
                }

                if(empty($aa) && !empty($bb) && !empty($cc)){
                    $ticket = array_merge($bb,$cc);
                }elseif(!empty($aa) && empty($bb) && !empty($cc)){
                    $ticket = array_merge($aa,$cc);
                }elseif(!empty($aa) && !empty($bb) && empty($cc)){
                    $ticket = array_merge($aa,$bb);
                }elseif(!empty($aa) && empty($bb) && empty($cc)){
                    $ticket = $aa;
                }elseif(empty($aa) && !empty($bb) && empty($cc)){
                    $ticket = $bb;
                }elseif(empty($aa) && empty($bb) && !empty($cc)){
                    $ticket = $cc;
                }elseif(!empty($aa) && !empty($bb) && !empty($cc)){
                    $ticket = array_merge($aa,$bb,$cc);
                }elseif(empty($aa) && empty($bb) && empty($cc)){
                    $this -> error('不可能的情况');
                }
            }
            // echo "<pre/>";
            // var_dump($bb);die;
            // sort($ticket);
            $gdmonth = strtotime(getNextMonthDays(date('Y-m')));
            $nextmonth = date('Y-m',$gdmonth);
            if($month < $gdmonth){
                foreach ($ticket as $key => $value) {
                    $ticketa[$key]['date'] = strtotime(date('Y-m') . '-' . $value['date']);
                    $ticketa[$key]['czsj'] =  $value['czsj'];
                    }
            }elseif($month == $gdmonth){
                foreach ($ticket as $key => $value) {
                    $ticketa[$key]['date'] = strtotime($nextmonth . '-' . $value['date']);
                    $ticketa[$key]['czsj'] =  $value['czsj'];
                }
            }
            // echo "<pre/>";
            // var_dump($ticketa);die;
            $zzz = M('online_ticket') -> field('date,visits,ticket,ticketall') -> where("expert='$aid' and month='$month' and recycle=0") -> select();
            // echo $tnum;die;
            //判读数据库是否有数据
            if(empty($zzz)){
                foreach ($ticketa as $key => $value) {

                    $data['expert'] = $aid;
                    $data['date'] = $value['date'];
                    $data['visits'] = $value['czsj'];
                    $data['month'] = $month;
                    $data['ticket'] = $tnum;
                    $data['ticketall'] = $tnum;
                    $data['addtime'] = time();
                    $data['uptime'] = time();
                    $abcd[] = M('online_ticket') -> data($data) -> add();
                }
            }else{
                $module = M('online_ticket');
                foreach ($zzz as $key => $value) {
                    if($value['ticket'] != $value['ticketall']){
                        // echo 1;die;
                        $acd = date('Y年m月d日',$value['date']);
                        $this -> error('有患者预约' . $acd . '号位确认后操作！');
                    }
                    $sc = $value['date'];
                    $where = "month='$month' and expert='$aid' and date='$sc'";
                    // var_dump($where);die;
                    $module -> recycle = 1;
                    $module -> uptime = time();
                    $abcd[] = $module -> where($where) -> save();
                    // $ab[] = intval($value['date']);
                }
                foreach ($ticketa as $key => $value) {

                    $data['expert'] = $aid;
                    $data['date'] = $value['date'];
                    $data['visits'] = $value['czsj'];
                    $data['month'] = $month;
                    $data['ticket'] = $tnum;
                    $data['ticketall'] = $tnum;
                    $data['addtime'] = time();
                    $data['uptime'] = time();
                    $abcd[] = M('online_ticket') -> data($data) -> add();
                }
                
            }
            //组装余号表参数存余号表
            // echo "<pre/>";
            // var_dump($abcd);die;
            // var_dump($ticket);
            $sw = implode(',',$sw);//转化为字符串
            $xw = implode(',',$xw);
            // echo $month;die;
            $where = "zjid='$aid' and month='$month'";
            //查询是否存在次条数据(如是否存在3月数据)
            $swjg = M('zjpb') -> field('pid,swcz,xwzc') -> where($where) -> select();
            if(empty($swjg)){//不存在存入数据库并跳转
                $data['zjid'] = $aid;
                $data['swcz'] = $sw;
                $data['xwzc'] = $xw;
                $data['month'] = $month;
                $data['addtime'] = time();
                $data['uptime'] = time();
                $abc = M('zjpb') -> data($data) ->add();
                if($abc){
                    if($next == 1){
                        header('location:' . U('zjcz',array('id' => $aid,'next' => 1)));
                    }else{
                        header('location:' . U('zjcz',array('id' => $aid)));
                    }
                }
            }else{//存在修改修改数据库跳转
                $data['swcz'] = $sw;
                $data['xwzc'] = $xw;
                $data['update'] = time();
                $cde = M('zjpb') -> where($where) -> save($data);
                if($cde){
                    if($next == 1){
                        header('location:' . U('zjcz',array('id' => $aid,'next' => 1)));
                    }else{
                        header('location:' . U('zjcz',array('id' => $aid)));
                    }
                }
            }
        }else{//不是post提交数据查询当月或下月数据回显页面

        $zid=I('get.id');
        $next = I('get.next');
        $_SESSION['zid'] = $zid;
        $_SESSION['next'] = $next;
        if(!empty($zid)){
            $expert=M("Zj")->field('id,zjname')->where('id='.$zid)->select();
        }
        // var_dump($expert[0]['id']);die;
        if(!empty($expert[0]['id'])){
            $zjname = $expert[0]['zjname'];
            $date=date('Y-m-1',time());
            // $dates=getNextMonthDays($date);
            // var_dump($dates);die;
            if($next==1){
                $dates=getNextMonthDays($date);
                $nowm = date('Y-m');
                $yuefen = date('Y年m月',strtotime(getNextMonthDays($nowm)));
                $dq = 1;
                $jc = 0;
                $jcyuefen = date('Y年m月');
                $Tmonth=get_day($dates,'2');//下月
                $dates=strtotime($dates);
                $xingqi = Date("w", $dates);
                $zjpb=M("Zjpb")->field()->where('zjid='.$zid.' and month='.$dates)->find();
            }else{
                $Tmonth=get_day($date,'2');//当月
                // var_dump($Tmonth);die;
                $yuefen = date('Y年m月');
                $nowm = date('Y-m');
                $jcyuefen = date('Y年m月',strtotime(getNextMonthDays($nowm)));
                $dq = 0;
                $jc = 1;
                $dates=strtotime(Date("Y-n-1"));
                $xingqi = Date("w", $dates);
                $zjpb=M("Zjpb")->field()->where('zjid='.$zid.' and month='.$dates)->find();
            }
            // $zjpb['swcz'] = '['.$zjpb['swcz'].']';
            // $zjpb['xwzc'] = '['.$zjpb['swcz'].']';
            // echo "<pre>";
            // var_dump($zjpb);die;
            $nowm = date('Y-m');
            $nextm = date('Y年m月',strtotime(getNextMonthDays($nowm)));
            $assign=array(
                'xingqi'=>$xingqi,
                'Tmonth'=>$Tmonth,
                'zjname' => $zjname,
                'zjpb'=>$zjpb,
                'zid'=>$zid,
                'nextm'=>$nextm,
                'next'=>$next,
                'yuefen' => $yuefen,
                'jcyuefen' => $jcyuefen,
                'dq' => $dq,
                'jc' => $jc
                );
            // echo "<pre/>";
            // var_dump($zjpb);die;
            $this->assign($assign);
            $this->display();
        }else{
            $this->error('信息错误');
        }
        }
    }

    public function getweek(){
        $post = I('post.');
        echo "<pre/>";
        var_dump($post);die;
        $zid = I('get.zid');//专家id
        $next = I('get.next');//是否是下月1表示下月
        $tnum = $post['ticket'];
        if($tnum == '')
        {
            $this -> error('号数不能为空',U('zjcz',array('id'=>$zid)));
        }
        // var_dump($zid);die;
        if($next == 1){
            $aaa = date('Y-m');
            $month = strtotime(getNextMonthDays($aaa));
        }else{
            $month = strtotime(date('Y-m'));
        }
        $where = "zjid='$zid' and month='$month'";

        if(!empty($post['swczj'])){
            $swczj = explode(',',$post['swczj']);
            foreach ($swczj as $key => $value) {
                if($value != 0){
                    $swc[] = $key+1;
                }
            }
            $sw = implode(',',$swc);
            $sdata['swcz'] = $sw;
            //查询是否存在次条数据(如是否存在3月数据)
            $swjg = M('zjpb') -> field('pid,swcz,xwzc') -> where($where) -> select();
            if(empty($swjg)){
                $data['zjid'] = $zid;
                $data['swcz'] = $sw;
                $data['xwzc'] = $xw;
                $data['month'] = $month;
                $data['addtime'] = time();
                $data['uptime'] = time();
                //存储数据
                $ace = M('zjpb') -> data($data) ->add();
            }else{//有次条数据则修改数据
                $ace = M('zjpb') -> where($where) -> save($sdata);
            }
            // var_dump($ace);
            // var_dump($sw);

        }
        //判断提交的数据是否为空(不为空修改)
        if(!empty($post['xwczj'])){
            $xwczj = explode(',',$post['xwczj']);
            foreach ($xwczj as $key => $value) {
                if($value != 0){
                    $xwc[] = $key+1;
                }
            }
            $xw = implode(',',$xwc);
            $xdata['xwzc'] = $xw;
            // $where = "";
            $swjg = M('zjpb') -> field('pid,swcz,xwzc') -> where($where) -> select();
            if(empty($swjg)){
                $data['zjid'] = $zid;
                $data['swcz'] = $sw;
                $data['xwzc'] = $xw;
                $data['month'] = $month;
                $data['addtime'] = time();
                $data['uptime'] = time();
                //存储数据
                $abe = M('zjpb') -> data($data) ->add();
            }else{//有此条数据则修改数据
                $abe = M('zjpb') -> where($where) -> save($xdata);
            }
            
            // if($abe){
            //  $this -> success('下午修改成功',U('zjcz'));
            // }
            // var_dump($abe);
        }
        // var_dump($ace);
        // echo "<br/>";
        // var_dump($abe);

        // die;
        if(!($ace || $abe)){
                $this -> error('修改失败',U('zjcz',array('id'=>$zid)));die;
            }
        if(empty($swc) && empty($xwc)){
            $this -> error('你不需要更多的操作！',U('zjcz',array('id' => $zid)));
        }
        if(empty($swc)){
            for ($i=0; $i <count($xwc) ; $i++) {
                $ticket[$i]['date'] = $xwc[$i];
                $ticket[$i]['czsj'] = 2;
            }
        }
        if(empty($xwc)){
            for ($i=0; $i <count($swc) ; $i++) {
                $ticket[$i]['date'] = $swc[$i];
                $ticket[$i]['czsj'] = 1;
            }
        }
        if(!empty($swc) && !empty($xwc)){
            $sect = array_intersect($swc,$xwc);
            $shw = array_diff($swc,$sect);
            $xiaw = array_diff($xwc,$sect);
            sort($shw);
            sort($xiaw);
            sort($sect);

            for ($i=0; $i <count($shw) ; $i++) { 
                $aa[$i]['date'] = $shw[$i];
                $aa[$i]['czsj'] = 1;
            }
            for ($i=0; $i <count($xiaw) ; $i++) { 
                $bb[$i]['date'] = $xiaw[$i];
                $bb[$i]['czsj'] = 2;
            }
            for ($i=0; $i <count($sect) ; $i++) { 
                $cc[$i]['date'] = $sect[$i];
                $cc[$i]['czsj'] = 3;
            }
            if(empty($aa) && !empty($bb) && !empty($cc)){
                    $ticket = array_merge($bb,$cc);
                }elseif(!empty($aa) && empty($bb) && !empty($cc)){
                    $ticket = array_merge($aa,$cc);
                }elseif(!empty($aa) && !empty($bb) && empty($cc)){
                    $ticket = array_merge($aa,$bb);
                }elseif(!empty($aa) && empty($bb) && empty($cc)){
                    $ticket = $aa;
                }elseif(empty($aa) && !empty($bb) && empty($cc)){
                    $ticket = $bb;
                }elseif(empty($aa) && empty($bb) && !empty($cc)){
                    $ticket = $cc;
                }elseif(!empty($aa) && !empty($bb) && !empty($cc)){
                    $ticket = array_merge($aa,$bb,$cc);
                }elseif(empty($aa) && empty($bb) && empty($cc)){
                    $this -> error('不可能的情况');
                }
        }



            $gdmonth = strtotime(getNextMonthDays(date('Y-m')));
            $nextmonth = date('Y-m',$gdmonth);
            if($month < $gdmonth){
                foreach ($ticket as $key => $value) {
                    $ticketa[$key]['date'] = strtotime(date('Y-m') . '-' . $value['date']);
                    $ticketa[$key]['czsj'] =  $value['czsj'];
                    }
            }elseif($month == $gdmonth){
                foreach ($ticket as $key => $value) {
                    $ticketa[$key]['date'] = strtotime($nextmonth . '-' . $value['date']);
                    $ticketa[$key]['czsj'] =  $value['czsj'];
                }
            }
            // foreach ($ticket as $key => $value) {
            //  $ticketa[$key]['date'] = strtotime(date('Y-m') . '-' . $value['date']);
            //  $ticketa[$key]['czsj'] =  $value['czsj'];
            // }
            $zzz = M('online_ticket') -> field('date,visits,ticket,ticketall') -> where("expert='$zid' and month='$month' and recycle=0") -> select();
            //判读数据库是否有数据
            if(empty($zzz)){
                foreach ($ticketa as $key => $value) {

                    $data['expert'] = $zid;
                    $data['date'] = $value['date'];
                    $data['visits'] = $value['czsj'];
                    $data['month'] = $month;
                    $data['ticket'] = $tnum;
                    $data['ticketall'] = $tnum;
                    $data['addtime'] = time();
                    $data['uptime'] = time();
                    $abcd[] = M('online_ticket') -> data($data) -> add();
                }
            }else{
                $module = M('online_ticket');
                foreach ($zzz as $key => $value) {
                    if($value['ticket'] != $value['ticketall']){
                        $acd = date('Y年m月d日',$value['date']);
                        $this -> error('有患者预约' . $acd . '号位确认后操作！');
                    }
                    $sc = $value['date'];
                    $where = "month='$month' and expert='$zid' and date='$sc'";
                        // var_dump($where);die;
                    $module -> recycle = 1;
                    $module -> uptime = time();
                    $abcd[] = $module -> where($where) -> save();
                }
                foreach ($ticketa as $key => $value) {
                    $data['expert'] = $zid;
                    $data['date'] = $value['date'];
                    $data['visits'] = $value['czsj'];
                    $data['month'] = $month;
                    $data['ticket'] = $tnum;
                    $data['ticketall'] = $tnum;
                    $data['addtime'] = time();
                    $data['uptime'] = time();
                    $abcd[] = M('online_ticket') -> data($data) -> add();
                }
                
            }
        //上午或者下午修改成功调表格页
        if($ace || $abe){
                $this -> success('修改成功',U('zjcz',array('id'=>$zid)));
            }
    }
    // public function getweek(){
    //  $week = I('post.week');
    //  // echo "<pre/>";
    //  // var_dump($week);
    //  $swcz = array_slice($week,0,7);
    //  $xwcz = array_slice($week,7,7);
    //  /*取出所有用户选择日期*/
    //  foreach ($swcz as $key => $value) {
    //      if($value !== '0'){
    //          $swval[] =  getAllMondayOf($key);
    //      }
    //  }
    //  foreach ($xwcz as $key => $value) {
    //      if($value !== '0'){
    //          $xwval[] =  getAllMondayOf($key);
    //      }
    //  }
    //  $sw = array_reduce($swval, function ($result, $value) {
 //             return array_merge($result, array_values($value));
    //      }, array());
    //  $xw = array_reduce($xwval, function ($result, $value) {
 //             return array_merge($result, array_values($value));
    //      }, array());
    //  sort($sw);
    //  sort($xw);
    //  $sw = implode(',',$sw);
    //  $xw = implode(',',$xw);
    //  $zjpb[sw] = $sw;
    //  $zjpb[xw] = $xw;
    //  // $key = 1;
    //  // $aaa = getAllMondayOf($key);
    //  // echo "<pre/>";
    //  // // var_dump($swcz);abc
    //  // var_dump($xw);die;
    //  $this -> ajaxReturn($zjpb);

    // }


}
