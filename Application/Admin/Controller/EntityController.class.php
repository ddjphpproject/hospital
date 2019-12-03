<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 专家列表
 */
class EntityController extends AdminBaseController{

	/**
	 * 专家列表
	 */
	public function index(){
		$entity=M('Bz  as  a')->join('yuyue_ks  as  b  on b.id = a.ksid')->field('a.id,a.bzname,b.ksname,a.ksid')->select();
		// dump($entity);die;
		$govern=M('Ks')->field('id,ksname')->select();
		$assign=array(
            'govern'=>$govern,
            'entity'=>$entity
        );
		$this->assign($assign);
		$this->display();
	}
	public function add_entity(){
		$data=I('post.');
		$data['time']=time();
		$entity = M("Bz"); // 实例化User对象
		$where="bzname="."'".$data['bzname']."'";
		$isbz=$entity->where($where)->select();
				//dump($data['Bzname']);die;
		if($data['bzname'] == "" || $data['ksid'] == "" || !empty($isbz)){
			$this->error('添加失败,参数错误');
		}
        $entity=$entity->data($data)->add();
		if ($entity) {
			$this->success('添加成功',U('Admin/entity/index'));
		}else{
			$this->error('添加失败');
		}
	}
	public function edit_entity(){
		$data=I('post.');
		$bz=M("Bz");
		$where="bzname="."'".$data['bzname']."'";
		// dump($where);die;
		$isbz=$bz->where($where)->select();
		// dump($isbz);die;
		// dump($isbz);die;
		if($data['bzname'] == "" || $data['ksid'] == ""  || !empty($isbz)){
			$this->error('修改失败,参数错误');
		}
		$data['time']=time();
		$id='id='.$data['id'];
		// dump($id);die;
		// dump($data);die;
		$bz=$bz->data($data)->where($id)->save();
        // $bz=$bz->data($data)->save();
        // dump($bz);die;
		if (!empty($bz)) {
			$this->success('修改成功',U('Admin/entity/index'));
		}else{
			$this->error('修改失败');
		}
	}
	public function delete_entity(){
		$id=I('get.id');
		$where = 'id='.$id; 
		$User = M("Bz"); // 实例化User对象
         $User->where($where)->delete(); // 删除id为的用户数据
		if ($User) {
			$this->success('删除成功',U('Admin/entity/index'));
		}else{
			$this->error('删除失败');
		}
	}




}
