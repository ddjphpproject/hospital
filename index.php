<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
// 检测是否是新安装
// function check_wap() {
// if (isset($_SERVER['HTTP_VIA'])) return true;
// if (isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) return true;
// if (isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) return true;
// if (strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0) {
// // Check whether the browser/gateway says it accepts WML.
// $br = "WML";
// } else {
// $browser = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
// if(empty($browser)) return true;
// $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
// $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
// $found_mobile=checkSubstrs($mobile_os_list,$browser) ||
// checkSubstrs($mobile_token_list,$browser);
// if($found_mobile)
// $br ="WML";
// else $br = "WWW";
// }
// if($br == "WML") {
// return true;
// } else {
// return false;
// }
// }
// function checkSubstrs($list,$str){
// $flag = false;
// for($i=0;$i<count($list);$i++){
// if(strpos($str,$list[$i]) > 0){
// $flag = true;
// break;
// }
// }
// return $flag;
// }
if(file_exists("./Public/install") && !file_exists("./Public/install/install.lock")){
  // 组装安装url
  $url=$_SERVER['HTTP_HOST'].trim($_SERVER['SCRIPT_NAME'],'index.php').'Public/install/index.php';
  // 使用http://域名方式访问；避免./Public/install 路径方式的兼容性和其他出错问题
  header("Location:http://$url");
  die;
}
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 定义应用目录
define('APP_PATH','./Application/');

// if(check_wap()){
// 	// 手机定义缓存目录
// define('RUNTIME_PATH','./Runtimem/');
// }else{
// 定义缓存目录
define('RUNTIME_PATH','./Runtime/');
// }

// if(check_wap()){
// 手机定义模板文件默认目录
// define("TMPL_PATH","./mtpl/");
// }else{
// 定义模板文件默认目录
define("TMPL_PATH","./tpl/");
// }

// 定义oss的url
define("OSS_URL","");

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单