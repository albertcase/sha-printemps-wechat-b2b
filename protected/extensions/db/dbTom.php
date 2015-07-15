<?php
/**
 * 自定义数据库处理类
 * 
 * @package db
 * @author Tom.He
 * @version 1.0
 */
/*
 * $connection = Yii::app()->db;  
		    $sql = 'call  trio_menuManageInsert ("test","test","test")';  
		    $command = $connection->createCommand($sql);  
		    $result = $command->queryColumn();  
		    print_r($result);  
 */
class dbTom {
	
	private static $_connection = null;
	
	public function init()
	{
		if(!self::$_connection){
			self::$_connection = Yii::app()->db;
		}
	}
	
	/**
	 * insert 
	 * @param string $sql
	 * @return integer
	 */
	public function insert ($sql){
		
		$command = $this->execute($sql);
		$result = $command->queryColumn();
		
		return $result[0];
	}
	
	/**
	 * update
	 * @param string $sql
	 * @return integer
	 */
	public function update ($sql){
	
		$command = $this->execute($sql);
		$result = $command->queryColumn();
	
		return $result[0];
	}
	
	/**
	 * execute
	 * 
	 * @param string $sql
	 * @return Resources
	 */
	private function execute ($sql){
		
		$command = self::$_connection->createCommand($sql);
		
		return $command;
	}
	
	
}