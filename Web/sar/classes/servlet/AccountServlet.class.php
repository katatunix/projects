<?php

__import('servlet/AbstractServlet');

__import('facade/BasicFacade');
__import('facade/AccountFacade');

__import('webutils/WebUtils');

__import('facade/PObject');

class AccountServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? TRUE : FALSE;
		switch ($action) {
			case 'index'	:
			case 'login'	:
				return TRUE;
			case 'logout'	:
			case 'cpass'	:
				return $logged;
			case 'viewaccountlist' 		:
			case 'viewaddstaffaccount'	:
			case 'addstaffaccount'		:
			case 'vieweditstaffaccount'	:
			case 'editstaffaccount'		:
			case 'resetpassword'		:
				return $logged && BasicFacade::instance()->isAdmin($role);
		}
		return FALSE;
	}
	
	public function index() {
		WebUtils::redirect(__SITE_CONTEXT . '/account/login');
	}

	public function login() {
		if (WebUtils::hasSESSION(__SKEY)) {
			WebUtils::redirect(__SITE_CONTEXT . '/dashboard');
			return;
		}
		
		if (WebUtils::isPOST()) {
			$username = WebUtils::obtainPOST('username');
			$password = WebUtils::obtainPOST('password');
			
			BasicFacade::instance()->startTrans();
			$p = AccountFacade::instance()->checkLogin($username, $password);
			BasicFacade::instance()->commit();
			
			$account = isset($p->accounts) ? reset($p->accounts) : NULL;
			
			if ($account) {
				BasicFacade::instance()->closeDataSource();
				WebUtils::setSESSION(__SKEY, $account);
				WebUtils::redirect(__SITE_CONTEXT . '/dashboard');
				return;
			}
			
			$this->ui->message = 'Incorrect username/password or your account has been deactivated.';
			if ($username) $this->ui->username = $username;
		}
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->pageTitle = 'Login';
		$this->ui->pageContent = 'LoginUI.php';
		
		$this->ui->headerMenuItem = 'login';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function logout() {
		WebUtils::removeSESSION(__SKEY);
		WebUtils::redirect(__SITE_CONTEXT);
	}

	public function cpass() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		if (WebUtils::isPOST()) {
			BasicFacade::instance()->startTrans();
			
			$message = new PObject();
			$message->isError = TRUE;
			$message->list = array();
			
			$currentPassword		= WebUtils::obtainPOST('currentPassword');
			$newPassword			= WebUtils::obtainPOST('newPassword');
			$newPasswordConfirm		= WebUtils::obtainPOST('newPasswordConfirm');
			
			if (!$currentPassword) {
				$message->list[] = 'Current password must not be blank.';
			} else if ( !AccountFacade::instance()->checkPassword($account->id, $currentPassword) ) {
				$message->list[] = 'Current password is incorrect.';
			}
			
			if (!$newPassword) {
				$message->list[] = 'New password must not be blank.';
			}
			if (!$newPasswordConfirm) {
				$message->list[] = 'New password confirm must not be blank.';
			}
			if ( $newPassword && $newPasswordConfirm && $newPassword != $newPasswordConfirm ) {
				$message->list[] = 'New password confirm must be matched with New password.';
			}
			
			if (count($message->list) == 0) { // OK
				if ( AccountFacade::instance()->changePassword($account->id, $newPassword) ) {
					$message->list[] = 'Password has been changed successfully.';
					$message->isError = FALSE;
				} else {
					$message->list[] = 'Something went wrong, password cannot be changed.';
				}
			}
			
			BasicFacade::instance()->commit();
		}
		
		BasicFacade::instance()->closeDataSource();
		
		if ( isset($message) && count($message->list) > 0 ) {
			$this->ui->message = $message;
		}
		
		$this->ui->pageTitle = 'Change password';
		$this->ui->pageContent = 'ChangePasswordUI.php';
		
		$this->ui->headerMenuItem = 'cpass';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function viewaccountlist(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
	    $listrole = array();
	    $listrole[] = 1;
	    $listrole[] = 2;
	    $listrole[] = 3;
	    $listrole[] = 4;
		$p = AccountFacade::instance()->findByRoles($listrole);
		BasicFacade::instance()->closeDataSource();
		$this->ui->accounts = $p->accounts;
		
		$this->ui->pageTitle = "View Account List";
		$this->ui->pageContent = 'AccountListUI.php';
		$this->ui->show('layout/MainLayout.php');
	}
	
	public function viewaddstaffaccount(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		$this->ui->accounts = NULL;
		$this->ui->pageTitle = "Add New Staff Account";
		$this->ui->pageContent = 'StaffAddOrEditUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	public function vieweditstaffaccount(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		if (WebUtils::isPOST()) return;
		
		$id = WebUtils::obtainGET('id');
		
		BasicFacade::instance()->startTrans();
		$p = AccountFacade::instance()->findById($id);
		BasicFacade::instance()->commit();
		
		$account = array_pop($p->accounts);
		
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->accounts = $account;//======================
		
		$this->ui->pageTitle = 'Edit Staff Account';
		$this->ui->pageContent = 'StaffAddOrEditUI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}
	public function addstaffaccount(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;

		if (WebUtils::isPOST()) {
			$message = new PObject();
			$message->isError = TRUE;
			$message->list = array();
			
			BasicFacade::instance()->startTrans();			
			$username		= WebUtils::obtainPOST('username');
			//$password		= WebUtils::obtainPOST('password');
			$fullname		= WebUtils::obtainPOST('fullname');
			$gender			= WebUtils::obtainPOST('gender');
			$dob			= WebUtils::obtainPOST('dob');
			$role			= WebUtils::obtainPOST('role');
			if(WebUtils::obtainPOST('isActive') == "on"){
				$isActive = 1;
			}else $isActive = 0;
			
			if(!$username || !trim($username)){
				$message->list[] = "Username cannot be blank!";
			}elseif(!$fullname){
				$message->list[] = "Please input your Fullname";
			}elseif(!$dob){
				$message->list[] = "Please input your birthday";
			}elseif(!AccountFacade::instance()->checkDuplicateUsername($username)){
				$message->list[] = "This username has been taken already. Please choose another";
			}
			
			if(count($message->list) == 0){
				if(AccountFacade::instance()->addStaffAccount($username, $fullname, $gender, $dob, $role, $isActive)){
					$message->list[] = "A new account has been created sucessully";
					$message->isError = FALSE;
				}else{
					$message->list[] = "Failed in creating new account. Please try again";
				}
			}
			if ( isset($message) && count($message->list) > 0 ) {
			$this->ui->message = $message;
			}
		//$result = AccountFacade::instance()->addStaffAccount($username, $fullname, $gender, $dob, $role, $isActive);
		BasicFacade::instance()->commit();
		BasicFacade::instance()->closeDataSource();
		
		$this->ui->accounts = NULL;
		$this->ui->pageTitle = "Add New Staff Account";
		$this->ui->pageContent = 'StaffAddOrEditUI.php';
		
		$this->ui->show('layout/MainLayout.php');
		}
	}
	
	public function editstaffaccount(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
			if (WebUtils::isPOST()) {
				$message = new PObject();
				$message->isError = TRUE;
				$message->list = array();
				
				BasicFacade::instance()->startTrans();
				$id = WebUtils::obtainPOST('id');		
				$fullname	= WebUtils::obtainPOST('fullname');
				$gender		= WebUtils::obtainPOST('gender');
				$dob		= WebUtils::obtainPOST('dob');
				if(WebUtils::obtainPOST('isActive') == "on"){
				$isActive = 1;
				}else $isActive = 0;
				
				if(!$fullname){
				$message->list[] = "Fullname cannot be blank";
				}elseif(!$dob){
				$message->list[] = "Please input your birthday";
				}
				/* elseif(!AccountFacade::instance()->checkDuplicateUsername($username)){
				$message->list[] = "This username has been taken already. Please choose another";
				} */
				
				if(count($message->list) == 0){
				if(AccountFacade::instance()->editStaffAccount($id, $fullname, $gender, $dob, $isActive)){
					$message->list[] = "Account has been updated sucessully";
					$message->isError = FALSE;
					BasicFacade::instance()->commit();
					BasicFacade::instance()->closeDataSource();
				}else{
					$message->list[] = 'Failed in updating the account. Please try again';
				}
			}
			if ( isset($message) && count($message->list) > 0 ) {
			$this->ui->message = $message;
			$account = AccountFacade::instance()->findById($id);
			$this->ui->accounts = reset($account->accounts);
			
			$this->ui->pageTitle = 'Edit Staff Account';
			$this->ui->pageContent = 'StaffAddOrEditUI.php';
			$this->ui->show('layout/MainLayout.php');
			}
			}
	}
	
	public function resetpassword(){
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
			//if (WebUtils::isPOST())
			 {
				BasicFacade::instance()->startTrans();
				$id	= WebUtils::obtainGET('id');
				$ok = AccountFacade::instance()->resetPassword($id);
				if ($ok){
					BasicFacade::instance()->commit();	
				}else BasicFacade::instance()->rollback();
				
				BasicFacade::instance()->closeDataSource();
				
				//$this->viewaccountlist();
			}
			$ok = $ok ? "1" : "0";
			WebUtils::redirect(__SITE_CONTEXT . "/account/viewaccountlist?ok=$ok");
	}	
}
?>
