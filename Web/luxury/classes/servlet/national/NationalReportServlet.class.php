<?php

__import('servlet/AbstractServlet');

__import('facade/PObject');
__import('facade/BasicFacade');
__import('facade/ProductFacade');

__import('webutils/WebUtils');

class NationalReportServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		if ($account) $role = $account->role;
		$logged = $account ? true : false;
		switch ($action) {
			case 'index'	:
			case 'room'		:
			case 'foodb'	:
				return $logged && BasicFacade::instance()->isNatMan($role);
		}
		return false;
	}
	
	public function index() {
		WebUtils::redirect(__SITE_CONTEXT . '/national/nationalReport/room');
	}

	public function room() {
		$this->handle(true);
	}

	public function foodb() {
		$this->handle(false);
	}

	private function handle($isRoom) {
		$isErrorMessage = false;
		$message = array();

		if (WebUtils::obtainGET('cw')) {
			$week = MiscUtils::getCurrentWeek();
			$fromDate = $week[0];
			$toDate = $week[1];
		} else {
			$fromDate = WebUtils::obtainGET('fromDate');
			$toDate = WebUtils::obtainGET('toDate');
		}

		$countDays = $fromDate && $toDate ? MiscUtils::countDays($fromDate, $toDate) : 0;

		//-----------------------------------------------------------------------------------
		// Get local report
		BasicFacade::instance()->startTrans();
		$p = ProductFacade::instance()->getProductReport($isRoom, $fromDate, $toDate);
		BasicFacade::instance()->rollback();
		$p = isset($p->products) ? $p->products : array();

		$products = array();
		foreach ($p as $prod) {
			$obj = new stdClass();
			$obj->productName	= $prod->name;
			$obj->unitPrice		= $prod->unitPrice;
			$obj->paidQty		= $prod->paidQty;
			$obj->resortName	= $this->resortInfo->name . ' (national)';
			$products[] = $obj;
		}

		//-----------------------------------------------------------------------------------
		// Get remote report
		$infoFile = 'LocalResortsList.xml';
		$xml = MiscUtils::loadXmlFile(__RES_DIR_PATH . '/private/' . $infoFile);
		if (!$xml) {
			$isErrorMessage = true;
			$message[] = "Could not load the $infoFile file.";
		} else {
			foreach ($xml->resort as $resort) {
				$wsdl		= (string)$resort->wsdl;
				$key		= (string)$resort->key;
				$resortName	= (string)$resort->name;

				try {
					$client = new SoapClient( $wsdl, array('cache_wsdl' => WSDL_CACHE_NONE) );
					$result = $client->getReport( $key, $isRoom ? 1 : 0, $fromDate, $toDate );
				} catch (SoapFault $ex) {
					$result = NULL;
				}

				if (!$result) {
					$message[] = 'Could not connect to the resort: ' . $resortName;
				} else {
					if ($result['success'] == '1') {
						foreach ($result['report']->Struct as $prod) {
							$prod->resortName = $resortName;
							$products[] = $prod;
						}
					} else {
						$message[] = 'The service key is not matched with the resort: ' . $resortName;
					}
				}
			}
		}

		//-----------------------------------------------------------------------------------
		BasicFacade::instance()->closeDataSource();

		$this->ui->isRoom = $isRoom;
		$this->ui->isErrorMessage = $isErrorMessage;
		$this->ui->message = $message;

		$this->ui->fromDate = $fromDate;
		$this->ui->toDate = $toDate;
		$this->ui->countDays = $countDays;

		$this->ui->products = $products;

		$this->ui->pageTitle = $isRoom ? 'National report of room occupancy' :
			'National report of food & beverage occupancy';
		$this->ui->pageContent = 'national/NationalReportProductUI.php';

		$this->ui->show('MainLayout.php');
	}
	
}
?>
