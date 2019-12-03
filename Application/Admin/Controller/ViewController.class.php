<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**

 * 数据查询控制器

 */

class ViewController extends AdminBaseController{

	/**

	 * 首页

	 */

	public function index(){

		// $post = $_POST['querytype'];

		// var_dump($post);die;

		// echo 1;die;

		$group_id=$_SESSION['user']['group_id'];

		if($group_id == 1 || $group_id == 2){

			$huawu4 = '';

			$huawu3 = '';

			$huawu2 = '';

			$huawu1 = '';

		}else{

			$huawu4=$_SESSION['user']['id'];

			$huawu1=$_SESSION['user']['id'];

			$huawu2=$_SESSION['user']['id'];

			$huawu3=$_SESSION['user']['id'];

		}

		if(IS_POST){

			// $post = $_POST;

			// var_dump($post);die;

			// $post = I('post.');

			// // $type = (int)$post;

			// dump($post);die;

			$type=I('post.querytype');   

			$ytype = I('post.type');

			$again = I('post.again');

			if($type=='1'){//科室

				$govern5=M("Ks")->field('ksname')->select();

				$govern1=M("Ks")->field('ksname')->select();

				$govern2=M("Ks")->field('ksname')->select();

				$govern3=M("Ks")->field('ksname')->select();

				$govern4=M("Ks")->field('ksname')->select();

				$ss4=array();	

				$ss1=array();

				$ss2=array();

				$ss3=array();

				$start4=I('post.star');

				$start1=I('post.star');

				$start2=I('post.star');

				$start3=I('post.star');

				$end4=I('post.end');	

				$end1=I('post.end');	

				$end2=I('post.end');	

				$end3=I('post.end');	



				//预约数

				foreach ($govern5 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu4==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu4;

			    	}

			    	if($start4!=""){

						$start=strtotime($start4);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end4!=""){

						$end=strtotime($end4);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyueks="'.$value['ksname'] . '"';

			    	// dump($where);die;

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss4[]=$value;

				    }

				    $n = array_column($ss4,'cunt');

			    }

			    

			    //到诊数

			    foreach ($govern1 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu1==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu1;

			    	}

			    	if($start1!=""){

						$start=strtotime($start1);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end1!=""){

						$end=strtotime($end1);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyueks="'.$value['ksname'].'" and diagnosis=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss1[]=$value;

				    }

				    $n1 = array_column($ss1,'cunt');

			    }



			    //消费数

			    foreach ($govern2 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu2==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu2;

			    	}

			    	if($start2!=""){

						$start=strtotime($start2);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end2!=""){

						$end=strtotime($end2);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyueks="'.$value['ksname'].'" and consumption=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss2[]=$value;

				    }

				    $n2 = array_column($ss2,'cunt');

			    }

			    //住院数

			    foreach ($govern3 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu3==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu3;

			    	}

			    	if($start3!=""){

						$start=strtotime($start3);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end3!=""){

						$end=strtotime($end3);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyueks="'.$value['ksname'].'" and hospital=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss3[]=$value;

				    }

