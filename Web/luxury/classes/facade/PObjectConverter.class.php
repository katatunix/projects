<?php

__import('facade/PObject');
__import('business/Account');
__import('business/Product');

class PObjectConverter {
	private $res;
	
	public function __construct() {
		$this->res = new PObject();
	}
	
	public function getResult() {
		return $this->res;
	}
	
	public function convertAccount($obj) {
		if (is_null($obj)) return NULL;
		
		if ( !isset($this->res->accounts) ) {
			$this->res->accounts = array();
		}
		
		if ( isset( $this->res->accounts[$obj->getId()] ) ) return $this->res->accounts[$obj->getId()];
		
		$p = new PObject();
		$p->id			= $obj->getId();
		$p->username	= $obj->getUsername();
		$p->fullname	= $obj->getFullname();
		$p->role		= $obj->getRole();
		$p->roleString	= $obj->getRoleString();
		
		$this->res->accounts[$p->id] = $p;

		//......................................
		// orders
		if ( $obj->hasLoadOrders() && $arr = $obj->getOrders() ) {
			$p->orderIds = array();
			foreach ($arr as $a) {
				$p->orderIds[] = $a->getId();
				$this->convertOrder($a);
			}
		}

		return $p;
	}
	
	public function convertProduct($obj) {
		if (is_null($obj)) return NULL;
		
		if ( !isset($this->res->products) ) {
			$this->res->products = array();
		}
		
		if ( isset( $this->res->products[$obj->getId()] ) ) return $this->res->products[$obj->getId()];
		
		$p = new PObject();
		$p->id              = $obj->getId();
		$p->name            = $obj->getName();
		$p->category		= $obj->getCategory();
		$p->categoryString	= $obj->getCategoryString();
		$p->unitPrice		= $obj->getUnitPrice();
		
		$this->res->products[$p->id] = $p;

		//......................................
		// orderItems
		if ( $obj->hasLoadOrderItems() && $arr = $obj->getOrderItems() ) {
			$p->orderItemIds = array();
			foreach ($arr as $a) {
				$p->orderItemIds[] = $a->getId();
				$this->convertOrderItem($a);
			}
		}

		return $p;
	}

	public function convertOrder($obj) {
		if (is_null($obj)) return NULL;

		if ( !isset($this->res->orders) ) {
			$this->res->orders = array();
		}

		if ( isset( $this->res->orders[$obj->getId()] ) ) return $this->res->orders[$obj->getId()];

		$p = new PObject();
		$p->id				    = $obj->getId();
		$p->createdDatetime	    = $obj->getCreatedDatetime();
		$p->customer		    = $obj->getCustomer();
		$p->consumedDatetime	= $obj->getConsumedDatetime();
		$p->status              = $obj->getStatus();
		$p->statusString        = $obj->getStatusString();
		$p->accountId  	    	= $obj->getAccountId();

		$this->res->orders[$p->id] = $p;

		//--------------------------------------------------------------------
		// externals
		if ( $obj->hasLoadAccount() && $ex = $obj->getAccount() ) {
			$this->convertAccount( $ex );
		}
		//......................................
		// orderItems
		if ( $obj->hasLoadOrderItems() && $arr = $obj->getOrderItems() ) {
			$p->orderItemIds = array();
			foreach ($arr as $a) {
				$p->orderItemIds[] = $a->getId();
				$this->convertOrderItem($a);
			}
		}

		return $p;
	}

	public function convertOrderItem($obj) {
		if (is_null($obj)) return NULL;

		if ( !isset($this->res->orderItems) ) {
			$this->res->orderItems = array();
		}

		if ( isset( $this->res->orderItems[$obj->getId()] ) ) return $this->res->orderItems[$obj->getId()];

		$p = new PObject();
		$p->id				    = $obj->getId();
		$p->orderId	    		= $obj->getOrderId();
		$p->productId		    = $obj->getProductId();
		$p->quantity			= $obj->getQuantity();

		$this->res->orderItems[$p->id] = $p;

		//--------------------------------------------------------------------
		// externals
		if ( $obj->hasLoadOrder() && $ex = $obj->getOrder() ) {
			$this->convertOrder( $ex );
		}
		if ( $obj->hasLoadProduct() && $ex = $obj->getProduct() ) {
			$this->convertProduct( $ex );
		}

		return $p;
	}
	
}

?>
