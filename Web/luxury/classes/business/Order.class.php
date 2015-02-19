<?php

__import('business/AccountHome');
__import('business/OrderStatus');

class Order {
	private $id;
	private $createdDatetime;
	private $customer;
	private $consumedDatetime;
	private $status;
	private $accountId;

	private $account = NULL;
	private $hasLoadAccount = false;

	private $orderItems = NULL;
	private $hasLoadOrderItems = false;

	public function __construct($id) {
		$this->id = $id;
	}

	public function setBasicData($createdDatetime, $customer, $consumedDatetime, $status, $accountId) {
		$this->createdDatetime  = $createdDatetime;
		$this->customer         = $customer;
		$this->consumedDatetime = $consumedDatetime;
		$this->status           = $status;
		$this->accountId        = $accountId;
	}

	//---------------------------------------------------------------------------------
	public function getAccount() {
		if ( !$this->hasLoadAccount ) {
			$this->account = AccountHome::instance()->findById( $this->accountId );
			$this->hasLoadAccount = true;
		}

		return $this->account;
	}

	public function hasLoadAccount() { return $this->hasLoadAccount; }

	public function clearAccount() {
		$this->account = NULL;
		$this->hasLoadAccount = false;
	}

	//---------------------------------------------------------------------------------
	public function getOrderItems() {
		if ( !$this->hasLoadOrderItems ) {
			$this->orderItems = OrderItemHome::instance()->findByOrderId( $this->id );
			$this->hasLoadOrderItems = true;
		}

		return $this->orderItems;
	}

	public function hasLoadOrderItems() { return $this->hasLoadOrderItems; }

	public function clearOrderItems() {
		$this->orderItems = NULL;
		$this->hasLoadOrderItems = false;
	}

	//---------------------------------------------------------------------------------
	public function clearAllExternals() {
		$this->clearAccount();
		$this->clearOrderItems();
	}

	//---------------------------------------------------------------------------------
	public function getId() { return $this->id; }
	public function getCreatedDatetime() { return $this->createdDatetime; }
	public function getCustomer() { return $this->customer; }
	public function getConsumedDatetime() { return $this->consumedDatetime; }
	public function getStatus() { return $this->status; }
	public function getStatusString() { return OrderStatus::toString($this->status); }
	public function getAccountId() { return $this->accountId; }

	//---------------------------------------------------------------------------------
	public function isPaid() {
		return $this->status == OrderStatus::PAID;
	}
	
	public function isCanceled() {
		return $this->status == OrderStatus::CANCELED;
	}

	public function getTotalSales() {
		if (!$this->isPaid()) return 0;

		$this->getOrderItems();
		$total = 0;
		foreach ($this->orderItems as $item) {
			$total += $item->getPrice();
		}

		return $total;
	}

	public function isRoom() {
		$this->getOrderItems();
		assert(count($this->orderItems) > 0);
		$item = reset( $this->orderItems );
		return $item->isRoom();
	}
}

?>
