<?php 

class DBHelper {

	private $debug = false; 

	private $version = "";

	private $link_id = NULL;

	function __construct($dbhost, $dbuser, $dbpwd,$dbname){
		$this->connect($dbhost, $dbuser, $dbpwd,$dbname);
	}
	/**
	 * 连接数据库
	 *
	 * param  string  $dbhost       数据库主机名<br />
	 * param  string  $dbuser       数据库用户名<br />
	 * param  string  $dbpw         数据库密码<br />
	 * param  string  $dbname       数据库名称<br />
	 * param  string  $dbcharset    数据库字符集<br />
	 * param  string  $pconnect     持久链接,1为开启,0为关闭
	 * return bool
	 **/
	function connect($dbhost, $dbuser, $dbpwd, $dbname = '', $dbcharset = 'utf8', $pconnect = 0) {
		if ($pconnect) {
			if (! $this->link_id = mysql_pconnect ( $dbhost, $dbuser, $dbpwd )) {
				$this->ErrorMsg ();
			}
		} else {
			if (! $this->link_id = mysql_connect ( $dbhost, $dbuser, $dbpwd, 1 )) {
				$this->ErrorMsg ();
			}
		}
		$this->version = mysql_get_server_info ( $this->link_id );
		if ($this->getVersion () > '4.1') {
			if ($dbcharset) {
				mysql_query ( "SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link_id );
			}

			if ($this->getVersion () > '5.0.1') {
				mysql_query ( "SET sql_mode=''", $this->link_id );
			}
		}
		if (mysql_select_db ( $dbname, $this->link_id ) === false) {
			$this->ErrorMsg ();
		}
	}

	function save($table, $field_values) {
		$fields = array ();
		$values = array ();
		$field_names = $this->getCol('DESC '.$table);

		foreach ( $field_names as $value ) {
			if (array_key_exists ( $value, $field_values ) == true) {
				$fields [] = $value;
				$values [] = "'" . mysql_real_escape_string($field_values [$value]) . "'";
			}
		}
		if (!empty($fields)) {
			$sql = 'INSERT INTO '.$table.' ('.implode(',',$fields).') VALUES ('.implode ( ',',$values ).')';
			if($this->query ($sql)){
				return $this->getLastInsertId ();
			}
		}
		return false;
	}

	function getLastInsertId() {
		return mysql_insert_id ( $this->link_id );
	}

	function update($table, $field_values, $where = '') {
		$field_names = $this->getCol ( 'DESC ' . $table );
		$sets = array ();
		foreach ( $field_names as $value ) {
			if (array_key_exists ( $value, $field_values ) == true) {
				$sets [] = $value . " = '" . $field_values [$value] . "'";
			}
		}
		if (! empty ( $sets )) {
			$sql = 'UPDATE ' . $table . ' SET ' . implode ( ', ', $sets ) . ' WHERE ' . $where;
		}
		if ($sql) {
			return $this->query ( $sql );
		} else {
			return false;
		}
	}


	function delete($table,$where=''){
		if(empty($where)){
			$sql = 'DELETE FROM '.$table;
		}else{
			$sql = 'DELETE FROM '.$table.' WHERE '.$where;
		}
		if($this->query ( $sql )){
			return true;
		}else{
			return false;
		}
	}

	function findAll($sql) {
		$res = $this->query ( $sql );
		if ($res !== false) {
			$arr = array ();
			$row = mysql_fetch_assoc ( $res );
			while ($row) {
				$arr [] = $row;
				$row = mysql_fetch_assoc ( $res );
			}
			return $arr;
		} else {
			return false;
		}
	}

	function selectLimit($sql, $numrows=-1, $offset=-1) {
		if($offset==-1){
			$sql .= ' LIMIT ' . $numrows;
		}else{
			$sql .= ' LIMIT ' . $offset . ', ' . $numrows;
		}
		return $this->findAll( $sql );
	}


	function find($sql) {
		$res = $this->query ( $sql );
		if ($res !== false) {
			return mysql_fetch_assoc ( $res );
		} else {
			return false;
		}
	}


	function getRowsNum($sql) {
		$query = $this->query ( $sql );
		return mysql_num_rows ( $query );
	}

	function getOneField($sql){
		$val = mysql_fetch_array($this->query ( $sql ));
		return $val[0];
	}


	function getCol($sql) {
		$res = $this->query( $sql );
		if ($res !== false) {
			$arr = array ();
			$row = mysql_fetch_row ($res);
			while ($row) {
				$arr [] = $row [0];
				$row = mysql_fetch_row ($res);
			}
			return $arr;
		} else {
			return false;
		}
	}

	function sumsql($sql) {
		$res = $this->query( $sql );
		if ($res !== false) {
			return $res;
		} else {
			return false;
		}
	}
	
	function query($sql) {
		if ($this->debug) echo "<pre><hr>\n" . $sql . "\n<hr></pre>";
		if (! ($query = mysql_query ( $sql, $this->link_id ))) {
			return false;
		} else {
			return $query;
		}
	}


	function getVersion() {
		return $this->version;
	}


	function debug(){
		$this->debug=true;
	}

	
	function ErrorMsg($message = '') {
		if (empty($message)) $message = @mysql_error ();
		Analog::log($message,Analog::ERROR);
		//exit ($message);
	}


	function close() {
		return mysql_close ( $this->link_id );
	}
}

?>