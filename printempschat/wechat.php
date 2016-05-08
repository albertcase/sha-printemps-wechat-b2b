<?php
class wechat{
  private $_TOKEN = 'printempsb2b';
  private $_appid = 'wx5724db07982c3896';
  private $_secret = 'd9e9ae55f59bb02fa1b71146520cda03';
  private $key = '29FB77CB8E94B358';
  private $iv = '6E4CAB2EAAF32E90';

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
        return false;
      }
    }
    return $access_token;
  }

  public function aes128_cbc_encrypt($key, $data, $iv) {
    if(16 !== strlen($key)) $key = hash('MD5', $key, true);
    if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
    $padding = 16 - (strlen($data) % 16);
    $data .= str_repeat(chr($padding), $padding);
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
  }

  public function aes128_cbc_decrypt($key, $data, $iv) {
    if(16 !== strlen($key)) $key = hash('MD5', $key, true);
    if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
    $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
    $padding = ord($data[strlen($data) - 1]);
    return substr($data, 0, -$padding);
  }

  public function backaccesstoken(){
    if($AccessToken = $this->getAccessToken()){
      return array(
        'code' => '10',
        'access_token' => $this->aes128_cbc_encrypt($this->key, $AccessToken, $this->iv),
    );
    }
    return array('code' => '9');
  }

  public function deacccesstoken(){
    $token = $this->backaccesstoken();
    return aes128_cbc_decrypt($this->key, $token['access_token'], $this->iv);
  }
}
