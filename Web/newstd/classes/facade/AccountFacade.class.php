<?php

__import('facade/PObjectConverter');
__import('business/AccountHome');

class AccountFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new AccountFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findById($id) {
		$cv = new PObjectConverter();
		$obj = AccountHome::instance()->findById($id);
		if ($obj) {
			$obj->clearAllExternals();
			$cv->convertAccount($obj);
		}
		
		return $cv->getResult();
	}
	
	public function checkLogin($username, $password) {
		$cv = new PObjectConverter();
		$obj = AccountHome::instance()->checkLogin($username, $password);
		if ($obj) {
			$obj->clearAllExternals();
			$cv->convertAccount($obj);
		}

		return $cv->getResult();
	}

}

?>
