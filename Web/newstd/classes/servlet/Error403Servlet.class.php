<?php

__import('servlet/AbstractServlet');

class Error403Servlet extends AbstractServlet {
	
	public function checkPermission($action) {
		return true;
	}

	public function index() {
		$this->ui->pageTitle = 'Access denied';
		$this->ui->pageContent = 'Error403UI.php';
		
		$this->ui->show('MainLayout.php');
	}

}

?>
