<?php

__import('facade/PObject');
__import('business/BasicHome');
__import('business/Role');

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
	
	public function isStaff($role) {
		return $role == Role::STAFF;
	}
	
	public function isLocMan($role) {
		return $role == Role::LOC_MAN;
	}
	
	public function isNatMan($role) {
		return $role == Role::NAT_MAN;
	}
	
}

?>
