<?php

__import('business/Role');
__import('business/OrderHome');

class Account {
	private $id;
	private $username;
	private $fullname;
	private $role;

	private $orders = NULL;
	private $hasLoadOrders = false;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setBasicData($username, $fullname, $role) {
		$this->username		= $username;
		$this->fullname		= $fullname;
		$this->role			= $role;
	}

	//-----------------------------------------------------------------
	public function getOrders() {
		if ( !$this->hasLoadOrders ) {
			$this->orders = OrderHome::instance()->findByAccountId( $this->id );
			$this->hasLoadOrders = true;
		}

		return $this->orders;
	}

	public function hasLoadOrders() { return $this->hasLoadOrders; }

	public function clearOrders() {
		$this->orders = NULL;
		$this->hasLoadOrders = false;
	}

	//-----------------------------------------------------------------
	public function clearAllExternals() {
		$this->clearOrders();
	}

	//-----------------------------------------------------------------
	public function getId() { return $this->id; }
	public function getUsername() { return $this->username; }
	public function getFullname() { return $this->fullname; }
	public function getRole() { return $this->role; }
	public function getRoleString() { return Role::toString( $this->role ); }

	//-----------------------------------------------------------------
	public function isStaff() {
		return $this->role == Role::STAFF;
	}

}
?>
