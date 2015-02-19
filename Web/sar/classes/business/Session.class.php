<?php

__import('business/CourseHome');
__import('business/RoomHome');
__import('business/AccountHome');
__import('business/AttendanceHome');

abstract class Session {
	
	const LEC = 0;
	const LAB = 1;
	
	protected $id;
	protected $startDatetime;
	protected $minutes;
	protected $isActive;
	protected $courseId;
	protected $roomId;
	protected $teacherId;
	
	protected $course;
	protected $hasLoadCourse = FALSE;
	
	protected $room;
	protected $hasLoadRoom = FALSE;
	
	protected $teacher;
	protected $hasLoadTeacher = FALSE;
	
	protected $attendances;
	protected $hasLoadAttendances = FALSE;
	
	public function __construct($id) {
		$this->id = $id;
	}
	
	public function setBasicData($startDatetime, $minutes, $isActive, $courseId, $roomId, $teacherId) {
		$dt = new DateTime($startDatetime);
		$this->startDatetime	= $dt->format('Y-m-d H:i');
		$this->minutes			= $minutes;
		$this->isActive			= $isActive;
		$this->courseId			= $courseId;
		$this->roomId			= $roomId;
		$this->teacherId		= $teacherId;
	}
	
	//
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
	
	//
	public function loadRoom() {
		if ( !$this->hasLoadRoom ) {
			$this->room = RoomHome::instance()->findById( $this->roomId );
			$this->hasLoadRoom = TRUE;
		}
		
		return $this->room;
	}
	
	public function clearRoom() {
		$this->room = NULL;
		$this->hasLoadRoom = FALSE;
	}
	
	//
	public function loadTeacher() {
		if ( !$this->hasLoadTeacher ) {
			$this->teacher = AccountHome::instance()->findById( $this->teacherId );
			$this->hasLoadTeacher = TRUE;
		}
		
		return $this->teacher;
	}
	
	public function clearTeacher() {
		$this->teacher = NULL;
		$this->hasLoadTeacher = FALSE;
	}
	
	/**
	* 
	* @param int $studentAisId zero means all
	* 
	* @return
	*/
	public function loadAttendances($studentAisId = 0) {
		if ( !$this->hasLoadAttendances ) {
			if ($studentAisId == 0) {
				$this->attendances = AttendanceHome::instance()->findBySessionId($this->id);
			} else {
				$this->attendances = array();
				if ($this instanceof LectureSession) {
					$att = AttendanceHome::instance()->findById_LecAtt($this->id, $studentAisId);
				} else {
					$att = AttendanceHome::instance()->findById_LabAtt($this->id, $studentAisId);
				}
				if ($att) {
					$this->attendances[] = $att;
				}
			}
			$this->hasLoadAttendances = TRUE;
		}
		
		return $this->attendances;
	}
	
	public function clearAttendances() {
		$this->attendances = NULL;
		$this->hasLoadAttendances = FALSE;
	}
	
	//
	public function clearAllExternals() {
		$this->clearCourse();
		$this->clearRoom();
		$this->clearTeacher();
		$this->clearAttendances();
	}
	
	public function getId() { return $this->id; }
	
	public function getStartDatetime() { return $this->startDatetime; }
	
	public function getMinutes() { return $this->minutes; }
	
	public function isActive() { return $this->isActive; }
	
	public function getCourseId() { return $this->courseId; }
	
	public function getRoomId() { return $this->roomId; }
	
	public function getTeacherId() { return $this->teacherId; }
	
	public function getCourse() { return $this->course; }
	
	public function getRoom() { return $this->room; }
	
	public function getTeacher() { return $this->teacher; }
	
	public function getAttendances() { return $this->attendances; }
	
}
?>
