<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 科室
 */
class ChannelController extends AdminBaseController{
    /**
     * 渠道列表
     */
    public function index(){
		$channel=M("Channel")->select();
		$assign=array(
            'data'=>$channel
            );
		$this->assign($assign);
        $this->display();
    }
	public function add_channel(){
		$data=I('post.');
		$data['time']=time();
		$channel = M("Channel"); // 实例化User对象
		$where="name="."'".$data['name']."'";
		$ischannel=$channel->where($where)->select();

		if($data['name'] == "" || !empty($ischannel)){
			$this->error('添加失败');
		}
        $channel->data($data)->add();
		if ($channel) {
			$this->success('添加成功',U('Admin/Channel/index'));
		}else{
			$this->error('添加失败');
		}
	}
	public function edit_channel(){
		$data=I('post.');
		$channel=M("Channel");
		$where="name="."'".$data['name']."'";
		$ischannel=$channel->where($where)->select();
		if($data['name'] == ""  || !empty($ischannel)){
			$this->error('修改失败,参数错误');
		}
		$data['time']=time();
		$id='id='.$data['id'];
        $channel=$channel->data($data)->where($id)->save();
		if ($channel) {
			$this->success('修改成功',U('Admin/Channel/index'));
		}else{
			$this->error('修改失败');
		}
	
	}
	public function delete_channel(){
		$id=I('get.id');
		$where = 'id='.$id; 
		$User = M("Channel"); // 实例化User对象
         $User->where($where)->delete(); // 删除id为的用户数据
		if ($User) {
			$this->success('删除成功',U('Admin/Channel/index'));
		}else{
			$this->error('删除失败');
		}
	}
	public function order_channel(){}

}