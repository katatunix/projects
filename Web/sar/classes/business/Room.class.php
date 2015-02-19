<?php

abstract class Room {
	const LEC = 0;
	const LAB = 1;
	
	protected $id;
	protected $name;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function clearAllExternals() {
		
	}
	
	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	
	public function setName($name) { $this->name = $name; }
}

?>
