<?php
class wechat{
  private $_TOKEN = 'printempsb2b';
  private $_appid = 'wx5724db07982c3896';
  private $_secret = 'd9e9ae55f59bb02fa1b71146520cda03';

  public function getAccessToken()
  {
    $time=file_get_contents(dirname(__FILE__)."/../upload/time.txt");
    $access_token=file_get_contents(dirname(__FILE__)."/../upload/access_token.txt");
    if (!$time || (time() - $time >= 3600)){
      $rs = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->_appid.'&secret='.$this->_secret);
      $rs = json_decode($rs,true);
      if(isset($rs['access_token'])){
        $time = time();
        $access_token = $rs['access_token'];
        $ticketfile = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi");
        $ticketfile = json_decode($ticketfile, true);
        $ticket = $ticketfile['ticket'];
        $fp = fopen(dirname(__FILE__)."/../upload/time.txt", "w");
        fwrite($fp,$time);
        fclose($fp);
        $fp = fopen(dirname(__FILE__)."/../upload/access_token.txt", "w");
        fwrite($fp,$access_token);
        fclose($fp);
        $fp = fopen(dirname(__FILE__)."/../upload/ticket.txt", "w");
        fwrite($fp,$ticket);
        fclose($fp);
        return $rs['access_token'];
      }else{
        return $rs['errcode'];
      }
    }
    return $access_token;
  }
}
