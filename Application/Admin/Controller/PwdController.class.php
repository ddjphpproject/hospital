<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 文章
 */
class PwdController extends AdminBaseController{
    /**
     * 个人信息
     */
    public function index(){
		 if(IS_POST){
            $data=I('post.');
            // 组合where数组条件
            $uid=$data['id'];
            // echo $uid;die;
            $user_data=M('Users')->find($uid);
            // dump($user_data);die;
            $map=array(
                'id'=>$uid
                );
            // 如果修改密码则md5
            $data['password']=MD5($data['password']);
            // dump($data['password']);die;
            if (!empty($data['password']) && $user_data['password']==$data['password'] && !empty($data['passwordes'])) {
            	// echo 1;die;
                $data['password']=md5($data['passwordes']);
                $result=D('Users')->editData($map,$data);
	            if($result){
	                // 操作成功
	                $this->success('编辑成功',U('Admin/Index/welcome'));
	            }else{
	                $error_word=D('Users')->getError();
	                if (empty($error_word)) {
	                    $this->success('编辑成功',U('Admin/Index/welcome'));
	                }else{
	                    // 操作失败
	                    $this->error('修改失败');                  
	                }
	            }
            }else{
	                    // 操作失败
	                    $this->error('修改失败');               
	                }
        }else{
            $id=$_SESSION['user']['id'];
            // echo $id;die;
            // 获取用户数据
            $user_data=M('Users')->find($id);
            $assign=array(
                'user_data'=>$user_data,
                );
            $this->assign($assign);
            $this->display();
        }

}
}