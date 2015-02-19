<?php

__import('facade/PObject');

__import('business/Role');
__import('business/RStatus');
__import('business/Session');
__import('business/Attendance');
__import('business/Room');

class PObjectConverter {
	private $res;
	
	private static $GENDERS = array('Male', 'Female');
	
	public function __construct() {
		$this->res = new PObject();
	}
	
	public function getResult() {
		return $this->res;
	}
	
	public function convertSession($obj) {
		if (is_null($obj) || !($obj instanceof Session)) return;
		
		if ( !isset($this->res->sessions) ) {
			$this->res->sessions = array();
		}
		
		if ( isset( $this->res->sessions[$obj->getId()] ) ) return;
		
		$p = new PObject();
		
		$p->id				= $obj->getId();
		$p->startDatetime	= $obj->getStartDatetime();
		$p->minutes			= $obj->getMinutes();
		$p->isActive		= $obj->isActive();
		$p->minutes			= $obj->getMinutes();
		$p->courseId		= $obj->getCourseId();
		$p->roomId			= $obj->getRoomId();
		$p->teacherId		= $obj->getTeacherId();
		
		if ($obj instanceof LectureSession) {
			$p->stype = Session::LEC;
		} else {
			$p->stype = Session::LAB;
			$p->labGroup = $obj->getLabGroup();
		}
		
		$this->res->sessions[$p->id] = $p;
		
		//--------------------------------------------------------------------
		// externals
		if ( $ex = $obj->getCourse() ) {
			$this->convertCourse( $ex );
		}
		if ( $ex = $obj->getTeacher() ) {
			$this->convertAccount( $ex );
		}
		if ( $ex = $obj->getRoom() ) {
			$this->convertRoom( $ex );
		}
		
		//......................................
		// attendances
		if ( $arr = $obj->getAttendances() ) {
			$p->attendanceIds = array();
			foreach ($arr as $a) {
				$p->attendanceIds[] = $a->getId();
				$this->convertAttendance($a);
			}
		}
	}

	public function convertCourse($obj) {
		if (is_null($obj)) return;
		
		if ( !isset($this->res->courses) ) {
			$this->res->courses = array();
		}
		
		if ( isset( $this->res->courses[$obj->getId()] ) ) return;
		
		$p = new PObject();
		$p->id			= $obj->getId();
		$p->name		= $obj->getName();
		$p->startDate	= $obj->getStartDate();
		$p->weeks		= $obj->getWeeks();
		
		$this->res->courses[$p->id] = $p;
		
		//......................................
		// registrations
		if ( $arr = $obj->getRegistrations() ) {
			$p->registrationIds = array();
			foreach ($arr as $r) {
				$p->registrationIds[] = $r->getId();
				$this->convertRegistration($r);
			}
		}
		
		//......................................
		// sessions
		if ( $arr = $obj->getSessions() ) {
			$p->sessionIds = array();
			foreach ($arr as $s) {
				$p->sessionIds[] = $s->getId();
				$this->convertSession($s);
			}
		}
		
	}
	
	public function convertAccount($obj) {
		if (is_null($obj)) return;
		
		if ( !isset($this->res->accounts) ) {
			$this->res->accounts = array();
		}
		
		if ( isset( $this->res->accounts[$obj->getId()] ) ) return;
		
		$p = new PObject();
		$p->id			= $obj->getId();
		$p->username	= $obj->getUsername();
		
		$p->role		= $obj->getRole();
		$p->roleString	= Role::toString( $p->role );
		
		$p->fullname	= $obj->getFullname();
		
		$p->gender		= $obj->getGender();
		if (!is_null($p->gender)) {
			$p->genderString = self::$GENDERS[$p->gender];
		}
		
		$p->dob			= $obj->getDob();
		$p->isActive	= $obj->isActive();
		
		$p->studentAisId = $obj->getStudentAisId();
		
		$this->res->accounts[$p->id] = $p;
		
		if ( $ex = $obj->getStudentAis() ) {
			$this->convertStudent( $ex );
		}
	}
	
	public function convertRoom($obj) {
		if (is_null($obj)) return;
		
		if ( !isset($this->res->rooms) ) {
			$this->res->rooms = array();
		}
		
		if ( isset( $this->res->rooms[$obj->getId()] ) ) return;
		
		$p = new PObject();
		$p->id		= $obj->getId();
		$p->name	= $obj->getName();
		$p->rtype	= $obj instanceof LectureTheater ? Room::LEC : Room::LAB;
		
		$this->res->rooms[$p->id] = $p;
	}
	
	public function convertStudent($obj) {
		if (is_null($obj)) return;
		
		if ( !isset($this->res->students) ) {
			$this->res->students = array();
		}
		
		if ( isset( $this->res->students[$obj->getId()] ) ) return;
		
		$p = new PObject();
		$p->id				= $obj->getId();
		$p->studentAisId	= $obj->getId();
		$p->fullname		= $obj->getFullname();
		$p->gender			= $obj->getGender();
		if (!is_null($p->gender)) {
			$p->genderString = self::$GENDERS[$p->gender];
		}
		$p->dob				= $obj->getDob();
		
		$this->res->students[$p->id] = $p;
		
		//
		if ( $ex = $obj->getAccount() ) {
			$this->convertAccount( $ex );
		}
		
		//......................................
		// registrations
		if ( $arr = $obj->getRegistrations() ) {
			$p->registrationIds = array();
			foreach ($arr as $r) {
				$p->registrationIds[] = $r->getId();
				$this->convertRegistration($r);
			}
		}
	}
	
	public function convertRegistration($obj) {
		if (is_null($obj)) return;
		
		if ( !isset($this->res->registrations) ) {
			$this->res->registrations = array();
		}
		
		if ( isset( $this->res->registrations[$obj->getId()] ) ) return;
		
		$p = new PObject();
		$p->id				= $obj->getId();
		$p->courseId		= $obj->getCourseId();
		$p->studentAisId	= $obj->getStudentAisId();
		$p->labGroup		= $obj->getLabGroup();
		$p->isTemp			= $obj->isTemp();
		
		$this->res->registrations[$p->id] = $p;
		
		if ( $ex = $obj->getCourse() ) {
			$this->convertCourse( $ex );
		}
		if ( $ex = $obj->getStudentAis() ) {
			$this->convertStudent( $ex );
		}
	}
	
	public function convertAttendance($obj) {
		if (is_null($obj)) return;
		
		if ( !isset($this->res->attendances) ) {
			$this->res->attendances = array();
		}
		
		if ( isset( $this->res->attendances[$obj->getId()] ) ) return;
		
		$p = new PObject();
		$p->id				= $obj->getId();
		$p->sessionId		= $obj->getSessionId();
		$p->studentAisId	= $obj->getStudentAisId();
		$p->isPresented		= $obj->isPresented();
		
		if ($obj instanceof LabAttendance) {
			$p->atype				= Attendance::LAB;
			$p->reason				= $obj->getReason();
			$p->reasonStatus		= $obj->getReasonStatus();
			
			if ($p->reasonStatus) {
				$p->reasonStatusString = RStatus::toString($p->reasonStatus);
			}
			
		} else {
			$p->atype			= Attendance::LEC;
		}
		
		$this->res->attendances[$p->id] = $p;
		
		if ( $ex = $obj->getSession() ) {
			$this->convertSession( $ex );
		}
		if ( $ex = $obj->getStudentAis() ) {
			$this->convertStudent( $ex );
		}
	}
	
}

?>
