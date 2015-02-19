<?php

__import('servlet/AbstractServlet');

class Error404Servlet extends AbstractServlet {
	
	public function checkPermission($action) {
		return TRUE;
	}

	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$this->ui->pageTitle = 'This page is not found';
		$this->ui->pageContent = 'Error404UI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}

}

?>
