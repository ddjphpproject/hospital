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
        // $p=getpage($m,$where,10);
        //$list=$m->field(true)->where($where)->order('id desc')->select();

        $expert=$m->join('yuyue_ks  as  b  on b.id = a.ks_id')->field('a.id,a.zjname,b.ksname,a.ks_id,a.order_number') -> where("a.recycle=0") ->select();
        //$this->list=$expert;
        // $this->page=$p->show();
        $govern=M('Ks')->field('id,ksname')->select();
        $assign=array(
            'govern'=>$govern,
            'expert'=>$expert
        );
        $this->assign($assign);
        $this->display();
    }
    public function add_expert(){
        if(IS_POST){
            $data=I('post.');

//            $file = $_FILES['file'];//得到传输的数据

            //得到文件名称
//            $name = $file['name'];
//            $size = $file['size'];
            // echo $size;die;
//            if($size > 20000){
//                $this -> error('超出大小限制');die;
//            }
       //     $type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写
         //   $allow_type = array('jpg','jpeg','gif','png'); //定义允许上传的类型
            //判断文件类型是否被允许上传
//            if(!in_array($type, $allow_type)){
//              //如果不被允许，则直接停止程序运行
//              return ;
//            }
            //判断是否是通过HTTP POST上传的
//            if(!is_uploaded_file($file['tmp_name'])){
//              //如果不是通过HTTP POST上传的
//              return ;
//            }
          //  $upload_path = "./Upload/image/"; //上传文件的存放路径
           // $file['name'] = time() . ".jpg";
            //$path = $upload_path.$file['name'];
            // var_dump($path);die;
//            if(move_uploaded_file($file['tmp_name'],$path)){
                $data['time']=time();
                $ksname = $data['ksname'];
                $res =  M('ks') -> field('id') -> where("ksname='$ksname'") -> find();
                $ks_id = $res['id'];
                $data['ks_id'] = $ks_id;
                $data['avatar'] = "http://" . $_SERVER['HTTP_HOST'] .$data['file'];
                $zj = M("Zj"); // 实例化User对象
                $where="zjname="."'".$data['zjname']."'";
                $iszj=$zj->where($where)->select();
                // var_dump($iszj);
                // var_dump($data);die;
                        //dump($data['zjname']);die;
                if($data['zjname'] == "" || $data['ks_id'] == "" || !empty($iszj)){
                    $this->error('添加失败,参数错误');
                }
                $resul = M('zj') -> field("order_number") -> where("recycle=0") -> order('order_number desc') -> find();
                $data['order_number'] = $resul['order_number'] + 1;
                $zj=$zj->data($data)->add();
                if ($zj) {
                    $this->success('添加成功',U('Admin/Expert/index'));
                }else{
                    $this->error('添加失败');
                }
//            }
//            else{
//                $this->error('文件上传出错');die;
//            }
        }else{
            $govern=M("Ks")->field('ksname')->select();
            $this -> assign('govern',$govern);
            $this -> display();
        }
    }
    public function edit_expert(){
        if(IS_POST){
            $post=I('post.');
            $id = $post['id'];
            $data['zjname'] = $post['zjname'];
            $data['time'] = time();
            $data['major'] = $post['major'];
            $data['intro'] = $post['intro'];
            $data['info'] = $post['info'];
            $data['ghf'] = $post['ghf'];
            $ksname = $post['ksname'];
            $res =  M('ks') -> field('id') -> where("ksname='$ksname'") -> find();
            $ks_id = $res['id'];
            $data['ks_id'] = $ks_id;
            $zj=M("Zj")->data($data)->where("id='$id'")->save();
            if ($zj) {
                $this->success('修改成功',U('Admin/Expert/index'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $id = I('get.id');
            $data = M('zj as z') -> join('yuyue_ks as k') -> field('z.id,z.zjname,z.major,z.intro,z.info,z.ghf,k.ksname,z.avatar') -> where("z.ks_id=k.id and z.id='$id'") -> select();
            $govern=M("Ks")->field('ksname')->select();
            $assign = array(
                        'govern' => $govern,
                        'data' => $data
                    );
            $this -> assign($assign);
            // var_dump($data);die;
            $this -> display();
        }
    }
    public function delete_expert(){
        $id=I('get.id');
        $where = 'id='.$id; 
        $User = M("Zj"); // 实例化User对象
        $User -> recycle = 1;
        $User->where($where)->save(); // 删除id为的用户数据
        if ($User) {
            $this->success('删除成功',U('Admin/Expert/index'));
        }else{
            $this->error('删除失败');
        }
    }

    public function expertOrder()
    {
        $post = I('post.');
        $result=D('Zj')->orderData($post);
        if($result)
        {
            $this->success('排序成功',U('Admin/Expert/index'));
        }
        else
        {
            $this->error('排序失败');
        }
    }


}
