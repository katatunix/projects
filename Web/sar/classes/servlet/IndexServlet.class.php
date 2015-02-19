<?php

__import('servlet/AbstractServlet');

class IndexServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		return TRUE;
	}
	
	public function index() {
		if ( $account = WebUtils::getSESSION(__SKEY) ) $this->ui->loggedAccount = $account;
		
		$this->ui->pageTitle = 'Home';
		$this->ui->pageContent = 'IndexUI.php';
		
		$this->ui->headerMenuItem = 'home';
		
		$this->ui->show('layout/GuestLayout.php');
	}

}

?>
