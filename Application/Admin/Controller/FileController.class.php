<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 导入导出控制器
 */
class FileController extends AdminBaseController{
	/**
	 * 首页
	 */
	public function index(){
		$this->error('完善中。。。');
        $beginToday = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    	// dump($where);die;
		$m=M("Patient as a");
		$p=getpage($m,$where,10);
		$make=$m->join('yuyue_users as  b  on b.id = a.huawu')->field('a.*,b.username')->where($where)->order('a.id desc')->select();
		//$make=M("Patient")->where($where)->select();
		// dump($make);die;
		$expert=M("Zj")->field('zjname')->select();
		$govern=M("Ks")->field('ksname')->select();
		$entity=M("Bz")->field('bzname')->select();
		$channel=M("Channel")->field('name')->select();
		$source=M("Source")->field('name')->select();
		// dump($make);die;
		$assign=array(
            'make'=>$make,
            'expert'=>$expert,
            'govern'=>$govern,
            'entity'=>$entity,
            'channel'=>$channel,
            'source'=>$source
        );
		$this->page=$p->show();
		$this->assign($assign);
		$this->display();
	}



}
