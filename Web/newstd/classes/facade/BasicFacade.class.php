<?php

__import('facade/PObject');
__import('business/BasicHome');

class BasicFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new BasicFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	
	public function closeDataSource() {
		BasicHome::instance()->closeDataSource();
	}
	
	public function startTrans() {
		return BasicHome::instance()->startTrans();
	}
	
	public function commit() {
		return BasicHome::instance()->commit();
	}
	
	public function rollback() {
		return BasicHome::instance()->rollback();
	}
	
}

?>
