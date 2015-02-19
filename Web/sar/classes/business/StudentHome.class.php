<?php

__import('business/AbstractHome');
__import('business/Student');
__import('business/AisFakeData');

class StudentHome extends AbstractHome {
	// Singleton pattern ================================================================
	private static $instance = NULL;
	public static function instance(){if(!self::$instance){self::$instance=new StudentHome();}return self::$instance;}
	private function __construct() { }
	//====================================================================================
	
	private function create($id) {
		$obj = $this->findObjectInPool($id);
		if (!$obj) $obj = new Student($id);
		$this->addObjectToPool($id, $obj);
		return $obj;
	}
	//====================================================================================
	
	public function findById($studentAisId) {
		if (!$studentAisId) return NULL;
		
		if ($obj = $this->findObjectInPool($studentAisId)) return $obj;
		
		if ( !isset(AisFakeData::$fakeStudentsList[$studentAisId]) ) return NULL;
		
		$student = $this->create($studentAisId);
		
		$fake = AisFakeData::$fakeStudentsList[$studentAisId];
		$student->setBasicData($fake['fullname'], $fake['gender'], $fake['dob']);
		
		return $student;
	}
	
	// both of temporary and official
	public function findCourseIds($studentAisId) {
		$std = $this->findById($studentAisId);
		if (!$std) return array();
		
		$std->clearRegistrations(); // clear old data
		$registrations = $std->loadRegistrations(); // load full data of Registrations
		
		$ids = array();
		foreach ($registrations as $regis) {
			$ids[] = $regis->getCourseId();
		}
		return $ids;
	}
	
	public function findByFilter($cid, $sk, $lg) {
		$list = array();
		
		foreach (AisFakeData::$fakeStudentsList as $id => $fake) {
			$std = $this->create($id);
			$std->setBasicData( $fake['fullname'], $fake['gender'], $fake['dob'] );
			
			if ($cid) {
				$pass = FALSE;
				$std->clearRegistrations();
				$registrations = $std->loadRegistrations();
				
				if ( !is_null($lg) && $lg > -1 ) {
					foreach ($registrations as $regis) {
						if ($regis->getCourseId() == $cid) {
							$labGroup = $regis->getLabGroup();
							if ( (is_null($labGroup) && $lg == 0) || ($labGroup == $lg) ) {
								$pass = TRUE;
								break;
							}
						}
					}
				} else {
					foreach ($registrations as $regis) {
						if ($regis->getCourseId() == $cid) {
							$pass = TRUE;
							break;
						}
					}
				}
				
				if (!$pass) continue;
			}
			
			if ($sk) {
				$pass =		stripos($std->getFullname(),	$sk) !== FALSE
						||	stripos($std->getDob(),			$sk) !== FALSE
						||	stripos($std->getId(),			$sk) !== FALSE;
				if (!$pass) continue;
			}
			
			$list[] = $std;
		}
		
		return $list;
	}
	
	public function getFreeStudentsWithCourse($courseId) {
		$resultList = array();
		
		$students = $this->findByFilter(0, NULL, -1);
		$registrations = RegistrationHome::instance()->findByFilter($courseId);
		
		foreach ($students as $std) {
			$found = FALSE;
			foreach ($registrations as $regis) {
				if ($regis->getStudentAisId() == $std->getId()) {
					$found = TRUE;
					break;
				}
			}
			if ( !$found ) {
				$resultList[] = $std;
			}
		}
		
		return $resultList;
	}
}

?>
