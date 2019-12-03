<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

class FuZhenController extends AdminBaseController{
	public function index(){
		$m=M("fz");
		$where = " recycle=0";
		$p=getpage($m,$where,10);
		$data=$m->where($where)->order('kzrq asc')->select();
		$this->page=$p->show();
		$this -> assign('data',$data);
		// var_dump($data);die;
		$this -> display();
	}

	public function search(){
		$post = I('post.');
		$name = $post['name'];
		$phone = $post['phone'];
		$where = "name='$name' and phone='$phone'";
		if(empty($name)){
			$where = "phone='$phone'";
		}elseif(empty($phone)){
			$where = "name='$name'";
		}
		// echo $
		$m=M("fz");
		$p=getpage($m,$where,10);
		// echo $where;die;
		$data=$m->where($where)->order('kzrq desc')->select();
		$this->page=$p->show();
		$this -> assign('data',$data);
		$this -> display('index');
	}

	public function edit(){
		if(IS_POST){
			$post = I('post.');
			// var_dump($post);die;
			$id = $post['id'];
			$make = M('fz');
			$post = I('post.');
			$post['kzrq'] = strtotime($post['kzrq']);
			$post['hfrq'] = strtotime($post['hfrq']);
			$post['fzrq'] = strtotime($post['fzrq']);
			$where = "id='$id'";
	        $result = $make->where($where)->save($post);
			// var_dump($result);die;
			if ($result !== false) {
				$this->success('修改成功',U('Admin/FuZhen/index'));
			}else{
				$this->error('修改失败');
			}
		}else{
			$id=I('get.id');
			$where = 'id='.$id; 
			$data = M('fz') -> where($where) -> select();
			// var_dump($data);die;
			$expert=M("Zj")->field('zjname')->select();
			$assign = array(
				'data' => $data,
				'expert' => $expert,
				);
			$this -> assign($assign);
			$this -> display();
		}
	}

	public function delete(){
		$id=I('get.id');
		$where = 'id='.$id; 
		$make = M("fz"); // 实例化User对象
        $make->recycle = 1;
        $make=$make->where($where)->save();
        // dump($make);die;
		if (!empty($make)) {
			$this->success('删除成功',U('Admin/FuZhen/index',array('sign'=>2)));
		}else{
			$this->error('删除失败');
		}
	}
	public function insert(){
		if(IS_POST){
			$post = I('post.');
			// echo "<pre>";
			// var_dump($post);die;
			$data['name'] 	= $post['name'];
			$data['gender'] = $post['gender'];
			$data['age'] 	= $post['age'];
			$data['expert'] = $post['expert'];
			$data['kzrq'] 	= strtotime($post['kzrq']);
			$data['address']= $post['address'];
			$data['phone'] 	= $post['phone'];
			$data['illness']= $post['illness'];
			$data['jcf'] 	= $post['jcf'];
			$data['ghf'] 	= $post['ghf'];
			$data['zlcs'] 	= $post['zlcs'];
			$data['zlfy'] 	= $post['zlfy'];
			$data['zyfs'] 	= $post['zyfs'];
			$data['zyfy'] 	= $post['zyfy'];
			$data['dj'] 	= $post['dj'];
			$data['wyfs'] 	= $post['wyfs'];
			$data['wyfy'] 	= $post['wyfy'];
			$data['xyfy'] 	= $post['xyfy'];
			$data['all'] 	= $post['all'];
			$data['hfrq'] 	= strtotime($post['hfrq']);
			$data['hfqk'] 	= $post['hfqk'];
			$data['fzrq'] 	= strtotime($post['fzrq']);
			$data['lrrq'] 	= time();
			$data['source'] = $post['source'];
			$result = M('fz') -> data($data) -> add();
			if ($result) {
				$this->success('添加成功',U('Admin/FuZhen/insert',array('sign'=>2)));
			}else{
				$this->error('添加失败');
			}
		}else{
			$expert=M("Zj")->field('zjname')->select();
			$assign = array(
				'expert' => $expert,
				);
			$this->assign($assign);
			$this -> display();
		}
	}

	public function excel(){
		$expert=M("Zj")->field('zjname')->select();
		$assign = array(
				'expert' => $expert,
			);
		$this->assign($assign);
		$this -> display();
	}

