<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 来源
 */
class SourceController extends AdminBaseController{
    /**
     * 来源列表
     */
    public function index(){
		$source=M("Source")->select();
		$assign=array(
            'data'=>$source
            );
		$this->assign($assign);
        $this->display();
    }
	public function add_Source(){
		$data=I('post.');
		$data['time']=time();
		$source = M("Source"); // 实例化User对象
		$where="name="."'".$data['name']."'";
		$isSource=$source->where($where)->select();

		if($data['name'] == "" || !empty($isSource)){
			$this->error('添加失败');
		}
        $source->data($data)->add();
		if ($source) {
			$this->success('添加成功',U('Admin/Source/index'));
		}else{
			$this->error('添加失败');
		}
	}
	public function edit_Source(){
		$data=I('post.');
		$source=M("Source");
		$where="name="."'".$data['name']."'";
		$issource=$source->where($where)->select();
		if($data['name'] == ""  || !empty($issource)){
			$this->error('修改失败,参数错误');
		}
		$data['time']=time();
		$id='id='.$data['id'];
        $source=$source->data($data)->where($id)->save();
		if ($source) {
			$this->success('修改成功',U('Admin/Source/index'));
		}else{
			$this->error('修改失败');
		}
	
	}
	public function delete_Source(){
		$id=I('get.id');
		$where = 'id='.$id; 
		$User = M("Source"); // 实例化User对象
         $User->where($where)->delete(); // 删除id为的用户数据
		if ($User) {
			$this->success('删除成功',U('Admin/Source/index'));
		}else{
			$this->error('删除失败');
		}
	}
	public function order_Source(){}

}