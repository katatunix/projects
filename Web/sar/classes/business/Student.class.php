<?php

__import('business/AccountHome');
__import('business/RegistrationHome');

class Student {
	private $studentAisId;
	private $fullname;
	private $gender;
	private $dob;
	
	private $registrations;
	private $hasLoadRegistrations = FALSE;
	
	private $account;
	private $hasLoadAccount = FALSE;
	
	public function __construct($studentAisId) {
		$this->studentAisId = $studentAisId;
	}
	
	public function setBasicData($fullname, $gender, $dob) {
		$this->fullname = $fullname;
		$this->gender = $gender;
		$this->dob = $dob;
	}
	
	/**
	* 
	* @param int $labGroup		-1: all; -2: greater than zero;
	* @param int $isTemp		-1: all; 0: false; 1: true;
	* 
	* @return
	*/
	public function loadRegistrations($labGroup = -1, $isTemp = -1, $courseId = 0) {
		if ( !$this->hasLoadRegistrations ) {
			$this->registrations = RegistrationHome::instance()->findByFilter(
				$courseId, $this->studentAisId, $labGroup, $isTemp);
			$this->hasLoadRegistrations = TRUE;
		}
		
		return $this->registrations;
	}
	
	public function clearRegistrations() {
		$this->registrations = NULL;
		$this->hasLoadRegistrations = FALSE;
	}
	
	//
	public function loadAccount() {
		if ( !$this->hasLoadAccount ) {
			$this->account = AccountHome::instance()->findByStudentAisId( $this->studentAisId );
			$this->hasLoadAccount = TRUE;
		}
		
		return $this->account;
	}
	
	public function clearAccount() {
		$this->account = NULL;
		$this->hasLoadAccount = FALSE;
	}
	
	//
	public function clearAllExternals() {
		$this->clearRegistrations();
	}
	
	public function getId() { return $this->studentAisId; }
	public function getStudentAisId() { return $this->studentAisId; }
	
	public function getFullname() { return $this->fullname; }
	public function getGender() { return $this->gender; }
	public function getDob() { return $this->dob; }
	
	public function getRegistrations() { return $this->registrations; }
	public function getAccount() { return $this->account; }
	
	
}
?>