	public function exe_export(){
		$post = I('post.');
		$start = strtotime($post['start']);
		$end = strtotime($post['end']);
		$expert = $post['expert'];
		if(empty($end)){
			$end = time();
		}
		$namestart = date('Y年m月d日',$start);
		$nameend = date('Y年m月d日',$end);
		$where = "expert='$expert' and kzrq > '$start' and kzrq < '$end'";
		if(empty($expert)){
			$where = "kzrq > '$start' and kzrq < '$end'";
		}elseif(empty($start)){
			$where = "expert='$expert'";
		}
		// var_dump($where);die;
		// $where = ;
		$m=M("fz");
		$result=$m-> where($where) -> order('kzrq asc')->select();
// 		echo "<pre>";
// var_dump($result);die
// echo "<pre>";
// var_dump($result);die;
		$data[]=array('日期','来源','姓名','性别','年龄','专家','家庭住址','电话','诊断','检查费','挂号费','治疗次数','治疗金额','中药副数','中药金额','代煎','外用药副数','外用药金额','中成药/西药','合计','回访日期','回访情况','复诊日期');
			foreach ($result as $key => $va) {
				$da=array(
					date('Y年m月d日',$va['kzrq']),
					$va['source'],
					$va['name'],
					$va['gender'],
					$va['age'],
					$va['expert'],
					$va['address'],
					$va['phone'],
					$va['illness'],
					$va['jcf'],
					$va['ghf'],
					$va['zlcs'],
					$va['zlfy'],
					$va['zyfs'],
					$va['zyfy'],
					$va['dj'],
					$va['wyfs'],
					$va['wyfy'],
					$va['xyfy'],
					$va['all'],
					$va['hfrq']==0? '未填写回访日期': date('Y年m月d日',$va['hfrq']),
					$va['hfqk'],
					$va['fzrq']==0? '未确定复诊日期': date('Y年m月d日',$va['fzrq']),
					// $va['type']==0? '预约到诊': '自然到诊',
					// $va['diagnosis']==0? '未到诊': '到诊',
					// $va['again']==0? '初诊': '复诊',
					// $va['consumption']==0? '未消费': '消费',
					// $va['hospital']==0? '不住院': '住院',
					// $va['remark'],
					// $va['username'],
					// $va['channel'],
					);
				$data[]=$da;
			}
		if(empty($expert)){
			create_xls($data,$namestart . "至" . $nameend . "复诊信息");
		}elseif(empty($start)){
			create_xls($data,$expert . "复诊信息");
		}else{
			create_xls($data,$expert . $namestart . "至" . $nameend . "复诊信息");
		}
	}
    //导入
    public function eximport(){ 
    	if(IS_POST){
	        $upload = new \Think\Upload();
	        $upload->maxSize   =     3145728 ;    
	        $upload->exts      =     array('xls', 'csv', 'xlsx');  
	        $upload->rootPath  =      './Public';    
	        $upload->savePath  =      '/excel/';    
	        $info   =   $upload->upload();
	        // var_dump($info);die;
	        if(!$info){
	            $this->error($upload->getError());
	        }else{
	            $filename='./Public/'.$info['import']['savepath'].$info['import']['savename'];
	            // var_dump($filename);die;
	            import("Org.Yufan.ExcelReader");
	            $ExcelReader=new \ExcelReader();
	            $arr=$ExcelReader->reader_excel($filename);
	            // var_dump($arr);die;
	            foreach ($arr as $key => $value) {
	            	$hhh=htmlentities(trim($arr[$key]['10']));
	            	$sss=htmlentities(trim($arr[$key]['11']));
	            	if(!is_numeric($hhh) && !is_numeric($sss)){
	            		$arr[$key]['10']='0';
	            		$arr[$key]['11']='0';
	            	}
	                $data['name']=htmlentities(trim($arr[$key]['0']));
	                $data['phone']=htmlentities(trim($arr[$key]['1']));
	                $data['sex']=htmlentities(trim($arr[$key]['2']));
	                $data['age']=htmlentities(trim($arr[$key]['3']));
	                $data['address']=htmlentities(trim($arr[$key]['4']));
	                $data['yuyueks']=htmlentities(trim($arr[$key]['5']));
	                $data['yuyuezj']=htmlentities(trim($arr[$key]['6']));
	                $data['yuyuebz']=NULL;
	                $data['alias']='';
	                $data['phones']='';
	                $data['yuyuetime']=strtotime(htmlentities(trim($arr[$key]['7'])).'/'.htmlentities(trim($arr[$key]['8'])).'/'.htmlentities(trim($arr[$key]['9'])).' '.htmlentities(trim($arr[$key]['10'])).':'.htmlentities(trim($arr[$key]['11'])));
	                $data['time']=time();
	                $data['type']='0';
	                $data['diagnosis']='0';
	                $data['again']='1';
	                $data['consumption']='0';
	                $data['hospital']='0';
	                $data['dialogue']='';
	                $data['remark']=htmlentities(trim($arr[$key]['12']));
	                $data['huawu']='88';
	                $data['channel']='医院';
	                $data['source']='医院';
	                $data['recycle']='0';
	                $data['referral_zj']='';
	                $data['referral_bezj']='';
	                $data['referral_state']='0';
	                $data['referral_num']='0';
	                $data['refetime']=NULL;
	                M("Patient")->data($data)->add();
	            }
	            // var_dump($data);die;
	            $this->success('导入成功');
	        }
        }else{
        	$this -> display();
        } 
    }
	//医院查看
	public function eximport_view(){
		$beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    	$where='recycle=0 and again=1 and huawu=88';
		$m=M("Patient");
		$p=getpage($m,$where,10);
		$data=$m->where($where)->order('yuyuetime asc')->field('id,name,phone,sex,age,address,yuyuezj,yuyueks,yuyuebz,alias,phones,yuyuetime,type,time,diagnosis,consumption,hospital,remark,again')->select();
		$expert = M("Zj") -> field('zjname') -> select();
			$govern = M("Ks") -> field('ksname') -> select();
			$entity = M("Bz") -> field('bzname') -> select();
			$channel = M("Channel") -> field('name') -> select();
			$source = M("Source") -> field('name') -> select();
			$assign=array(
            	'data' => $data,
            	'expert' => $expert,
            	'govern' => $govern,
            	'entity' => $entity,
            	'channel' => $channel,
            	'source' => $source
        	);
		$this->page=$p->show();
		$this->assign($assign);
		$this->display();
	}
	//医院修改
	public function eximport_edit(){
		$make = M("Patient"); // 实例化User对象
		if(IS_POST){
			$data=I('post.');
			if($data['name'] == ""){
				$this->error('修改失败');
			}
			$verifys=substr(md5("活着"),8,16);
			$as=$data['act'].$verifys;
			$verifys=substr(md5($as),8,16);
			if($data['verify']=$verifys){
				$where='id='.$data['id'];
				if($data['consumption']!=1){
					$data['consumption']=0;
				}
				if($data['diagnosis']!=1){
					$data['diagnosis']=0;
				}
				if($data['hospital']!=1){
					$data['hospital']=0;
				}
				if($data['yuyuezj']==''){
					$data['yuyuezj']='其他';
				}
				if($data['yuyueks']==''){
					$data['yuyueks']='综合';
				}
				if($data['channel']==''){
					$data['channel']='其他';
				}
				$make->name=$data['name'];
				$make->phone=$data['phone'];
				$make->sex=$data['sex'];
				$make->age=$data['age'];
				$make->address=$data['address'];
				$make->yuyuezj=$data['yuyuezj'];
				$make->yuyueks=$data['yuyueks'];
				$make->yuyuebz=$data['yuyuebz'];
				$make->alias=$data['alias'];
				$make->phones=$data['phones'];
				$make->diagnosis=$data['diagnosis'];
				$make->consumption=$data['consumption'];
				$make->hospital=$data['hospital'];
				$make->remark=$data['remark'];
				$make->again=$data['again'];
		        $make=$make->where($where)->save();
				if (!empty($make)) {
					$this->success('修改成功',U('Admin/FuZhen/eximport_view'));die;
				}else{
				$this->error('修改失败');
				}
			}else{
				$this->error('修改失败，参数错误');
			}
		}elseif(IS_GET){
			$data=I('get.');
			$where='recycle=0 and id='.$data['id'];
			$key=array_keys($data);
			if($key[1]=='diagnosis'){
				$m=$make->where($where)->getField('diagnosis');
				if($m === $data['diagnosis']){
					$data['diagnosis'] = abs($m-1);
					$make=$make->where($where)->field('diagnosis')->filter('strip_tags')->save($data);
					if(!empty($make)){
						$this->success('修改成功');die;
					}
				}
				$this->error('修改失败');
			}
			elseif($key[1]=='consumption'){
				$m=$make->where($where)->getField('consumption');
				if($m === $data['consumption']){
					$data['consumption'] = abs($m-1);
					$make=$make->where($where)->field('consumption')->filter('strip_tags')->save($data);
					if(!empty($make)){
						$this->success('修改成功');die;
					}
				}
				$this->error('修改失败');

			}
			elseif($key[1]=='hospital'){
				$m=$make->where($where)->getField('hospital');
				if($m === $data['hospital']){
					$data['hospital'] = abs($m-1);
					$make=$make->where($where)->field('hospital')->filter('strip_tags')->save($data);
					if(!empty($make)){
						$this->success('修改成功');die;
					}
				}
				$this->error('修改失败');
			}
			else{
				$make=$make->where($data)->field('id,name,phone,sex,age,address,yuyuezj,yuyueks,yuyuebz,alias,phones,yuyuetime,type,time,diagnosis,consumption,hospital,remark,again')->find();
				// dump($make);die;
				if(empty($make)){
					$this->error('修改失败,参数错误');
				}
			}
		}
		$govern=M("Ks")->field('ksname')->select();
		$expert=M("Zj")->field('zjname')->select();
		$entity=M("Bz")->field('bzname')->select();
		$channel=M("Channel")->field('name')->select();
		$source=M("Source")->field('name')->select();
		$this->make=$make;
		$assign=array(
            'govern'=>$govern,
            'expert'=>$expert,
            'govern'=>$govern,
            'entity'=>$entity,
            'channel'=>$channel,
            'source'=>$source
        );
		$this->assign($assign);
		$this->display();
	}
	public function eximport_search(){
	if(IS_POST){
			//用户查询数据
			$post = I('post.');
			$where = "recycle = '0'";
			if($post['yuyueks'] != ""){
				$where .= ' and yuyueks = ' . '"' . $post['yuyueks'] . '"';
			}
			if($post['yuyuezj'] != ""){
				$where .= ' and yuyuezj = ' . '"' . $post['yuyuezj'] . '"';
			}
			if($post['yuyuebz'] != ""){
				$where .= ' and yuyuebz = ' . '"' . $post['yuyuebz'] . '"';
			}
			if($post['start']!=""){
				$start = strtotime($post['start']);
			$where .=' and yuyuetime>'.'"'.$start.'"';
			}
			if($post['end']!=""){
				$end = strtotime($post['end']);
			$where .=' and yuyuetime<'.'"'.$end.'"';
			}
			if($post['phone'] != ""){
				$where .= ' and phone like  ' . '"%' . $post['phone'] . '%"';
			}
			if($post['name'] != ""){
				$where .= ' and name  like ' . '"%' . $post['name'] . '%"';
			}
			$where .= ' and again=1  and huawu=88';
		$_SESSION['where']=$where;
		}else{
			$where=$_SESSION['where'];
		}
			$make=M("patient");
			$p=getpage($make,$where,10);
			$data=$make -> where($where) -> order('yuyuetime desc') -> select();
			$expert = M("Zj") -> field('zjname') -> select();
			$govern = M("Ks") -> field('ksname') -> select();
			$entity = M("Bz") -> field('bzname') -> select();
			$channel = M("Channel") -> field('name') -> select();
			$source = M("Source") -> field('name') -> select();
			$assign=array(
            	'data' => $data,
            	'expert' => $expert,
            	'govern' => $govern,
            	'entity' => $entity,
            	'channel' => $channel,
            	'source' => $source
        	);

		$this -> page = $p -> show();
		$this -> assign($assign);
		$this -> display('eximport_view');
	}
	//医助导入信息删除
	public function eximport_del(){
		$id=I('get.id');
		$where = 'id='.$id.' and again=1'; 
		$make = M("Patient"); // 实例化User对象
        $make->recycle = 1;
        $make=$make->where($where)->save();
        // dump($make);die;
		if (!empty($make)) {
			$this->success('删除成功',U('Admin/FuZhen/eximport_view'));
		}else{
			$this->error('删除失败');
		}
	}

