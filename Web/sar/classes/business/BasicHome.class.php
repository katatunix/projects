<?php

__import('business/AbstractHome');

class BasicHome extends AbstractHome {
	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new BasicHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function closeDataSource() {
		DataSource::freeInstance();
	}
	
	public function startTrans() {
		return DataSource::instance()->startTransaction();
	}
	
	public function commit() {
		return DataSource::instance()->commit();
	}
	
	public function rollback() {
		return DataSource::instance()->rollback();
	}
}

?>
