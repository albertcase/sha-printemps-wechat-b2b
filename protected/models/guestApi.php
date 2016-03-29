<?php

class guestApi
{
  private $sql;
  private $request;

  public function __construct(){
    $this->sql = new database();
    $this->request = new uprequest();
  }

  public function alaialogin(){
    $data = array(
      array('key' => 'user' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'password' ,'type'=> 'post' ,'regtype'=> 'text'),
    );
    if(!$keys = $this->request->comfirmKeys($data))
      return '11'; /*data formate error*/
    $keys['password'] = md5($keys['password']);
    if($this->sql->checkData($keys ,'same_guest')){
      $_SESSION['loguser'] = $keys['user'];
      return '14'; /*loginsuccess*/
    }
    return '15'; /*log error*/
  }

  public function alaiauseradd(){
    $data = array(
      array('key' => 'user' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'password' ,'type'=> 'post' ,'regtype'=> 'text'),
    );
    if(!$keys = $this->request->comfirmKeys($data))
      return '11'; /*data formate error*/
    if($this->sql->checkData(array('user' => $keys['user']) ,'same_guest'))
      return '14'; /*user name repeat*/
    $keys['password'] = md5($keys['password']);
    if($this->sql->insertData($keys ,'same_guest'))
        return '12'; /*data instart success*/
      return '13';/*data insert error*/
  }
}
