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

  public function uploadfile(){
    $file = $_FILES;
    $uploadname = "./upload/" . $file["printempslogin"]["name"];
    $result = move_uploaded_file($file["printempslogin"]["tmp_name"],$uploadname);
    if($result){
      $myphpexcel = new myphpexcel();
      $excel = $myphpexcel->loadexcel5(realpath($uploadname));
      $sheet = $excel['sheet'];
      $highestRow = $excel['highestRow'];
      $highestColumm = $excel['highestColumm'];
      for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
          for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
              $title[$column] = $this->translate(trim($sheet->getCell($column.$row)->getValue()));
          }
          if(!in_array('',$title)){//$title is the keys
          break;
          }
      }
      $row++;
      $totle = 0;
      for ($row ; $row <= $highestRow; $row++){
          for ($column = 'A'; $column <= $highestColumm; $column++) {
            $col = $title[$column];
            if(in_array($col, array('cardno','firstname','secondname', 'bak', 'openid')))//control insert datas
              $data[$col] = trim($sheet->getCell($column.$row)->getValue());
          }
           if(implode($data)!="" && isset($data['cardno'])){
             if(!$this->sql->checkData(array('cardno' => $data['cardno']), 'same_login')){
                $this->sql->insertData($data, 'same_login');
                $totle++;
              }
          }
      }
      unlink($uploadname);
      return array('code' => '10', 'msg' => 'added '.$totle.' users');
    }
    unlink($uploadname);
    return array('code' => '9', 'msg' => 'upload file errors');
  }

//sub function
  private function translate($name){
    $list = array(
      'NUMERO' => 'cardno',
      'NOM' => 'firstname',
      'PRENOM' => 'secondname',
      'PAYS' => 'bak',
    );
    if(isset($list[$name]))
      return $list[$name];
    return $name;
  }
}
