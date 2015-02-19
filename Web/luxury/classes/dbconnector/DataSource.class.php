<?php

class DataSource {
	
	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new DataSource();}return self::$instance;}
	//====================================================================================
	
	private static $host		= __DB_HOST;
	private static $username	= __DB_USERNAME;
	private static $password	= __DB_PASSWORD;
	private static $database	= __DB_NAME;
	
	private $link;
	
	public static function freeInstance() {
		if (self::$instance) {
			self::$instance->closeSql();
			self::$instance = NULL;
		}
	}
	
	private function __construct() {
		$this->link = mysqli_connect(self::$host, self::$username, self::$password)
			or die('Could not connect to the host database: ' . self::$host);
			
		mysqli_select_db($this->link, self::$database)
			or die('Error when select the database: ' . self::$database);
			
		mysqli_query($this->link, "SET NAMES 'utf8'");
	}
	
	private function closeSql() {
		return mysqli_close($this->link);
	}
	
	//=======================================================================================
	
	public function startTransaction() {
		//$ret = mysqli_begin_transaction($this->link);
		//return $ret;
		return mysqli_begin_transaction($this->link);
		
		//return mysqli_query("START TRANSACTION", $this->link);
	}
	
	public function commit() {
		return mysqli_commit($this->link);
		//return mysqli_query("COMMIT", $this->link);
	}
	
	public function rollback() {
		return mysqli_rollback($this->link);
		//return mysqli_query("ROLLBACK", $this->link);
	}
	
	public function setSavePoint($name)
	{
		return mysqli_query($this->link, "SAVEPOINT $name");
	}
	
	public function releaseSavePoint($name)
	{
		return mysqli_query($this->link, "RELEASE SAVEPOINT $name");
	}
	
	public function rollbackToSavePoint($name)
	{
		return mysqli_query($this->link, "ROLLBACK TO $name");
	}
	
	public function escape($str) {
		return mysqli_real_escape_string( $this->link, $str );
	}
	
	public function execute($sql) {
		return mysqli_query($this->link, $sql);
	}
	
	public function getLastId() {
		return mysqli_insert_id($this->link);
	}
	
	public function getLastError() {
		return mysqli_error($this->link);
	}
	
}

?>
