<?php

__import('servlet/AbstractServlet');

__import('facade/BasicFacade');
__import('facade/AccountFacade');
__import('facade/PObject');

__import('webutils/WebUtils');

class AccountServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		$logged = $account ? true : false;
		switch ($action) {
			case 'index'	:
			case 'login'	:
				return true;
			case 'logout'	:
				return $logged;
		}
		return false;
	}
	
	public function index() {
		WebUtils::redirect(__SITE_CONTEXT . '/account/login');
	}

	public function login() {
		if (WebUtils::hasSESSION(__SKEY)) {
			WebUtils::redirect(__SITE_CONTEXT . '/dashboard/index');
			return;
		}
		
		if (WebUtils::isPOST()) {
			$username = WebUtils::obtainPOST('username');
			$password = WebUtils::obtainPOST('password');
			
			BasicFacade::instance()->startTrans();
			$p = AccountFacade::instance()->checkLogin($username, $password);
			BasicFacade::instance()->rollback();
			BasicFacade::instance()->closeDataSource();

			$account = isset($p->accounts) ? reset($p->accounts) : NULL;
			
			if ($account) {
				WebUtils::setSESSION(__SKEY, $account);
				WebUtils::redirect(__SITE_CONTEXT . '/dashboard/index');
				return;
			}
			
			$this->ui->message = 'Incorrect username/password or your account has been deactivated.';
			if ($username) $this->ui->username = $username;
		}

		$this->ui->loggedAccount = NULL;
		
		$this->ui->pageTitle = 'Login';
		$this->ui->pageContent = 'LoginUI.php';
		
		$this->ui->show('MainLayout.php');
	}
	
	public function logout() {
		WebUtils::removeSESSION(__SKEY);
		WebUtils::redirect(__SITE_CONTEXT);
	}
	
}
?>
