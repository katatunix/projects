<?php


class Account {
	private $id;
	private $username;
	private $fullname;

	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setBasicData($username, $fullname) {
		$this->username		= $username;
		$this->fullname		= $fullname;
	}

	//-----------------------------------------------------------------
	public function clearAllExternals() {

	}

	//-----------------------------------------------------------------
	public function getId() { return $this->id; }
	public function getUsername() { return $this->username; }
	public function getFullname() { return $this->fullname; }

}
?>