				    $n3 = array_column($ss3,'cunt');

			    }

			    $n4=array_column($govern4,'ksname');

			    $xx=array();

			    for ($i=0; $i < count($n4); $i++) { 

			    	$xx[]=array('ks'=>$n4[$i],'subscribe'=>$n[$i],'diagnosis'=>$n1[$i],'consumption'=>$n2[$i],'hospital'=>$n3[$i]);

			    }

			    // dump($xx);die;

			    $json=json_encode($xx);

			    // dump($xx);die;

			    echo $json;die;

			}elseif($type=='2'){//专家

				$govern5=M("Zj")->field('zjname')->select();

				$govern1=M("Zj")->field('zjname')->select();

				$govern2=M("Zj")->field('zjname')->select();

				$govern3=M("Zj")->field('zjname')->select();

				$govern4=M("Zj")->field('zjname')->select();

				$ss4=array();	

				$ss1=array();

				$ss2=array();

				$ss3=array();

				$start4=I('post.star');

				$start1=I('post.star');

				$start2=I('post.star');

				$start3=I('post.star');

				$end4=I('post.end');	

				$end1=I('post.end');	

				$end2=I('post.end');	

				$end3=I('post.end');

				//预约数

				foreach ($govern5 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	// echo $huawu4;die;

			    	if($huawu==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu;

			    	}

			    	if($start4!=""){

						$start=strtotime($start4);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end4!=""){

						$end=strtotime($end4);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyuezj="'.$value['zjname'].'"';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss4[]=$value;

				    }

				    $n = array_column($ss4,'cunt');

			    }

			    //到诊数

			    foreach ($govern1 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu1==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu1;

			    	}

			    	if($start1!=""){

						$start=strtotime($start1);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end1!=""){

						$end=strtotime($end1);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyuezj="'.$value['zjname'].'" and diagnosis=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss1[]=$value;

				    }

				    $n1 = array_column($ss1,'cunt');

			    }

			    //消费数

			    foreach ($govern2 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu2==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu2;

			    	}

			    	if($start2!=""){

						$start=strtotime($start2);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end2!=""){

						$end=strtotime($end2);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyuezj="'.$value['zjname'].'" and consumption=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss2[]=$value;

				    }

				    $n2 = array_column($ss2,'cunt');

			    }

			    //住院数

			    foreach ($govern3 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu3==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu3;

			    	}

			    	if($start3!=""){

						$start=strtotime($start3);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end3!=""){

						$end=strtotime($end3);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and yuyuezj="'.$value['zjname'].'" and hospital=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss3[]=$value;

				    }

				    $n3 = array_column($ss3,'cunt');

			    }

			    $n4=array_column($govern4,'zjname');

			    $xx=array();

			    for ($i=0; $i < count($n4); $i++) { 

			    	$xx[]=array('ks'=>$n4[$i],'subscribe'=>$n[$i],'diagnosis'=>$n1[$i],'consumption'=>$n2[$i],'hospital'=>$n3[$i]);

			    }

			    $json=json_encode($xx);

			    echo $json;die;

			}else{//渠道

				$start4=I('post.star');

				$start1=I('post.star');

				$start2=I('post.star');

				$start3=I('post.star');

				$end4=I('post.end');	

				$end1=I('post.end');	

				$end2=I('post.end');	

				$end3=I('post.end');

				$govern5=M("Channel")->field('name')->select();

				$govern1=M("Channel")->field('name')->select();

				$govern2=M("Channel")->field('name')->select();

				$govern3=M("Channel")->field('name')->select();

				$govern4=M("Channel")->field('name')->select();

				$ss4=array();	

				$ss1=array();

				$ss2=array();

				$ss3=array();

				//预约数

				foreach ($govern5 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu4==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu4;

			    	}

			    	if($start4!=""){

						$start=strtotime($start4);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end4!=""){

						$end=strtotime($end4);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and channel="'.$value['name'].'"';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss4[]=$value;

				    }

				    $n = array_column($ss4,'cunt');

			    }

			    //到诊数

			    foreach ($govern1 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu1==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu1;

			    	}

			    	if($start1!=""){

						$start=strtotime($start1);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end1!=""){

						$end=strtotime($end1);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and channel="'.$value['name'].'" and diagnosis=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss1[]=$value;

				    }

				    $n1 = array_column($ss1,'cunt');

			    }

			    //消费数

			    foreach ($govern2 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu2==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu2;

			    	}

			    	if($start2!=""){

						$start=strtotime($start2);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end2!=""){

						$end=strtotime($end2);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and channel="'.$value['name'].'" and consumption=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss2[]=$value;

				    }

				    $n2 = array_column($ss2,'cunt');

			    }

			    //住院数

			    foreach ($govern3 as $key=>$value)

			    {

			    	$where='recycle=0 ';

			    	if($huawu3==""){

			    		$where.="";

			    	}else{

			    		$where.=' and huawu='.$huawu3;

			    	}

			    	if($start3!=""){

						$start=strtotime($start3);

						$where .=' and yuyuetime>'.'"'.$start.'"' ;

					}

					if($end3!=""){

						$end=strtotime($end3);

						$where .=' and yuyuetime<'.'"'.$end.'"' ;

					}

					if($ytype!==''){

						$where .=' and type='.$ytype;

					}

					if($again!==''){

						$where .=' and again='.$again;

					}

			    	$where.=' and channel="'.$value['name'].'" and hospital=1';

			    	$govern=M("Patient")->field('count(id) as cunt')->where($where)->select();

			    	foreach ($govern as $key=>$value)

				    {

				    	$ss3[]=$value;

				    }

				    $n3 = array_column($ss3,'cunt');

			    }

			    $n4=array_column($govern4,'name');

			    $xx=array();

			    for ($i=0; $i < count($n4); $i++) { 

			    	$xx[]=array('ks'=>$n4[$i],'subscribe'=>$n[$i],'diagnosis'=>$n1[$i],'consumption'=>$n2[$i],'hospital'=>$n3[$i]);

			    }

			    $json=json_encode($xx);

			    echo $json;die;

			}

		}		

		// dump($govern);die;

		$this->display();

	}

	public function show(){

		// dump(I('post.'));

    	// dump($_SESSION['user']);die;

    	$huawu=$_SESSION['user']['id'];

    	$group_id=$_SESSION['user']['group_id'];

		if($group_id == 1 || $group_id == 2){

			$huawu="";

		}else{

			$huawu=$_SESSION['user']['id'];

		}

    	if(IS_POST){

		$type=I('post.querytype');

		// $type='1';

		$start=I('post.star');

		$end=I('post.end');	

		$where='recycle=0 ';

    	if($huawu==""){

    		$where.="";

    	}else{

    		$where.=' and huawu='.$huawu;

    	}

    	if($type!=""){

			$where .=' and (name="'.$type.'" or phone="'.$type.'")';

		}

		if($start!=""){

			$start=strtotime($start);

			$where .=' and yuyuetime>'.'"'.$start.'"' ;

		}

		if($end!=""){

			$end=strtotime($end);

			$where .=' and yuyuetime<'.'"'.$end.'"' ;

		}

		$m=M("Patient");

		$make=$m->field('name,phone,diagnosis,consumption,hospital')->where($where)->order('id desc')->select();

		foreach ($make as $key => $value) {

			$make[$key]['phone'] = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $value['phone']);

		}

		$json=json_encode($make);

		echo $json;die;

		}

        $this->display();

    	}

    	public function add(){

    		if(IS_POST){

    			$data=I('post.');

				$make = M("Patient");

				$data['time']=time();

				$data['huawu']=$_SESSION['user']['id'];

				$data['yuyuetime']=time();

				// $where="name="."'".$data['name']."' and phone=".$data['phone'];

				// $isname=$make->where($where)->select();

				// dump($isname);die;

				if($data['name'] == ""){

					$this->error('添加失败');

				}

		        $makes=$make->data($data)->add();

		        $_SESSION['addid']=$makes;

		        // dump($makes);die;

				if ($make) {

					$this->success('添加成功',U('Admin/View/adds'));

				}else{

					$this->error('添加失败');

				}

    		}

    		$this->display();

    	}

    	public function adds(){



    		$uid=$_SESSION['addid'];

    		// echo $uid;die;/

    		if($uid==""){

    			$this->error('添加错误，没有上级数据',U('Admin/View/add'));

    		}

    		if(IS_POST){

    			$data=I('post.');

				// dump($data);die;

				$data['yuyuetime']=strtotime($data['yuyuetime']);

				$data['id']=$uid;

				if($data['yuyueks'] == ''){

					$data['yuyueks'] = '综合';

				}

				if($data['channel'] == ''){

					$data['channel'] = '其他';

				}

				$make = M("Patient");

				// $huawu=$_SESSION['user']['id'];

				// $where="name="."'".$data['name']."' and phone=".$data['phone']." and $huawu=".$huawu;

				// $isname=$make->where($where)->select();

				// dump($isname);die;

				// if($data['name'] == ""){

				// 	$this->error('修改失败');

				// }

		        $make=$make->save($data);

				if ($make) {

					$this->success('修改成功',U('Admin/view/add'));

				}else{

					$this->error('修改失败');

				}

    		}

    		$govern=M("Ks")->field('ksname')->select();

			$expert=M("Zj")->field('zjname')->select();

			$entity=M("Bz")->field('bzname')->select();

			$channel=M("Channel")->field('name')->select();

			$source=M("Source")->field('name')->select();

			// dump($make);die;

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

/**

 * [hospital_data 医院数据]

 * @return [type] [description]

 */

    	function hospital_data(){

    		if(IS_POST){

    			$post = I('post.');

    			// dump($post);

    			$where = "recycle = '0'";

    			if($post['expert'] !=''){

    				$where .=' and expert = ' . '"' . $post['expert'] . '"';

    			}

    			if($post['star'] !='' && $post['end']!=''){

    				$start = strtotime($post['star']);

    				$end = strtotime($post['end']);

    				$where .= ' and htime > ' . $start . ' and htime < ' . $end;

    			}

    			if($post['again'] == '1'){

    				$where .= ' and again = 1';

    			}

    			if($post['again'] == '2'){

    				$where .= ' and again = 2';

    			}

    			if($post['name'] !=''){

    				$where = 'name = ' . '"' . $post['name'] . '"';

    			}

    			// dump($where);die;

    			$data = M('hdata') -> field('id,pnum,expert,name,department,again') -> where($where)-> limit(100) -> order('id desc') -> select();

    			// dump($data);die;

    			foreach ($data as $key => $value) {

			$data[$key]['pnum'] = preg_replace('/(\d{3})\d{4}(\d{3})/', '$1****$2', $value['pnum']);

			if($value['again'] =='1'){

				$data[$key]['again'] = '初诊';

			}

			if($value['again'] =='2'){

				$data[$key]['again'] = '复诊';

			}

		}

    			$json = json_encode($data);

    			echo $json;die;

    		}

    		$this -> display();

    	}



    	public function testv(){
    		$this -> display();
    	}

}

