<?php

/**
* ACM for Memcached
*/
class memcaches
{
	public $server = '127.0.0.1';
	public $port = '11211';
	public $prostr = 'b2b';
	private $memcache;

	public function __construct()
	{
	    $this->memcache =  new Memcached();
	    $this->memcache->addServer($this->server, $this->port);
	}

	public function addDatas(array $data){
	    foreach($data as $x)
				$this->addData($x['key'],$x['val'],$x['limit']);
	}

	public function addData($key,$val,$limit = 0)
	{
		$key = $this->prostr.$key;
		$this->memcache->set($key, $val , $limit);
	}

	public function getData($key)
	{
		$key = $this->prostr.$key;
		return $this->memcache->get($key);
	}

	public function getDatas($data)
	{
		$out = array();
		foreach($data as $x)
		    $out[$x] = $this->getData($x);
		return $out;
	}

	public function delData($key ,$delay='0')
	{
	  $key = $this->prostr.$key;
	  return $this->memcache->delete($key, $delay);
	}

	public function delDatas($data)
	{
	  foreach($data as $x)
		    $this->dalData($x);
	}

	public function rewriteData($key ,$key_val ,$limit = 0)
	{
	  $key = $this->prostr.$key;
	  $this->memcache->set($key, $val , $limit);
	}

	public function incremkey($key ,$sum = 1){
		$key = $this->prostr.$key;
		return $this->memcache->increment($key, $sum);
	}


}
