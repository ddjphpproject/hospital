<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

use Think\Page;

/**

 * 科室

 */

class GovernController extends AdminBaseController{

    /**

     * 科室列表

     */

    public function index(){
    	$post = I('post.');
        $where='';
        $m=M("Bz as yb"); 
        $ks = $m
            ->join('yuyue_ks   yk  on yb.ksid = yk.id')
            ->field('yb.ksid,yk.ksname,GROUP_CONCAT( yb.bzname ) as bznames ')
            ->where('yk.recycle = 0')
            ->GROUP('ksid')
            ->limit(10) 
            ->select();  
        $kscount = $m
            ->join('yuyue_ks   yk  on yb.ksid = yk.id')
            ->field('yb.ksid,yk.ksname,GROUP_CONCAT( yb.bzname ) as bznames ')
            ->where('yk.recycle = 0')
            ->GROUP('ksid') 
            ->select();  

        $count = count($kscount);

	    if(IS_POST && $post['pageSize']){
            $pageSize = $post['pageSize'];
        }else{
            $pageSize = 2;
        }
        $page = new Page($count, $pageSize);
		   // var_dump($count);
        //判断是不是ajax提交
        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
        	// var_dump(@@@dsad );
            if (!empty($post['pageSize']) && !empty($post['page_shu'])) {//跳转页码
                $return = array();
                $pageSize = 2;
                $page_shu = $post['page_shu'];
                $limit = ($page_shu - 1) * $pageSize . ',' . $pageSize;
                $return['total_page'] = ceil($count / $pageSize);
                $return['page_shu'] = intval($page_shu);
              // var_dump(33);die;
                $ks = $m
			            ->join('yuyue_ks   yk  on yb.ksid = yk.id')
			            ->field('yb.ksid,yk.ksname,GROUP_CONCAT( yb.bzname ) as bznames ')
			            ->where('yk.recycle = 0')
			            ->GROUP('ksid')  
			            ->limit($limit)
			            ->select();
                $return['data'] = $ks;
                exit(json_encode($return));
			            // var_dump($ks);die;  
            } else if (!empty($post['current_page']) && !empty($post['pageSize'])) {//累加一个页码

                $pageSize = 2;
                $current_page = $post['current_page'];
                $limit = ($current_page - 1) * $pageSize . ',' . $pageSize;
                $return['total_page'] = ceil($count / $pageSize);
                $return['current_page'] = intval($current_page);
                $ks = $m
			            ->join('yuyue_ks   yk  on yb.ksid = yk.id')
			            ->field('yb.ksid,yk.ksname,GROUP_CONCAT( yb.bzname ) as bznames ')
			            ->where('yk.recycle = 0')
			            ->GROUP('ksid')  
			            ->limit($limit)
			            ->select();
                $return['data'] = $ks;
                exit(json_encode($return));
            }
        } 
		  
		$assign=array(

            'data'=>$ks

            );

		$this->assign($assign);
        $page->show();

        $pageData = [
            'current_page' => $page->nowPage,
            'total_page' => $page->totalPages,
            'pageSize' => $page->listRows
        ];
        // var_dump($pageData);die;
        $this->assign(array_merge($assign,$pageData));
        $this->display();

    }

	public function add_ks(){

		$data=I('get.');
 		// var_dump($data);die;

 		$ks_arr['ksname'] = $data['ksname'];
		$ks_arr['time']=time();

		$ks = M("Ks"); // 实例化User对象

		$bz=M("Bz");

		// $where="ksname="."'".$data['ksname']."'";

		// $isks=$ks->where($where)->select();



		// if($data['ksname'] == "" || !empty($isks)){

		// 	$this->error('添加失败');

		// } 
// var_dump($ks_arr);die;
        $ks->data($ks_arr)->add();
        $lastid = $ks->getLastInsID();

        //获取添加内容
        $bznames = $data['bznames'];
        $bzname_arr=explode(",",$bznames);
         
        //循环添加病种
        for ($i=0; $i <count($bzname_arr) ; $i++) { 
        	 $arr['bzname']= $bzname_arr[$i];
        	 $arr['time'] = time();
        	 $arr['ksid'] = $lastid;

        	 $bz->data($arr)->add();
        }
// var_dump($sql);die;
		if ($ks) {

			$this->success('添加成功',U('Admin/Govern/index'));

		}else{

			$this->error('添加失败');

		}

	}

	public function edit_ks(){

		$data=I('get.');

		$ks=M("Ks");

		$bz=M("Bz");
        
        $ksname=[];

		$id='id='.$data['ksid'];
        
        $ksname['ksname'] = $data['ksname'];


		$isks=$ks->where($id)->select();
		

		if($data['ksname'] == ""  || empty($isks)){

			$this->error('修改失败,参数错误');

		}
        
        //获取更新内容
        $bznames = $data['bznames'];
        $bzname_arr=explode(",",$bznames);
        
        //删除科室下所有病种
        if(!empty($bznames))
        {
        	$where = 'ksid='.$data['ksid'];   
            $bz->where($where)->delete(); 
        } 
        //循环添加病种
        for ($i=0; $i <count($bzname_arr) ; $i++) { 
        	 $arr['bzname']= $bzname_arr[$i];
        	 $arr['ksid'] = $data['ksid'];

        	 $bz->data($arr)->add();
        }
        //修改科室名称
		$ksname['time']=time(); 
		// $id='id='.$data['ksid'];

        $ks=$ks->data($ksname)->where($id)->save();

		if (!empty($ks)) {

			$this->success('修改成功',U('Admin/Govern/index'));

		}else{

			$this->error('修改失败');

		}

	

	}

	public function delete_ks(){

		$id=I('get.id');

		$where = 'id='.$id; 

		$User = M("Ks"); // 实例化User对象

        $data['recycle']=1;

        $User->data($data)->where($where)->save(); // 删除id为的用户数据

		if ($User) {

			$this->success('删除成功',U('Admin/Govern/index'));

		}else{

			$this->error('删除失败');

		}

	}

	public function order_ks(){}



}