	public function eximport_delete(){
		$post = I('post.');
		// echo "<pre/>";
		// var_dump($post);die;
		if(empty($post)){
			$this->error('请选择');
		}else{
			$res = $post['mistake'];
			$res = "(" . implode(',',$res) . ")";
			// echo $res;die;
			$where = 'id IN' . $res . ' and again=1';
			$make = M("Patient"); // 实例化User对象
        	$make->recycle = 1;
        	$make=$make->where($where)->save();
        	// dump($make);die;
			if (!empty($make)) {
				$this->success('删除成功',U('Admin/FuZhen/eximport_view',array('sign'=>2)));
			}else{
				$this->error('删除失败');
			}
		}
	}


	public function timeTask()
	{
		$abc= array(

            'first'  => array('value'=>urlencode("您好，您复诊患者回访日期临近"),'color'=>'#FF0000'),

            'keyword1' => array('value'=>urlencode("$name"),'color'=>''),

            'keyword2' => array('value'=>"$sex",'color'=>''),

            'keyword3' => array('value'=>"$hos",'color'=>''),

            'keyword4' => array('value'=>urlencode("$dep"),'color'=>''),

            'remark'   => array('value'=>urlencode("您已预约成功，稍后医院会跟您联系，确定具体就诊日期和时段，请您注意接听客服的电话！谢谢您的配合！"),'color'=>''),

            );

            Vendor('WeixinMsg.WeixinMsg');

                $wxtlp = new \wxmsg\WeiXin();

                $template = $wxtlp->template($openid,3,$abc);

                $res=$wxtlp->send_template_message(urldecode(json_encode($template)));

                $data=json_decode($res,true);

        $title = '患者预约成功';

        $content = "患者姓名：" . $name . "，性别：" . $sex ."，电话：" . $phone . "，身份证号：". $identify . "，预约：" . $yytime . "," . $doc . "专家的号位，挂号费：" . $result['total_fee']/100 . "元，用户订单号:" . $seq . ",来自微信公众号预约。";
        $this -> sendMail($one,$two,$three,$four,$five,$six,$title,$content);
	}


