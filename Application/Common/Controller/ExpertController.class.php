<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 专家列表
 */
class ExpertController extends AdminBaseController{

	/**
	 * 专家列表
	 */
	public function index(){
		$m=M('Zj as a');
		$p=getpage($m,$where,10);
		//$list=$m->field(true)->where($where)->order('id desc')->select();

		$expert=$m->join('yuyue_ks  as  b  on b.id = a.ks_id')->field('a.id,a.zjname,b.ksname,a.ks_id')->select();
		//$this->list=$expert;
		$this->page=$p->show();
		$govern=M('Ks')->field('id,ksname')->select();
		$assign=array(
            'govern'=>$govern,
            'expert'=>$expert
        );
		$this->assign($assign);
		$this->display();
	}
	public function add_expert(){
		$data=I('post.');
		$data['time']=time();
		$expert = M("Zj"); // 实例化User对象
		$where="zjname="."'".$data['zjname']."'";
		$iszj=$expert->where($where)->select();
				//dump($data['zjname']);die;
		if($data['zjname'] == "" || $data['ks_id'] == "" || !empty($iszj)){
			$this->error('添加失败,参数错误');
		}
        $expert=$expert->data($data)->add();
		if ($expert) {
			$this->success('添加成功',U('Admin/Expert/index'));
		}else{
			$this->error('添加失败');
		}
	}
	public function edit_expert(){
		$data=I('post.');
		$zj=M("Zj");
		$where="zjname="."'".$data['zjname']."'";
		$isks=$zj->where($where)->select();
		if($data['zjname'] == "" || $data['ks_id'] == ""  || !empty($iszj)){
			$this->error('修改失败,参数错误');
		}
		$data['time']=time();
		$id='id='.$data['id'];
        $zj=$zj->data($data)->where($id)->save();
		if ($zj) {
			$this->success('修改成功',U('Admin/Expert/index'));
		}else{
			$this->error('修改失败');
		}
	}
	public function delete_expert(){
		$id=I('get.id');
		$where = 'id='.$id; 
		$User = M("Zj"); // 实例化User对象
        $User->where($where)->delete(); // 删除id为的用户数据
		if ($User) {
			$this->success('删除成功',U('Admin/Expert/index'));
		}else{
			$this->error('删除失败');
		}
	}




}
