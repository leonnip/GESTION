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
		//$this->conexion = @mysqli_connect('localhost', 'user_bd_elpais', 'JAMON2011');
		//mysqli_select_db($this->conexion, 'bd_elpaisseleccion');
		
		//$this->conexion = @mysqli_connect('databaseserver.cplbepow19k0.eu-central-1.rds.amazonaws.com', 'leonnip', 'Jamon2011', 'bd_elpaisseleccion');
		
		$this->conexion = @mysqli_connect('databaseserver.cplbepow19k0.eu-central-1.rds.amazonaws.com', $_SESSION['USSER_BD'], $_SESSION['PASS_BD'], $_SESSION['NAME_BD']);
		
		$this->queries = 0;
		$this->resource = null;
	}

	public function execute(){
		if(!($this->resource = mysqli_query($this->conexion, $this->sql))){
			return null;
		}
		$this->queries++;
		return $this->resource;
	}

	public function alter(){
		if(!($this->resource = mysqli_query($this->conexion, $this->sql))){
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
	
	
	public function Affected_Rows() {
		if ($this->conexion !== NUlL) {
            return mysqli_affected_rows($this->conexion); 
        }
        return NULL; 
	}
	public function getInsertID() {
        if ($this->conexion !== NUlL) {
            return mysqli_insert_id($this->conexion); 
        }
        return NULL;  
    }
	public function AutoCommit() {
		return mysqli_autocommit($this->conexion, FALSE);
	}
	public function Commit() {
		return mysqli_commit($this->conexion);
	}
	public function Rollback() {
		return mysqli_rollback($this->conexion);
	}
	
}
?>