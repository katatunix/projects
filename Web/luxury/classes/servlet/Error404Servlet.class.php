<?php

__import('servlet/AbstractServlet');

class Error404Servlet extends AbstractServlet {
	
	public function checkPermission($action) {
		return true;
	}

	public function index() {
		$this->ui->pageTitle = 'This page is not found';
		$this->ui->pageContent = 'Error404UI.php';
		
		$this->ui->show('MainLayout.php');
	}

}

?>
