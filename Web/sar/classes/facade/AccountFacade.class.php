<?php

__import('facade/PObject');
__import('facade/PObjectConverter');

__import('business/AccountHome');
__import('business/Role');

class AccountFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new AccountFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findById($accountId) {
		$account = AccountHome::instance()->findById($accountId);
		if (!$account) return NULL;
		
		$account->clearAllExternals();
		
		// always get Student Ais data
		if ($account->getRole() == Role::STUDENT) {
			$account->loadStudentAis()->clearAllExternals();
		}
		
		$cv = new PObjectConverter();
		$cv->convertAccount($account);
		
		return $cv->getResult();
	}
	
	public function checkLogin($username, $password) {
		$account = AccountHome::instance()->checkLogin($username, $password);
		if (!$account) return NULL;
		
		$account->clearAllExternals();
		
		$cv = new PObjectConverter();
		$cv->convertAccount($account);
		
		return $cv->getResult();
	}
	
	public function changePassword($accountId, $newPassword) {
		return AccountHome::instance()->changePassword($accountId, $newPassword);
	}
	
	public function checkPassword($accountId, $password) {
		return AccountHome::instance()->checkPassword($accountId, $password);
	}
	
	// $rolesArray is NULL means all
	public function findByRoles($rolesArray) {
		$accounts = AccountHome::instance()->findByRoles($rolesArray);
		
		$cv = new PObjectConverter();
		
		foreach ($accounts as $account) {
			$account->clearAllExternals();
			$cv->convertAccount($account);
		}
		
		return $cv->getResult();
	}
	
	public function addStaffAccount($username, $fullname, $gender, $dob, $role, $isActive){
		$result = AccountHome::instance()->addStaffAccount($username, $fullname, $gender, $dob, $role, $isActive);
		if(!$result){
			$result = false;
		}
		return $result;
	}
	public function editStaffAccount($id, $fullname, $gender, $dob, $isActive){
		$result = AccountHome::instance()->editStaffAccount($id, $fullname, $gender, $dob, $isActive);
		if(!$result){
			$result = false;
		}
		return $result;
	}
	public function resetPassword($accountId) {
		return AccountHome::instance()->resetPassword($accountId);
	}
	public function activeDeactive($id, $isActive){
		$result = AccountHome::instance()->activeDeactive($id, $isActive);
		if(!$result){
			$result = false;
		}
		return $result;
	}
	
	public function findListTeachers() {
		return $this->findByRoles(array(Role::LECTURER, ROLE::TUTOR));
	}
	
	public function checkDuplicateUsername($username){
		$check = AccountHome::instance()->checkDuplicateUsername($username);
		return $check;
	}
}

?>
