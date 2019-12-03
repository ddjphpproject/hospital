<?php 

 

namespace wxmsg; 

 

class WeiXin{ 

      

      public function __construct(){ 

       $config = C("THINK_SDK_WXOAUTH"); 

         

         $this->appid = 'wx557dc429506a40d8'; 

         $this->appsecret = 'd674b06143302d1648240233ca46c817';

         $this->access_token ='';

        //判断access_token是否存在，不存在重新获取 

         if(session('access_token_bd')){ 

              $this->access_token =  session('access_token_bd'); 

         }else{ 

              $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret; 

              $res = json_decode($this->http_request($url),true); 

              $this->access_token = $res['access_token']; 

              session(array('name'=>'access_token_bd','value'=>$this->access_token,'expire'=>7200));//保存access_token  

         } 

      } 

       

      //发送模板消息 

      public function send_template_message($data){ 

                

         

              $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->access_token; 

              $res=$this->http_request($url,$data); 

              return $res; 

              return json_decode($res,true); 

      } 

 

     

    public function template($openid,$template_id,$data,$url,$topcolor){ 

         // $url = "http://pc.tianjianh.cn/yyzh";

       // $member=M('member')->where(array('id'=>$uid))->find(); 

       $openid=$openid; //根据用户id获取数据库保存的openid

        switch($template_id){ 

           case 1: 

           $tpl="rxzQQkRUHYbmh34qRMLkq59A6F9fOqb-M3JFw8umObA";//客户预约通知 

           break; 

           case 2: 

           $tpl="bWy0BWHhdBTAVe07ninMcnQhz8OpbdTpP22_bjpT5HI";//预约成功提醒 

           break; 

           case 3: 

           $tpl="2buHZlscCJsqpDWNOMOuWDuwwyPeMebASxi3BACuiDw";//任务通知 

           break; 

           case 4: 

           $tpl="g5XuPhHUbymZ8-7KLo4j3exHzYDFHjPhVJDQto13-Kc";//订单完成通知 

           break; 

           // case 5: 

           // $tpl="pF0szsKuAh2u7ziqH3Im0GscRwrXaUNwXGrUNZKYHy8";//任务完成通知 

           // break; 

           // case 2: 

           // $tpl="pXFC-Lc4TgiAl-nQWMMbD7eLoi25SIWgSU3IXrcKcmw";//订单待支付提醒 

           // break; 

           default: 

            $tpl = ''; 

            break; 

            

        } 

        if(!$tpl){ 

          return ''; 

        } 

        $template=array('touser'     =>$openid, 

                        'template_id'=>$tpl, 

                        'url'        =>$url, 

                        'topcolor'   =>$topcolor, 

                        'data'       =>$data 

                 ); 

     

     

    return $template; 

     

    } 



    protected function http_request($url,$data=null){ 

      $curl = curl_init(); 

      curl_setopt($curl,CURLOPT_URL,$url); 

      curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE); 

      curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE); 

      if(!empty($data)){ 

         curl_setopt($curl,CURLOPT_POST,1); 

         curl_setopt($curl,CURLOPT_POSTFIELDS,$data); 

      } 

      curl_setopt($curl,CURLOPT_RETURNTRANSFER,1); 

      $output = curl_exec($curl); 

      curl_close($curl); 

        return $output; 

    }

}