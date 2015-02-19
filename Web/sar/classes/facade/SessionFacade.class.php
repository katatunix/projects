<?php

__import('facade/PObject');
__import('facade/PObjectConverter');

__import('business/Session');
__import('business/LabSession');
__import('business/LectureSession');

__import('business/SessionHome');

__import('miscutils/MiscUtils');

class SessionFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new SessionFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findById($sessionId, $withExternalData = FALSE) {
		$session = SessionHome::instance()->findById($sessionId);
		if (!$session) return NULL;
		
		$session->clearAllExternals();
		
		if ($withExternalData) {
			$session->loadCourse()->clearAllExternals();
			$session->loadTeacher()->clearAllExternals();
			$session->loadRoom()->clearAllExternals();
		}
		
		$cv = new PObjectConverter();
		$cv->convertSession($session);
		return $cv->getResult();
	}
	
	public function findDetail($accountId, $sessionId) {
		$session = SessionHome::instance()->findById($sessionId);
		if (!$session) return NULL;
		
		$account = AccountHome::instance()->findById($accountId);
		if (!$account) return NULL;
		$role = $account->getRole();
		
		if ($role == Role::LECTURER && !($session instanceof LectureSession)) {
			return NULL;
		}
		if ($role == Role::TUTOR && !($session instanceof LabSession)) {
			return NULL;
		}
		
		//
		$session->clearAllExternals();
		
		$session->loadCourse()->clearAllExternals();
		$session->loadTeacher()->clearAllExternals();
		$session->loadRoom()->clearAllExternals();
		
		//
		$course = $session->getCourse();
		
		if ($session instanceof LectureSession || $session->getLabGroup() > 0) {
			if ($session instanceof LectureSession) {
				$course->loadRegistrations();
			} else {
				$course->loadRegistrations( $session->getLabGroup() );
			}
			
			$registrations = $course->getRegistrations();
			foreach ($registrations as $r) {
				$r->clearAllExternals();
				$s = $r->loadStudentAis();
				if ($s) $s->clearAllExternals();
			}
			
			$attendances = $session->loadAttendances();
			foreach ($attendances as $a) {
				$a->loadStudentAis()->clearAllExternals();
			}
		}
		
		//
		$cv = new PObjectConverter();
		$cv->convertSession($session);
		$res = $cv->getResult();
		
		//
		// Check permission
		if ($role == Role::STUDENT) {
			//
			$studentAisId = $account->getStudentAisId();
			$found = FALSE;
			foreach ($res->students as $sid => $pStd) {
				if ($sid == $studentAisId) {
					$found = TRUE;
					break;
				}
			}
			if (!$found) return NULL; // the student cannot view this session
			
			// remove Attendance info of other students
			if (isset($res->attendances)) {
				foreach ($res->attendances as $i => $a) {
					if ($a->studentAisId != $studentAisId) {
						//
						unset( $res->attendances[$i]->isPresented );
						if ($a->atype == Attendance::LAB) {
							unset( $res->attendances[$i]->reason );
							unset( $res->attendances[$i]->reasonStatus );
						}
						$res->attendances[$i]->hidden = 1;
					}
				}
			}
			//
		}
		
		//
		if (isset($res->students)) {
			$res->students = MiscUtils::sort($res->students, 'fullname');
		}
		
		return $res;
	}
	
	public function findByFilter($accountId, $courseId, $sessionType, $year, $month, $roomId, $active, $lg,
			$fromDate = NULL, $toDate = NULL, $withExternalData = FALSE) {
		
		$list = SessionHome::instance()->findByFilter(
			$accountId, $courseId, $sessionType, $year, $month, $roomId, $active, $lg, $fromDate, $toDate);
			
		foreach ($list as $session) {
			$session->clearAllExternals();
		}
			
		if ($withExternalData) {
			foreach ($list as $session) {
				$session->loadCourse()->clearAllExternals();
				$session->loadTeacher()->clearAllExternals();
				$session->loadRoom()->clearAllExternals();
			}
		}
		
		$cv = new PObjectConverter();
		
		foreach ($list as $session) {
			$cv->convertSession($session);
		}
		
		$res = $cv->getResult();
		
		if (isset($res->sessions)) {
			// Load attendance status for each session for the student
			$account = AccountHome::instance()->findById($accountId);
			if ($account && $account->getRole() == Role::STUDENT) {
				
				foreach ($list as $session) {
					$attStatus = -1; // not set
					
					$atts = $session->loadAttendances($account->getStudentAisId());
					if ($atts) {
						$att = $atts[0];
						$pres = $att->isPresented();
						if (!is_null($pres)) {
							$attStatus = $pres ? 1 : 0;
						}
					}
					$res->sessions[$session->getId()]->attStatus = $attStatus;
				}
			}
		}
		
		return $res;
	}
	
	/**
	* 
	* @param undefined $tutorId
	* @param undefined $courseId
	* @param undefined $lg
	* @param undefined $fromDate
	* @param undefined $toDate
	* 
	* @return PObject contains: sessions, courses, registrations, students, attendances
	*/
	public function findByRegister($tutorId, $courseId, $lg, $fromDate, $toDate) {
		//
		if (!$tutorId || !$courseId || !$lg) return NULL;
		
		// $sessions should be an array
		$sessions = SessionHome::instance()->findByFilter(
			$tutorId, $courseId, Session::LAB, NULL, NULL, 0, 2, $lg, $fromDate, $toDate);
		
		if (count($sessions) == 0) return NULL;
		
		foreach ($sessions as $session) {
			$session->clearAllExternals();
		
			$session->loadCourse()->clearAllExternals();
			
			foreach ($session->loadAttendances() as $a) {
				$a->clearAllExternals();
			}
			
			// No nedd to loadStudentAis for each attendance because
			// only registered students will be shown
		}
		
		$course = CourseHome::instance()->findById($courseId);
		$course->clearAllExternals();
		
		foreach ($course->loadRegistrations($lg) as $r) {
			$r->loadStudentAis()->clearAllExternals();
		}
		
		//
		$cv = new PObjectConverter();
		foreach ($sessions as $session) {
			$cv->convertSession($session);
		}
		
		$res = $cv->getResult();
		if (isset($res->students)) {
			$res->students = MiscUtils::sort($res->students, 'fullname');
		}
		
		return $res;
	}

	public function insert(
			$courseId, $sessionType, $minutes, $specificDatetime,
			$patternDatetime, $roomId, $teacherId, $labGroup) {
		
		return SessionHome::instance()->insert(
			$courseId, $sessionType, $minutes, $specificDatetime, $patternDatetime, $roomId, $teacherId, $labGroup
		);
	}
	
	public function update(
			$sessionId,
			$courseId, $sessionType, $minutes, $specificDatetime,
			$roomId, $teacherId, $labGroup) {
		
		return SessionHome::instance()->update(
			$sessionId,
			$courseId, $sessionType, $minutes, $specificDatetime,
			$roomId, $teacherId, $labGroup
		);
	}
	
	public function updateActive($sessionId, $isActive) {
		return SessionHome::instance()->updateActive($sessionId, $isActive);
	}
	
	public function delete($sessionId) {
		return SessionHome::instance()->delete($sessionId);
	}
}
?>
