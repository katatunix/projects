<?php
__import('servlet/UILoader');
__import('webutils/WebUtils');
__import('facade/PObject');

abstract class AbstractServlet {
	protected $ui;

	public function __construct() {
		$this->ui = new UILoader();
	}
	
	abstract public function checkPermission($action);
	
	abstract public function index();
	
}

?>
