<?php
require_once dirname(__FILE__).'/memcachesC.php';
class forwordGrata{

  private $_memcache;
  private $prostr = 'grata:';
  private $count = 'count';
  private $now = 'now';
  private $list = 'list:';
  private $changT = 'changT';
  private $outtime = '100';

  public function __construct(){
    $this->_memcache = new memcachesC();
    if(!$this->_memcache->getData($this->prostr.$this->count)){
      $this->_memcache->addData($this->prostr.$this->count ,'1');
      $this->_memcache->addData($this->prostr.$this->now ,'1');
    }
    $this->closeforwardJob();
  }

  public function closeforwardJob(){
    if($time = $this->_memcache->getData($this->prostr.$this->changT)){
      if((time() - $time) > $this->outtime){
        exec("nohup ".dirname(__FILE__)."/closeforwardJob.sh >>./closeforwardJob.log 2>&1 &");
        $this->_memcache->delData($this->prostr.$this->changT);
      }
    }
  }

  public function runforwardJob(){
    exec("nohup ".dirname(__FILE__)."/runforwardJob.sh >>./runforwardJob.log 2>&1 &");
  }

  public function addforwardJob($data){
    $key = $this->_memcache->incremkey($this->prostr.$this->count);
    $this->_memcache->addData($this->prostr.$this->list.$key, $data, '800');
  }

  public function ststus(){
    if($this->_memcache->getData($this->prostr.$this->now) < $this->_memcache->getData($this->prostr.$this->count)){
      return true;
    }else{
      return false;
    }
  }

  public function runforward($data){
    $this->send_xml($data);
  }

  public function pushMsg(){
    $this->_memcache->addData($this->prostr.$this->changT, time());
    $key = $this->_memcache->incremkey($this->prostr.$this->now);
    $this->runforward($this->_memcache->getData($this->prostr.$this->list.$key));
    $this->_memcache->delData($this->prostr.$this->list.$key);
    $this->_memcache->delData($this->prostr.$this->changT);
  }

  public function send_xml($xmlData){
    $url = 'https://apiint.guestops.com/connect-api/wechat/229310580131';
    $header[] = "Content-type: text/xml"; //定义content-type为xml,注意是数组
    $ch = curl_init ($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
    $response = curl_exec($ch);
    if(curl_errno($ch)){
        print curl_error($ch);
    }
    curl_close($ch);
    $this->_memcache->addData('aaaa', $xmlData);
  }

  public function gomsg(){
    while($this->ststus())
    {
      $this->pushMsg();
    }
  }
}
?>
