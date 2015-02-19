<?php

__import('business/StudentHome');

class Account {
	private $id;
	private $username;
	//private $password; // No need to store this
	private $role;
	private $fullname;
	private $gender;
	private $dob;
	private $studentAisId;
	private $isActive;
	
	private $studentAis;
	private $hasLoadStudentAis = FALSE;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setBasicData($username, $fullname, $gender, $dob, $role, $studentAisId, $isActive) {
		$this->username		= $username;
		$this->fullname		= $fullname;
		$this->gender		= $gender;
		$this->dob			= $dob;
		$this->role			= $role;
		$this->studentAisId	= $studentAisId;
		$this->isActive		= $isActive;
	}
	
	public function loadStudentAis() {
		if ($this->role != Role::STUDENT) {
			$this->clearStudentAis();
			return NULL;
		}
		
		if ( !$this->hasLoadStudentAis ) {
			$this->studentAis = StudentHome::instance()->findById( $this->studentAisId );
			$this->hasLoadStudentAis = TRUE;
		}
		
		return $this->studentAis;
	}
	
	public function clearStudentAis() {
		$this->studentAis = NULL;
		$this->hasLoadStudentAis = FALSE;
	}
	
	public function clearAllExternals() {
		$this->clearStudentAis();
	}
	
	public function getId() { return $this->id; }
	public function getUsername() { return $this->username; }
	public function getFullname() { return $this->fullname; }
	public function getGender() { return $this->gender; }
	public function getRole() { return $this->role; }
	public function getDob() { return $this->dob; }
	public function getStudentAisId() { return $this->studentAisId; }
	public function isActive() { return $this->isActive; }
	
	public function getStudentAis() { return $this->studentAis; }
}
?>
