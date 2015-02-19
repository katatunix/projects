<?php

__import('facade/PObject');
__import('facade/PObjectConverter');

__import('business/StudentHome');
__import('business/AccountHome');
__import('business/RegistrationHome');

class StudentFacade {

	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new StudentFacade();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	public function findById($id) {
		$std = StudentHome::instance()->findById($id);
		if (!$std) return NULL;
		
		$std->clearAllExternals();
		
		$cv = new PObjectConverter();
		$cv->convertStudent($std);
		return $cv->getResult();
	}
	
	public function findStudentAttStatistics($sid) {
		$student = StudentHome::instance()->findById($sid);
		if (!$student) return NULL;
		
		$student->clearAllExternals();
		foreach ($student->loadRegistrations() as $regis) {
			$regis->clearAllExternals();
			
			$regis->loadCourse()->clearAllExternals();
			$course = $regis->getCourse();
			
			if ( $regis->getLabGroup() ) {
				$course->loadSessions( $regis->getLabGroup() );
			} else {
				// student does not belong to any LabSession, so we don't load LabSession
				$course->loadSessions(NULL);
			}
			
			foreach ($course->getSessions() as $ss) {
				$ss->clearAllExternals();
				$ss->loadAttendances( $student->getStudentAisId() );
			}
		}
		
		$cv = new PObjectConverter();
		$cv->convertStudent($student);
		$res = $cv->getResult();
		
		foreach ($student->getRegistrations() as $regis) {
			$course = $regis->getCourse();
			
			$totalLectureSessions = 0;
			$countAbsentLecture = 0;
			$countPresentLecture = 0;
			
			$totalLabSessions = 0;
			$countAbsentLab = 0;
			$countPresentLab = 0;
			
			foreach ($course->getSessions() as $ss) {
				if ($ss instanceof LectureSession) {
					$totalLectureSessions++;
					$atts = $ss->getAttendances();
					if ($atts) {
						$pres = $atts[0]->isPresented();
						if (!is_null($pres)) {
							if ($pres) $countPresentLecture++;
							else $countAbsentLecture++;
						}
					}
				} else {
					$totalLabSessions++;
					$atts = $ss->getAttendances();
					if ($atts) {
						$pres = $atts[0]->isPresented();
						if (!is_null($pres)) {
							if ($pres) $countPresentLab++;
							else $countAbsentLab++;
						}
					}
				}
			}
			
			//
			$c = $res->courses[$course->getId()];
			$c->totalLectureSessions = $totalLectureSessions;
			$c->countAbsentLecture = $countAbsentLecture;
			$c->countPresentLecture = $countPresentLecture;
			
			$c->totalLabSessions = $totalLabSessions;
			$c->countAbsentLab = $countAbsentLab;
			$c->countPresentLab = $countPresentLab;
			
			$c->labGroup = $regis->getLabGroup();
		}
		
		return $res;
		
	}
	
	public function findByFilter($cid, $sk, $lg) {
		$list = StudentHome::instance()->findByFilter($cid, $sk, $lg);
		
		$cv = new PObjectConverter();
		
		foreach ($list as $student) {
			$student->clearAllExternals();
			
			if ($cid) {
				$student->loadRegistrations(-1, -1, $cid);
			}
			
			$student->loadAccount();
			
			$cv->convertStudent($student);
		}
		
		$res = $cv->getResult();
		
		if (isset($res->students)) $res->students = MiscUtils::sort($res->students, 'fullname');
		
		return $res;
	}
	
	public function genAccount($studentAisIdList) {
		return AccountHome::instance()->genAccount($studentAisIdList);
	}
	
	public function assignGroups($studentAisIdList, $groupsList, $courseId) {
		return RegistrationHome::instance()->assignGroups($studentAisIdList, $groupsList, $courseId);
	}
	
	public function randomlyAssign($courseId, $maxnum) {
		return RegistrationHome::instance()->assignGroupsRandomly($courseId, $maxnum);
	}
	
	public function getFreeStudentsWithCourse($courseId) {
		$students = StudentHome::instance()->getFreeStudentsWithCourse($courseId);
		
		$cv = new PObjectConverter();
		
		foreach ($students as $std) {
			$std->clearAllExternals();
			$cv->convertStudent($std);
		}
		
		$res = $cv->getResult();
		if (isset($res->students)) {
			$res->students = MiscUtils::sort($res->students, 'fullname');
		}
		
		return $res;
	}
	
	public function addTemp($cid, $studentAisIdList) {
		return RegistrationHome::instance()->addTemp($cid, $studentAisIdList);
	}
	
	public function removeTemp($cid, $studentAisIdList) {
		return RegistrationHome::instance()->removeTemp($cid, $studentAisIdList);
	}
	
}
