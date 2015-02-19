<?php

__import('business/Attendance');

class LabAttendance extends Attendance {
	
	private $reason;
	private $reasonStatus;
	
	public function __construct($sessionId, $studentAisId) {
		parent::__construct($sessionId, $studentAisId);
	}
	
	public function setBasicData($isPresented, $reason = NULL, $reasonStatus = NULL) {
		parent::setBasicData($isPresented);
		$this->reason		= $reason;
		$this->reasonStatus	= $reasonStatus;
	}
	
	public function getReason() { return $this->reason; }
	public function getReasonStatus() { return $this->reasonStatus; }
	
}

?>
