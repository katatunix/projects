<?php

__import('servlet/AbstractServlet');

__import('webutils/WebUtils');

__import('facade/BasicFacade');
__import('facade/AccountFacade');

//============================================================
/*class MyService {
	function greet($param) {
		$retval = 'haha: ' . $param->name;
		
		$result = new stdClass();
		$result->greetReturn = $retval;
		return $result;
	}
}*/

//================================================================

class DashboardServlet extends AbstractServlet {
//
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? TRUE : FALSE;
		switch ($action) {
			case 'index': return $logged;
			
			//
			//case 'test' : return TRUE;
			//
		}
		return FALSE;
	}
	
	public function index() {
		$ok = FALSE;
		
		$loggedAccount = WebUtils::getSESSION(__SKEY);
		$account = NULL;
		if ($loggedAccount) {
			BasicFacade::instance()->startTrans();
			$p = AccountFacade::instance()->findById($loggedAccount->id);
			BasicFacade::instance()->commit();
			
			$account = $p && isset($p->accounts) ? reset($p->accounts) : NULL;
			$student = $p && isset($p->students) ? reset($p->students) : NULL;
			
			if ($account) {
				$ok = TRUE;
			}
		}
		
		BasicFacade::instance()->closeDataSource();
		
		if (!$ok) {
			WebUtils::removeSESSION(__SKEY);
			WebUtils::redirect(__SITE_CONTEXT);
			return;
		}
		
		$loggedAccount->username = $account->username;
		$loggedAccount->role = $account->role;
		$this->ui->loggedAccount = $loggedAccount;
		
		$this->ui->account = $account; // for DashboardUI
		$this->ui->student = $student; // for DashboardUI
		
		$this->ui->pageTitle = 'Dashboard';
		$this->ui->pageContent = 'DashboardUI.php';
		
		$this->ui->headerMenuItem = 'dashboard';
		
		$this->ui->show('layout/MainLayout.php');
	}
	
	/*public function test() {
		$server = new SoapServer(__SITE_PATH . '/wsdl/test.wsdl');
		$server->setObject( new MyService() );
		
		$server->handle();
	}*/

}

?>
