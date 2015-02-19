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
	
	public function isStudent($role) {
		return $role == Role::STUDENT;
	}
	
	public function isAdmin($role) {
		return $role == Role::ADMIN;
	}
	
	public function isCoordinator($role) {
		return $role == Role::COORDINATOR;
	}
	
	public function isLecturer($role) {
		return $role == Role::LECTURER;
	}
	
	public function isTutor($role) {
		return $role == Role::TUTOR;
	}
}

?>
