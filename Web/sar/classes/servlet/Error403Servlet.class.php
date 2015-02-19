<?php

__import('servlet/AbstractServlet');

class Error403Servlet extends AbstractServlet {
	
	public function checkPermission($action) {
		return TRUE;
	}

	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$this->ui->pageTitle = 'Access denied';
		$this->ui->pageContent = 'Error403UI.php';
		
		$this->ui->show('layout/MainLayout.php');
	}

}

?>
