<?php
class DataBase {		
	private $conexion;
	private $resource;
	private $sql;
	public static $queries;
	private static $_singleton;

	public static function getInstance(){
		if (is_null (self::$_singleton)) {
			self::$_singleton = new DataBase();
		}
		return self::$_singleton;
	}

	private function __construct(){
		$this->conexion = @mysqli_connect('localhost', 'user_bd_15encasa', 'Jamon2011');
		@mysqli_select_db('bd_15encasaf', $this->conexion);
		
		//$this->conexion = @mysqli_connect('localhost', 'leonnip_15encasa', 'Jamon2011');
		//mysqli_select_db('bd_15encasa', $this->conexion);
		
		//$this->conexion = @mysqli_connect('localhost', 'root', 'kattyta123');
		//mysqli_select_db('bd_solo100', $this->conexion);
		$this->queries = 0;
		$this->resource = null;
	}

	public function execute(){
		if(!($this->resource = mysqli_query($this->sql, $this->conexion))){
			return null;
		}
		$this->queries++;
		return $this->resource;
	}

	public function alter(){
		if(!($this->resource = mysqli_query($this->sql, $this->conexion))){
			return false;
		}
		return true;
	}

	public function loadObjectList(){
		if (!($cur = $this->execute())){
			return null;
		}
		$array = array();
		while ($row = mysqli_fetch_object($cur)){
			$array[] = $row;
		}
		return $array;
	}

	public function setQuery($sql){
		if(empty($sql)){
			return false;
		}
		$this->sql = $sql;
		return true;
	}

	public function freeResults(){
		@mysqli_free_result($this->resource);
		return true;
	}

	public function loadObject(){
		if ($cur = $this->execute()){
			if ($object = mysqli_fetch_object($cur)){
				@mysqli_free_result($cur);
				return $object;
			}
			else {
				return null;
			}
		}
		else {
			return false;
		}
	}

	function __destruct(){
		@mysqli_free_result($this->resource);
		@mysqli_close($this->conexion);
	}
	
	public function beginTransaction() {
		mysqli_query("BEGIN");
	}
	public function commit() {
		mysqli_query("COMMIT");
	}
	public function rollback() {
		mysqli_query("ROLLBACK");
	}
	
}
?>