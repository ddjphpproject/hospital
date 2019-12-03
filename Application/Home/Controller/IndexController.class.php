<?php

namespace Home\Controller;

use Common\Controller\HomeBaseController;

/**

 * 商城首页Controller

 */

class IndexController extends HomeBaseController{

	/**

	 * 首页

	 */

	public function index(){

        if(IS_POST){

            $res = $this -> isMobile();





            // 做一个简单的登录 组合where数组条件 

            $map=I('post.');

            $map['password']=md5($map['password']);

            $data=M('Users')->where($map)->find();

            if (empty($data)) {

                $this->error('账号或密码错误');

            }else{

                $_SESSION['user']=array(

                    'id'=>$data['id'],

                    'username'=>$data['username'],

                    'avatar'=>$data['avatar']

                    );

                if($res == false){
                    $this->success('登录成功、前往管理后台',U('Admin/Index/index'));
                }else{
                    $this->success('登录成功、前往管理后台',U('Home/ShouYe/shouye'));
                }


            }

        }else{

            $data=check_login() ? $_SESSION['user']['username'].'已登录' : '未登录';

            $assign=array(

                'data'=>$data

                );

            $this->assign($assign);

            $this->display('Login:login');

        }

	}



    //判断访问方式
    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }



    /**

     * 退出

     */

    public function logout(){

        session('user',null);

        $this->success('退出成功、前往登录页面',U('Home/Index/index'));

    }



    /**

     * 发送邮件

     */

    public function send_email(){

        $email=I('post.email');

        $result=send_email($email,'邮件标题','邮件内容');

        if ($result['error']==1) {

            p($result);die;

        }

        $this->success('发送完成',U('Home/Index/index'));

    }



    /**

     * 生成二维码

     */

    public function qrcode(){

        $url=I('post.url');

        qrcode($url);

    }



    /**

     * 生成pdf

     */

    public function pdf(){

        $content=$_POST['content'];

        pdf($content);

    }



    /**

     * 支付宝

     */

    public function alipay(){

        $price=I('post.price');

        $data=array(

            'out_trade_no'=>time(),

            'price'=>$price,

            'subject'=>'测试'

            );

        alipay($data);

    }



    /**

     * 微信 公众号jssdk支付

     */

    public function weixinpay_js(){

        // 此处根据实际业务情况生成订单 然后拿着订单去支付



        // 用时间戳虚拟一个订单号  （请根据实际业务更改）

        $out_trade_no=time();

        // 组合url

        $url=U('Api/Weixinpay/pay',array('out_trade_no'=>$out_trade_no));

        // 前往支付

        redirect($url);

    }



    /**

     * 微信 扫描支付

     */

    public function weixinpay_qrcode(){

        // 此处根据实际业务情况生成订单 然后拿着订单去支付



        // 虚拟的订单 请根据实际业务更改

        $time=time();

        $order=array(

            'body'=>'test',

            'total_fee'=>1,

            'out_trade_no'=>strval($time),

            'product_id'=>1

            );

        weixinpay($order);

    }



    /**

     * 融云用户1

     */

    public function user1(){

        // 模拟id为89的用户的登录过程

        $user_data=M('Users')->field('id,username,avatar')->find(88);

        $_SESSION['user']=array(

            'id'=>$user_data['id'],

            'username'=>$user_data['username'],

            'avatar'=>$user_data['avatar']

            );

        // 获取融云key

        $rong_key_secret=get_rong_key_secret();

        $assign=array(

            'uid'=>$user_data['id'], // 用户id

            'avatar'=>$user_data['avatar'],// 头像

            'username'=>$user_data['username'],// 用户名

            'rong_key'=>$rong_key_secret['key'],// 融云key

            'rong_token'=>get_rongcloud_token($user_data['id'])//获取融云token

            );

        $this->assign($assign);

        $this->display();

    }



    /**

     * 融云用户2

     */

    public function user2(){

        // 模拟id为89的用户的登录过程

        $user_data=M('Users')->field('id,username,avatar')->find(89);

        $_SESSION['user']=array(

            'id'=>$user_data['id'],

            'username'=>$user_data['username'],

            'avatar'=>$user_data['avatar']

            );

        // 获取融云key

        $rong_key_secret=get_rong_key_secret();

        $assign=array(

            'uid'=>$user_data['id'], // 用户id

            'avatar'=>$user_data['avatar'],// 头像

            'username'=>$user_data['username'],// 用户名

            'rong_key'=>$rong_key_secret['key'],// 融云key

            'rong_token'=>get_rongcloud_token($user_data['id'])//获取融云token

            );

        $this->assign($assign);

        $this->display();

    }



    /**

     * 生成xls格式的表格

     */

    public function xls(){

        $data=I('post.data');

        create_xls($data);

    }



    /**

     * 生成csv格式的表格

     */

    public function csv(){

        $data=I('post.data');

        array_walk($data, function(&$v){

            $v=implode(',', $v);

        });

        create_csv($data);

    }



    /**

     * 导入xls格式的数据 

     * 也可以用来导入csv格式的数据

     * 但是csv建议使用 下面的import_csv 效率更高

     */

    public function import_xls(){

        $data=import_excel('./Upload/excel/simple.xls');

        p($data);

    }



    /**

     * 导入csv格式的数据

     */

    public function import_csv(){

        $data=file_get_contents('./Upload/excel/simple.csv');

        $data=explode("\r\n", $data);

        p($data);

    }



    /**

     * geetest生成验证码

     */

    public function geetest_show_verify(){

        $geetest_id=C('GEETEST_ID');

        $geetest_key=C('GEETEST_KEY');

        $geetest=new \Org\Xb\Geetest($geetest_id,$geetest_key);

        $user_id = "test";

        $status = $geetest->pre_process($user_id);

        $_SESSION['geetest']=array(

            'gtserver'=>$status,

            'user_id'=>$user_id

            );

        echo $geetest->get_response_str();

    }



    /**

     * geetest submit 提交验证

     */

    public function geetest_submit_check(){

        $data=I('post.');

        if (geetest_chcek_verify($data)) {

            echo '验证成功';

        }else{

            echo '验证失败';

        }

    }



    /**

     * geetest ajax 验证

     */

    public function geetest_ajax_check(){

        $data=I('post.');

        echo intval(geetest_chcek_verify($data));

    }



    /**

     * webuploader 上传文件

     */

    public function ajax_upload(){

        // 根据自己的业务调整上传路径、允许的格式、文件大小

        ajax_upload('/Upload/image/');

    }



    /**

     * webuploader 上传demo

     */

    public function webuploader(){

        // 如果是post提交则显示上传的文件 否则显示上传页面

        if(IS_POST){

            p($_POST);die;

        }else{

            $this->display();

        }

    }



    /**

     * 用来做测试用

     */

    public function test(){

        

    }





}