	public function sendMail(){
// $one,$two,$three,$four,$five,$six,$title,$content
        require('./ThinkPHP/Library/Vendor/phpmailer/class.phpmailer.php');
        require('./ThinkPHP/Library/Vendor/phpmailer/class.smtp.php');
        require('./ThinkPHP/Library/Vendor/phpmailer/class.pop3.php');

        // $to = 'wangxiange_mail@163.com';
        $one = '441163446@qq.com';
        // $two = '1632400495@qq.com';

        $title = '测试';

        $content = '这里是测试数据';

        try {

            $mail = new \PHPMailer(true);

            $mail->IsSMTP();

            $mail->SMTPSecure = 'ssl';

            $mail->CharSet = 'UTF-8';

            $mail->SMTPAuth = true; //开启认证

            $mail->Port = 465;    //网易为25

            $mail->Host = "smtp.qq.com";

            $mail->Username = "441163446";    //qq此处为邮箱前缀名  163为邮箱名

            $mail->Password = "xoxhbhmckeqhcadd";

            $mail->AddReplyTo("441163446@qq.com", "first");//回复地址

            $mail->From = "441163446@qq.com";

            $mail->FromName = '441163446';

            $mail->AddAddress($one);

            $mail->Subject = $title;

            $mail->Body = $content;

            $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示

            $mail->WordWrap = 80; // 设置每行字符串的长度

//$mail->AddAttachment("f:/test.png"); //可以添加附件

            $mail->IsHTML(true);

            $mail->Send();

            echo '发送成功';

        } catch (phpmailerException $e) {

            $e->errorMessage();

        }

    }
}