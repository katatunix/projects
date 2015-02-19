<?php

__import('facade/PObject');

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

		$this->res->accounts[$p->id] = $p;

		return $p;
	}
	
}

?>
