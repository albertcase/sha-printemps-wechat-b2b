<?php

class guestadmin
{
  private $sql;
  private $request;

  public function __construct(){
    $this->sql = new database();
    $this->request = new uprequest();
  }

  public function getpage(){
    $data = array(
      array('key' => 'bak' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'firstname' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'secondname' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'cardno' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'openidd' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'numb' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'one' ,'type'=> 'post' ,'regtype'=> 'text'),
    );
    if(!$keys = $this->request->uselykeys($data))
      return '11'; /*data formate error*/
    if(!is_array($keys))
        $keys = array();
    $numb = isset($keys['numb'])?$keys['numb']:'1';
    $one = isset($keys['one'])?$keys['one']:'10';
    unset($keys['numb']);
    unset($keys['one']);
    if(isset($keys['openidd'])){
      if($keys['openidd'] == '1'){
        $keys['openid'] = "^.";
      }else{
        $keys['openid'] = "^$";
      }
    }
    unset($keys['openidd']);
    return $this->sql->Reggetpage($numb ,$one ,$keys ,array(),'same_login');
  }

  public function comfirmbespk(){
    $data = array(
      array('key' => 'id' ,'type'=> 'post' ,'regtype'=> 'number'),
    );
    if(!$keys = $this->request->comfirmKeys($data))
      return '11'; /*data formate error*/
    if($this->sql->Sqlupdate('same_order',array('status'=>'1'),'id=:id',array(':id' => $keys['id']))){
      return '12'; /*data instart success*/
    }
    return '13';/*data insert error*/
  }

  public function getcount(){
    $data = array(
      array('key' => 'bak' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'firstname' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'secondname' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'openidd' ,'type'=> 'post' ,'regtype'=> 'text'),
      array('key' => 'cardno' ,'type'=> 'post' ,'regtype'=> 'text'),
    );
    if(!$keys = $this->request->uselykeys($data))
      return '11'; /*data formate error*/
    if(!is_array($keys))
      $keys = array();
      if(isset($keys['openidd'])){
        if($keys['openidd'] == '1'){
          $keys['openid'] = "^.";
        }else{
          $keys['openid'] = "^$";
        }
      }
    unset($keys['openidd']);
    return array('count' => $this->sql->Reggetcount('same_login',$keys));
  }
}
