<?php

__import('business/Category');
__import('business/OrderItemHome');

class Product {
	private $id;
	private $name;
	private $category;
	private $unitPrice;

	private $orderItems = NULL;
	private $hasLoadOrderItems;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setBasicData($name, $category, $unitPrice) {
		$this->name			= $name;
		$this->category		= $category;
		$this->unitPrice	= $unitPrice;
	}

	//------------------------------------------------------------------
	public function getOrderItems() {
		if ( !$this->hasLoadOrderItems ) {
			$this->orderItems = OrderItemHome::instance()->findByProductId($this->id);
			$this->hasLoadOrderItems = true;
		}

		return $this->orderItems;
	}

	public function hasLoadOrderItems() { return $this->hasLoadOrderItems; }

	public function clearOrderItems() {
		$this->orderItems = NULL;
		$this->hasLoadOrderItems = false;
	}

	//------------------------------------------------------------------
	public function clearAllExternals() {
		$this->clearOrderItems();
	}

	//------------------------------------------------------------------
	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getCategory() { return $this->category; }
	public function getCategoryString() { return Category::toString( $this->category ); }
	public function getUnitPrice() { return $this->unitPrice; }

	public function isRoom() {
		return $this->category == Category::ROOM;
	}

	//------------------------------------------------------------------
	public function getPaidQuantity($fromDate, $toDate) {
		$this->getOrderItems();

		$totalQty = 0;
		foreach ($this->orderItems as $item) {
			$added = $item->getConsumedQtyInDateRange($fromDate, $toDate);
			$totalQty += $added;
		}

		return $totalQty;
	}

	/**
	 * For room, check the room is free at the specific date + qty
	 * @param $date
	 * @param $qty
	 * @param $exceptionOrderId
	 * @return bool
	 */
	public function isFree($date, $qty, $exceptionOrderId) {
		if (!$this->isRoom()) return true;

		$this->getOrderItems();
		$date2 = MiscUtils::addDays($date, $qty - 1);

		foreach ($this->orderItems as $item) {
			if ($exceptionOrderId == 0 || $item->getOrderId() != $exceptionOrderId) {
				$order = $item->getOrder();
				if ( !$order->isCanceled() ) {
					$start = MiscUtils::removeTime( $order->getConsumedDatetime() );
					$duration = $item->getQuantity();
					assert($duration >= 1);
					$start2 = MiscUtils::addDays($start, $duration - 1);

					if ( MiscUtils::isOverlap($date, $date2, $start, $start2) ) {
						return false;
					}
				}
			}
		}

		return true;
	}
}
?>
