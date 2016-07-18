<?php

class uprequest
{
  public function get($key ,$val=''){
    return trim(isset($_GET[$key])?$_GET[$key]:$val);
  }

  public function post($key ,$val=''){
    return trim(isset($_POST[$key])?$_POST[$key]:$val);
  }

  public function comfirmKeys($keys){ /*array(array('key' => key ,'type'=> post/get ,'regtype'=> regtype ,$selfReg => '') )*/
    $out = array();
    $k = '';
    $strTest = new strTest();
    foreach($keys as $x){
      $k = $this->$x['type']($x['key']);
      if($x['regtype'] != 'text'){
          if(!$strTest->$x['regtype']($k)){
            return false;
          }
      }
      $out = $out + array($x['key'] => $k);
      unset($k);
    }
    return $out;
  }

  public function uselykeys($keys){
    $out = array();
    $kk = $this->comfirmKeys($keys);
    if(!is_array($kk))
      return false;/*format error*/
    foreach($kk as $x => $x_val){
      if($x_val != '')
        $out[$x] = $x_val;
    }
    if(count($out)>0)
      return $out;
    return true;
  }

  public function text($key){
    return $key;
  }

}
