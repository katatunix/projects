<?php

__import('business/RegistrationHome');

class Course {
	private $id;
	private $name;
	private $startDate;
	private $weeks;
	
	private $registrations;
	private $hasLoadRegistrations = FALSE;
	
	private $sessions;
	private $hasLoadSessions = FALSE;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setBasicData($name, $startDate, $weeks) {
		$this->name			= $name;
		$this->startDate	= $startDate;
		$this->weeks		= $weeks;
	}
	
	/**
	* 
	* @param int $labGroup		-1: all; -2: greater than zero;
	* @param int $isTemp		-1: all; 0: false; 1: true;
	* 
	* @return
	*/
	public function loadRegistrations($labGroup = -1, $isTemp = -1) {
		if ( !$this->hasLoadRegistrations ) {
			$this->registrations = RegistrationHome::instance()->findByFilter(
				$this->id, 0/*studentAisId*/, $labGroup, $isTemp);
			$this->hasLoadRegistrations = TRUE;
		}
		
		return $this->registrations;
	}
	
	public function clearRegistrations() {
		$this->registrations = NULL;
		$this->hasLoadRegistrations = FALSE;
	}
	
	/**
	* 
	* @param int $labGroup NULL: not load LabSession ; -1: all; 0: lab group zero
	* 
	* @return
	*/
	public function loadSessions($labGroup = NULL) {
		if ( !$this->hasLoadSessions ) {
			if (is_null($labGroup)) {
				$this->sessions = SessionHome::instance()->findByFilter(
					0/*accountId*/, $this->id/*courseId*/, Session::LEC, NULL/*year*/, NULL/*month*/,
					0/*roomId*/, 1/*active*/, NULL/*labGroup*/,
					NULL/*fromDate*/, NULL/*toDate*/
				);
			} else {
				$this->sessions = SessionHome::instance()->findByFilter(
					0/*accountId*/, $this->id/*courseId*/, NULL/*sessionType*/, NULL/*year*/, NULL/*month*/,
					0/*roomId*/, 1/*active*/, $labGroup,
					NULL/*fromDate*/, NULL/*toDate*/
				);
			}
			$this->hasLoadSessions = TRUE;
		}
		
		return $this->sessions;
	}
	
	public function clearSessions() {
		$this->sessions = NULL;
		$this->hasLoadSessions = FALSE;
	}
	
	//
	public function clearAllExternals() {
		$this->clearRegistrations();
		$this->clearSessions();
	}
	
	public function getId() { return $this->id; }
	public function getName() { return $this->name; }
	public function getStartDate() { return $this->startDate; }
	public function getWeeks() { return $this->weeks; }
	
	public function getRegistrations() { return $this->registrations; }
	public function getSessions() { return $this->sessions; }
	
}
?>
