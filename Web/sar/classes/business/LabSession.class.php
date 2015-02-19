<?php

__import('business/Session');

class LabSession extends Session {
	private $labGroup;
	
	public function __construct($id) {
		parent::__construct($id);
	}
	
	public function setLabGroup($v) { $this->labGroup = $v; }
	public function getLabGroup() { return $this->labGroup; }
}

?>
