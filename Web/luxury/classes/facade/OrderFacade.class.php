<?php

__import('facade/PObjectConverter');

__import('business/OrderHome');
__import('business/OrderItemHome');

class OrderFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new OrderFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================

	public function findById($id) {
		$cv = new PObjectConverter();
		$obj = OrderHome::instance()->findById($id);
		if ($obj) {
			$obj->clearAllExternals();
			$cv->convertOrder($obj);
		}

		return $cv->getResult();
	}

	public function findByIdWithAllExternals($id) {
		$cv = new PObjectConverter();
		$obj = OrderHome::instance()->findById($id);
		if ($obj) {
			$obj->clearAllExternals();

			$obj->getAccount();
			$obj->getOrderItems();

			$cv->convertOrder($obj);
		}

		return $cv->getResult();
	}

	public function findByAccountId($accountId, $fromDate, $toDate) {
		$cv = new PObjectConverter();

		$list = OrderHome::instance()->findByAccountId($accountId, $fromDate, $toDate);
		foreach ($list as $obj) {
			$obj->clearAllExternals();

			foreach ($obj->getOrderItems() as $item) {
				$item->clearAllExternals();
				$item->getProduct();
			}

			$cv->convertOrder($obj);
		}

		return $cv->getResult();
	}

	public function getStaffReport($staffId, $fromDate, $toDate) {
		$orders = OrderHome::instance()->findByAccountId($staffId, $fromDate, $toDate);

		$currentDate = NULL;
		$currentObj = NULL;

		$result = array();

		foreach ($orders as $order) {
			if (!$order->isPaid()) continue;

			$date = MiscUtils::removeTime( $order->getCreatedDatetime() );

			if (is_null($currentObj) || $date != $currentDate) {
				$currentDate = $date;
				$currentObj = new PObject();

				$currentObj->date = $date;
				$currentObj->ordersCount = 0;
				$currentObj->roomSales = 0;
				$currentObj->foodbSales = 0;

				$result[] = $currentObj;
			}

			$currentObj->ordersCount++;
			if ($order->isRoom()) {
				$currentObj->roomSales += $order->getTotalSales();
			} else {
				$currentObj->foodbSales += $order->getTotalSales();
			}
		}

		return $result;
	}

	public function insert($customer, $consumedDate, $consumedHour, $consumedMinute,
						   $statusString, $accountId, $pidList, $qtyList) {
		return OrderHome::instance()->insert($customer, $consumedDate, $consumedHour, $consumedMinute,
			$statusString, $accountId, $pidList, $qtyList);
	}

	public function update($orderId, $customer, $consumedDate, $consumedHour, $consumedMinute,
						   $statusString, $pidList, $qtyList) {
		return OrderHome::instance()->update($orderId, $customer, $consumedDate, $consumedHour, $consumedMinute,
			$statusString, $pidList, $qtyList);
	}


}

?>
