<?php

__import('business/CourseHome');
__import('business/StudentHome');

class Registration {
	private $courseId;
	private $studentAisId;
	
	private $labGroup;
	private $isTemp;
	
	private $course;
	private $hasLoadCourse = FALSE;
	
	private $studentAis;
	private $hasLoadStudentAis = FALSE;
	
	public function __construct($courseId, $studentAisId) {
		$this->courseId = $courseId;
		$this->studentAisId = $studentAisId;
	}
	
	public function setTemp($v) {
		$this->isTemp = $v;
	}
	
	public function setLabGroup($v) {
		$this->labGroup = $v;
	}
	
	public function loadCourse() {
		if ( !$this->hasLoadCourse ) {
			$this->course = CourseHome::instance()->findById( $this->courseId );
			$this->hasLoadCourse = TRUE;
		}
		
		return $this->course;
	}
	
	public function clearCourse() {
		$this->course = NULL;
		$this->hasLoadCourse = FALSE;
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
		$this->clearCourse();
		$this->clearStudentAis();
	}
	
	public function getId() {
		return $this->courseId . '_' . $this->studentAisId;
	}
	
	public function getCourseId() { return $this->courseId; }
	public function getStudentAisId() { return $this->studentAisId; }
	
	public function isTemp() { return $this->isTemp; }
	public function getLabGroup() { return $this->labGroup; }
	
	public function getCourse() { return $this->course; }
	public function getStudentAis() { return $this->studentAis; }
}

?>
