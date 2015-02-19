<?php

__import('servlet/AbstractServlet');

__import('facade/PObject');
__import('facade/BasicFacade');
__import('facade/ProductFacade');
__import('facade/OrderFacade');

__import('webutils/WebUtils');
__import('miscutils/MiscUtils');

class OrderServlet extends AbstractServlet {
	
	public function checkPermission($action) {
		$account = WebUtils::getSESSION(__SKEY);
		$logged = $account ? true : false;
		switch ($action) {
			case 'index'		:
			case 'createRoom'	:
			case 'createFoodb'	:
			case 'editRoom'		:
			case 'editFoodb'	:
				return $logged;
		}
		return false;
	}

	public function index() {
		if (WebUtils::obtainGET('cw')) {
			$week = MiscUtils::getCurrentWeek();
			$fromDate = $week[0];
			$toDate = $week[1];
		} else {
			$fromDate = WebUtils::obtainGET('fromDate');
			$toDate = WebUtils::obtainGET('toDate');
		}

		BasicFacade::instance()->startTrans();
		$p = OrderFacade::instance()->findByAccountId($this->loggedAccount->id, $fromDate, $toDate);
		BasicFacade::instance()->rollback();

		$this->ui->orders = isset($p->orders) ? $p->orders : array();
		$this->ui->orderItems = isset($p->orderItems) ? $p->orderItems : array();
		$this->ui->products = isset($p->products) ? $p->products : array();

		$this->ui->fromDate = $fromDate;
		$this->ui->toDate = $toDate;

		$this->ui->pageTitle = 'My orders list';
		$this->ui->pageContent = 'local/MyOrdersListUI.php';

		BasicFacade::instance()->closeDataSource();
		$this->ui->show('MainLayout.php');
	}

	public function createRoom() {
		$this->handle(false/*isEdit*/, true/*isRoom*/);
	}
	
	public function createFoodb() {
		$this->handle(false/*isEdit*/, false/*isRoom*/);
	}
	
	public function editRoom() {
		$this->handle(true/*isEdit*/, true/*isRoom*/);
	}
	
	public function editFoodb() {
		$this->handle(true/*isEdit*/, false/*isRoom*/);
	}

	private function handle($isEdit, $isRoom) {
		$customer       = NULL;
		$consumedDate   = NULL;
		$consumedHour   = NULL;
		$consumedMinute = NULL;
		$statusString   = 'Booked';
		$pidList        = NULL;
		$qtyList        = NULL;
		$message        = NULL;
		$isErrorMessage	= false;
		$newOrderId		= 0;
		$orderId		= 0; // for Edit
		$canEditOrder	= false;

		BasicFacade::instance()->startTrans();
		$commit = false;

		$p = $isRoom ? ProductFacade::instance()->findAllRooms() : ProductFacade::instance()->findAllFoodbs();
		$this->ui->products = isset($p->products) ? $p->products : array();

		if (WebUtils::isPOST()) {
			$customer       = WebUtils::obtainPOST('customer');
			$consumedDate   = WebUtils::obtainPOST('consumedDate');
			$consumedHour   = WebUtils::obtainPOST('consumedHour');
			$consumedMinute = WebUtils::obtainPOST('consumedMinute');
			$statusString   = WebUtils::obtainPOST('statusString');

			$pidList = WebUtils::obtainPOST('pidList');
			if ($pidList) {
				$pidList = explode(',', $pidList);
			}
			$qtyList = WebUtils::obtainPOST('qtyList');
			if ($qtyList) {
				$qtyList = explode(',', $qtyList);
			}

			if ($isEdit) {
				//
				$orderId = WebUtils::obtainPOST('orderId');
				$res = OrderFacade::instance()->update( $orderId, $customer, $consumedDate, $consumedHour, $consumedMinute,
					$statusString, $pidList, $qtyList );
				if ($res[0]) {
					$message = 'The order is updated successfully.';
					$isErrorMessage = false;
					$commit = true;
					$canEditOrder = true;
				} else {
					$message = $res[1];
					$isErrorMessage = true;
					$commit = false;
					$canEditOrder = !isset($res[2]);
				}
			} else {
				//
				$res = OrderFacade::instance()->insert( $customer, $consumedDate, $consumedHour, $consumedMinute,
					$statusString, $this->loggedAccount->id,
					$pidList, $qtyList );
				if ($res[0]) {
					$message = 'The order is created successfully with id = ' . $res[1] . '.';
					$isErrorMessage = false;
					$newOrderId = $res[1];
					$commit = true;
				} else {
					$message = $res[1];
					$isErrorMessage = true;
					$commit = false;
				}
			}
		} else { // GET
			if ($isEdit) {
				$orderId = WebUtils::obtainGET('id');
				$p = OrderFacade::instance()->findByIdWithAllExternals($orderId);
				$order = isset($p->orders) ? reset($p->orders) : NULL;

				$canEditOrder = true;
				if ($order) {
					if ($this->loggedAccount->id != $order->accountId) {
						$canEditOrder = false;
						$message = 'Only author of the order can edit it.';
					} else {
						$comp = MiscUtils::splitDatetimeString( $order->consumedDatetime );
						$customer       = $order->customer;
						$consumedDate   = $comp[0];
						$consumedHour   = $comp[1];
						$consumedMinute = $comp[2];
						$statusString   = $order->statusString;
						//
						if (isset($p->orderItems)) {
							$pidList = array();
							$qtyList = array();
							foreach ($p->orderItems as $item) {
								$pidList[] = $item->productId;
								$qtyList[] = $item->quantity;
							}
						}
					}
				} else {
					$canEditOrder = false;
				}

				if (!$canEditOrder) {
					$isErrorMessage = true;
					$commit = false;
				}
			}
		}

		if ($commit) {
			BasicFacade::instance()->commit();
		} else {
			BasicFacade::instance()->rollback();
		}
		BasicFacade::instance()->closeDataSource();

		$this->ui->isEdit           = $isEdit;
		$this->ui->isRoom           = $isRoom;
		$this->ui->customer         = $customer;
		$this->ui->consumedDate     = $consumedDate;
		$this->ui->consumedHour     = $consumedHour;
		$this->ui->consumedMinute   = $consumedMinute;
		$this->ui->statusString     = $statusString;
		$this->ui->pidList          = $pidList;
		$this->ui->qtyList          = $qtyList;
		$this->ui->message          = $message;
		$this->ui->isErrorMessage   = $isErrorMessage;
		$this->ui->newOrderId       = $newOrderId;
		$this->ui->orderId       	= $orderId;
		$this->ui->canEditOrder    	= $canEditOrder;

		if ($isRoom) {
			$this->ui->pageTitle = $isEdit ? 'Edit room order' : 'Create room order';
		} else {
			$this->ui->pageTitle = $isEdit ? 'Edit food & beverage order' : 'Create food & beverage order';
		}

		$this->ui->pageContent = 'local/CreateOrEditOrderUI.php';

		$this->ui->show('MainLayout.php');
	}
	
}
?>
