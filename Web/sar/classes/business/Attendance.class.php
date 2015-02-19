<?php

__import('business/SessionHome');
__import('business/StudentHome');

abstract class Attendance {
	const LEC = 0;
	const LAB = 1;
	
	protected $sessionId;
	protected $studentAisId;
	
	protected $isPresented;
	
	protected $session;
	protected $hasLoadSession = FALSE;
	
	protected $studentAis;
	protected $hasLoadStudentAis = FALSE;
	
	public function __construct($sessionId, $studentAisId) {
		$this->sessionId = $sessionId;
		$this->studentAisId = $studentAisId;
	}
	
	public function setBasicData($isPresented) {
		$this->isPresented = $isPresented;
	}
	
	public function loadSession() {
		if ( !$this->hasLoadSession ) {
			$this->session = SessionHome::instance()->findById( $this->sessionId );
			$this->hasLoadSession = TRUE;
		}
		
		return $this->session;
	}
	
	public function clearSession() {
		$this->session = NULL;
		$this->hasLoadSession = FALSE;
	}
	
	public function loadStudentAis() {
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
		$this->clearSession();
		$this->clearStudentAis();
	}
	
	//
	
	public function getId() {
		return $this->sessionId . '_' . $this->studentAisId;
	}
	
	public function isPresented() { return $this->isPresented; }
	
	public function getSessionId() { return $this->sessionId; }
	public function getStudentAisId() { return $this->studentAisId; }
	
	public function getSession() { return $this->session; }
	public function getStudentAis() { return $this->studentAis; }
	
}

?>
