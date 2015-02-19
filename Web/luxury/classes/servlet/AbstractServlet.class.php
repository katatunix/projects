<?php
__import('servlet/UILoader');
__import('webutils/WebUtils');
__import('facade/PObject');

abstract class AbstractServlet {
	protected $ui;
	protected $resortInfo;

	public function __construct() {
		$this->ui = new UILoader();
	}

	public function init() {
		$this->loggedAccount = WebUtils::getSESSION(__SKEY);
		$this->ui->loggedAccount = $this->loggedAccount;

		$this->resortInfo = new PObject();
		$xml = MiscUtils::loadXmlFile(__RES_DIR_PATH . '/private/ResortInfo.xml');
		if ($xml) {
			$this->resortInfo->name			= $xml->name;
			$this->resortInfo->address		= $xml->address;
			$this->resortInfo->phone		= $xml->phone;
			$this->resortInfo->isNational	= $xml->isNational == '1';
		} else {
			$this->resortInfo->name			= 'Uknown';
			$this->resortInfo->address		= 'Uknown';
			$this->resortInfo->phone		= 'Uknown';
			$this->resortInfo->isNational	= false;
		}

		$this->ui->resortInfo = $this->resortInfo;
	}
	
	abstract public function checkPermission($action);
	
	abstract public function index();
	
}

?>
