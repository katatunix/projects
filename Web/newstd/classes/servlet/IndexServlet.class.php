<?php

__import('servlet/AbstractServlet');

class IndexServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		return true;
	}
	
	public function index() {
		$this->ui->pageTitle = 'Home';
		$this->ui->pageContent = 'IndexUI.php';
		
		$this->ui->show('MainLayout.php');
	}

}

?>
