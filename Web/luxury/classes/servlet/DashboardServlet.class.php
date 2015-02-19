<?php

__import('servlet/AbstractServlet');

__import('facade/BasicFacade');

__import('webutils/WebUtils');

class DashboardServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? true : false;
		switch ($action) {
			case 'index': return $logged;
		}
		return false;
	}
	
	public function index() {
		$this->ui->pageTitle = 'Dashboard';
		$this->ui->pageContent = 'DashboardUI.php';
		
		$this->ui->show('MainLayout.php');
	}

}

?>
